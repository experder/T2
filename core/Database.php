<?php

namespace core;

use installer\Core_database;
use service\Install_wizard;

class Database {

	/**
	 * Returns id value of the inserted set of data.
	 * Used by the function insert.
	 * @see insert
	 */
	const RETURN_LASTINSERTID = 1;
	/**
	 * Returns result of the SELECT query in form of an associative array.
	 * Used by the function select.
	 * @see select
	 */
	const RETURN_ASSOC = 2;
	/**
	 * Returns the number of rows affected by the last query.
	 * Used by the function delete.
	 * @see delete
	 */
	const RETURN_ROWCOUNT = 3;

	/** @var Database $singleton */
	static private $singleton = null;

	private $pdo;

	private $error=false;

	public function __construct($host, $dbname, $user, $password, $stacktrace_depth=0, $quit_on_error=true) {
		try {
			$this->pdo = new \PDO("mysql:host=" . $host . ";dbname=" . $dbname, $user, $password);
			$this->pdo->query('SET NAMES utf8');
		} catch (\Exception $e) {
			if($quit_on_error){
				Error::quit("Fatal error on database initialization (#1). ".$e->getMessage(), $stacktrace_depth+1);
			}else{
				$this->error= Error::from_exception($e, false);
			}
		}
	}

	public function getError(){
		return $this->error;
	}

	/**
	 * @param bool $quit_on_error
	 * @return Database|false
	 */
	public static function get_singleton($quit_on_error=true) {
		if (self::$singleton === null) {
			if($quit_on_error){
				Error::quit("Please initialize Database singelton first: <code>\\core\\Database::init();</code>", 1);
			}
			return false;
		}
		return self::$singleton;
	}

	public static function init($host, $dbname, $user, $password) {

		if(self::$singleton!==null){
			Error::quit("Database is already initialized!", 1);
		}

		self::$singleton = new Database($host, $dbname, $user, $password, 1, false);

		if(($error=self::$singleton->getError()) && $error instanceof Error){
			if($error->get_type()== Error::TYPE_DB_NOT_FOUND){
				//Database doesn't exist -> Call Installer to initialize Database:
				require_once ROOT_DIR.'/service/Install_wizard.php';
				self::$singleton = Install_wizard::initialize_database($host, $dbname, $user, $password);
				$updater = new Core_database();
				$msg = $updater->update();
				Page::$compiler_messages[]=new Message(Message::TYPE_CONFIRM, "Core databases \"$dbname\" created. $msg");
			}
			if($error->get_type()== Error::TYPE_WRONG_CREDENTIALS){
				Error::quit("Couldn't connect to database. Please check credentials:", 1);
			}
		}
//		else{
//			self::$singleton->iquery("DROP DATABASE $dbname",array(),Database::RETURN_ROWCOUNT);
//			Error::quit("DROPPED!!");
//		}

		if(self::$singleton->getError()){
			Error::quit("Fatal error on database initialization (#2). ".self::$singleton->getError()->get_message(), 1);
		}

		return self::$singleton;
	}

	public static function select_($query, $substitutions=array()){
		return self::get_singleton()->select($query, $substitutions, 1);
	}

	/**
	 * @param string $query
	 * @param array  $substitutions
	 * @return array|false
	 */
	public function select($query, $substitutions=array(), $backtrace_depth=0){
		return self::iquery($query, $substitutions, self::RETURN_ASSOC, true, $backtrace_depth+1);
	}

	public static function select_single_($query, $substitutions=array(), $backtrace_depth=0){
		return self::get_singleton()->select_single($query, $substitutions, $backtrace_depth+1);
	}

	public function select_single($query, $substitutions=array(), $backtrace_depth=0){
		$result = self::iquery($query, $substitutions, self::RETURN_ASSOC, true, $backtrace_depth+1);
		if(!$result){
			return false;
		}
		return $result[0];
	}

	public function get_pdo(){
		return $this->pdo;
	}

	/**
	 * Handles different types of queries, specified by $return
	 * @param string $query
	 * @param int    $return_type Database::RETURN_...
	 * @return array|false|null|string|int
	 */
	private function iquery($query, $substitutions, $return_type, $report_error=true, $backtrace_depth=0) {
		$this->error = false;
		/** @var \PDOStatement $statement */
		$statement = $this->pdo->prepare($query);
		$ok = @$statement->execute($substitutions);
		if(!$ok){
			$compiled_query="";
			$errorInfo = $statement->errorInfo();
			$errorInfo = $errorInfo[2];
			if(!$errorInfo && $statement->errorCode()==='HY093'){
				$errorInfo="Invalid parameter number: parameter was not defined";
			}else{
				ob_flush();
				ob_start();
				$statement->debugDumpParams();
				$compiled_query=ob_get_clean();
				$compiled_query.=Error::HR;
			}

			$this->error = new Error($compiled_query.$errorInfo, Error::TYPE_SQL, $report_error, $backtrace_depth+1);
			return false;
		}
		switch ($return_type) {
			case self::RETURN_LASTINSERTID:
				return $this->pdo->lastInsertId();
				break;
			case self::RETURN_ASSOC:
				return $statement->fetchAll(\PDO::FETCH_ASSOC);
				break;
			case self::RETURN_ROWCOUNT:
				return $statement->rowCount();
				break;
			default:
				return null;/*No return type specified*/
				break;
		}
	}

}