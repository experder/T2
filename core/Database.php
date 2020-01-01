<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


namespace core;

use admin\Install_wizard;
use service\Strings;

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

	private $error = false;

	private static $dev_global_count = 0;

	private $dbname;

	public function __construct($host, $dbname, $user, $password, $stacktrace_depth = 0, $quit_on_error = true) {
		$this->dbname=$dbname;
		try {
			$this->pdo = new \PDO("mysql:host=" . $host . ";dbname=" . $dbname, $user, $password);
			$this->pdo->query('SET NAMES utf8');
		} catch (\Exception $e) {
			if ($quit_on_error) {
				Error::quit("Fatal error on database initialization (#1). " . $e->getMessage(), $stacktrace_depth + 1);
			} else {
				$this->error = Error::from_exception($e, false);
			}
		}
	}

	public function getError() {
		return $this->error;
	}

	public function get_dbname() {
		return $this->dbname;
	}

	/**
	 * @param bool $quit_on_error
	 * @return Database|false
	 */
	public static function get_singleton($quit_on_error = true) {
		if (self::$singleton === null) {
			if ($quit_on_error) {
				Error::quit("Please initialize Database singelton first: <code>\\core\\Database::init();</code>", 1);
			}
			return false;
		}
		return self::$singleton;
	}

	public static function init($host, $dbname, $user, $password, $backtrace_depth = 0) {

		if (self::$singleton !== null) {
			Error::quit("Database is already initialized!", 1);
		}

		self::$singleton = new Database($host, $dbname, $user, $password, 1, false);

		if (($error = self::$singleton->getError()) && $error instanceof Error) {
			if ($error->get_type() == Error::TYPE_DB_NOT_FOUND) {
				//Database doesn't exist -> Call Installer to initialize Database:
				require_once ROOT_DIR . '/admin/Install_wizard.php';
				self::$singleton = Install_wizard::init2_db($host, $dbname, $user, $password, $backtrace_depth + 1);
			}
		}

		if (self::$singleton->getError()) {
			Error::quit("Fatal error on database initialization (#2). " . self::$singleton->getError()->get_message(), 1);
		}

		return self::$singleton;
	}

	public static function select_($query, $substitutions = array(), $report_error = true) {
		return self::get_singleton()->select($query, $substitutions, 1, $report_error);
	}

	/**
	 * @param string $query
	 * @param array  $substitutions
	 * @param int    $backtrace_depth
	 * @return array|false
	 */
	public function select($query, $substitutions = array(), $backtrace_depth = 0, $report_error = true) {
		return self::iquery($query, $substitutions, self::RETURN_ASSOC, $report_error, $backtrace_depth + 1);
	}

	/**
	 * @param string $query
	 * @return int|false
	 */
	public function insert($query) {
		return self::iquery($query, null, self::RETURN_LASTINSERTID);
	}

	public static function select_single_($query, $substitutions = null, $backtrace_depth = 0) {
		return self::get_singleton()->select_single($query, $substitutions, $backtrace_depth + 1);
	}

	public function select_single($query, $substitutions = null, $backtrace_depth = 0) {
		$result = self::iquery($query, $substitutions, self::RETURN_ASSOC, true, $backtrace_depth + 1);
		if (!$result) {
			return false;
		}
		return $result[0];
	}

	public function get_pdo() {
		return $this->pdo;
	}

	public static function get_dev_stats() {
		return self::$dev_global_count . " Queries";
	}

	/**
	 * Handles different types of queries, specified by $return
	 * @param string $query
	 * @param array  $substitutions
	 * @param int    $return_type Database::RETURN_...
	 * @param bool   $report_error
	 * @param int    $backtrace_depth
	 * @return array|int|false|null
	 */
	private function iquery($query, $substitutions, $return_type, $report_error = true, $backtrace_depth = 0) {
		self::$dev_global_count++;
		$this->error = false;
		/** @var \PDOStatement $statement */
		$statement = $this->pdo->prepare($query);
		$ok = @$statement->execute($substitutions);
		if (!$ok) {
			$compiled_query = "";
			$errorInfo = $statement->errorInfo();
			$errorInfo = $errorInfo[2];
			if (!$errorInfo && $statement->errorCode() === 'HY093'/*Invalid parameter number: parameter was not defined*/) {
				$errorInfo = "Invalid parameter number: parameter was not defined";
			} else {
				ob_flush();
				ob_start();
				$statement->debugDumpParams();
				$compiled_query = ob_get_clean();
				$compiled_query = preg_replace("/\\R$/", "", $compiled_query);
				$compiled_query .= Error::HR;
			}
			$error_type = Error::TYPE_SQL;
			if($statement->errorCode()=="42S02"/*Unknown table*/){
				$error_type = Error::TYPE_TABLE_NOT_FOUND;
				$errorInfo.=print_r($statement->errorInfo(),1);
			}

			$this->error = new Error($compiled_query . $errorInfo, $error_type, $report_error, $backtrace_depth + 1);
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

	public static function insert_assoc_($tabelle, $data_set) {
		return self::get_singleton()->insert_assoc($tabelle, $data_set);
	}

	public function insert_assoc($tabelle, $data_set) {
		$keys_sql = array();
		$values_sql = array();
		foreach ($data_set as $key => $value) {
			$keys_sql[] = "`$key`";
			$values_sql[] = ($value === null ? "NULL" : ("'" . Strings::escape_sql($value) . "'"));
		}
		$keys = implode(", ", $keys_sql);
		$values = implode(", ", $values_sql);
		$query2 = "INSERT INTO $tabelle ($keys) VALUES ($values);";
		return self::insert($query2);
	}

	/**
	 * @param string $query
	 * @return int|false
	 */
	public function update($query) {
		return $this->iquery($query, null, self::RETURN_ROWCOUNT);
	}

	public function update_assoc($tabelle, $where, $data_set) {
		$set_sql = array();
		foreach ($data_set as $key => $value) {
			$val_sql = $value === null ? "NULL" : ("'" . Strings::escape_sql($value) . "'");
			$set_sql[] = "`$key` = $val_sql";
		}
		$set = implode(", ", $set_sql);
		$query2 = "UPDATE $tabelle SET $set WHERE $where;";
		return $this->update($query2);
	}

	/**
	 * Adds data ($data_set and $data_where) to table $tabelle if it doesn't yet exist ($data_where).
	 * If $data_where exists the data from $data_set in $tabelle will be updated.
	 * @param string $tabelle
	 * @param array  $data_where
	 * @param array  $data_set
	 * @param int    $backtrace_depth
	 * @return int|false Number of modified rows or ID of the inserted data or false in case of any failure
	 */
	public function update_or_insert($tabelle, $data_where, $data_set, $backtrace_depth = 0) {
		if (empty($data_where) && empty($data_set)) return false;

		//Build the WHERE statement:
		$where_sql = array();
		foreach ($data_where as $key => $value) {
			$val_sql = $value === null ? "IS NULL" : ("= '" . Strings::escape_sql($value) . "'");
			$where_sql[] = "`$key` $val_sql";
		}
		$where = implode(" AND ", $where_sql);

		//Check, if data already exists:
		$query1 = "SELECT count(*) as c FROM $tabelle WHERE $where;";
		$data = $this->select_single($query1, null, $backtrace_depth + 1);
		$anzahl_treffer = $data["c"];

		if ($anzahl_treffer) {
			//Data already exists: UPDATE
			return $this->update_assoc($tabelle, $where, $data_set);
		} else {
			//Data didn't exist: INSERT
			$data_alltogehter = array_merge($data_where, $data_set);
			return $this->insert_assoc($tabelle, $data_alltogehter);
		}
	}

}