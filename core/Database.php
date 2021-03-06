<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core;

use t2\core\service\Arrays;
use t2\core\service\Config;
use t2\core\service\Strings;
use t2\dev\Debug;
use t2\dev\Install_wizard;

class Database {

	/**
	 * Returns id value of the inserted set of data.
	 * Used by the function insert.
	 * @see insert
	 */
	const RETURN_LASTINSERTID = 1;
	/**
	 * Returns result of the SELECT query in form of an associative array.
	 * Used by the functions select and its derivates.
	 * @see select
	 */
	const RETURN_ASSOC = 2;
	/**
	 * Returns the number of rows affected by the last query.
	 * Used by the functions delete and update.
	 * @see delete
	 * @see update
	 */
	const RETURN_ROWCOUNT = 3;

	/** @var Database $singleton */
	static private $singleton = null;

	public $core_prefix;

	private $pdo;

	private $error = false;

	private $dbname;

	public function __construct($host, $dbname, $user, $password, $stacktrace_depth = 0, $quit_on_error = true, $core_prefix = 'core') {
		$this->core_prefix = $core_prefix;
		$this->dbname = $dbname;
		try {
			$this->pdo = new \PDO("mysql:host=" . $host . ";dbname=" . $dbname, $user, $password);
			$this->pdo->query('SET NAMES utf8');
		} catch (\Exception $e) {
			$type = Error::TYPE_EXCEPTION;
			if ($e instanceof \PDOException) {
				if ($e->getCode() === 1049/*Unknown database*/) {
					$type = Error::TYPE_PDO_1049_UNKNOWN_DATABASE;
				} else if ($e->getCode() === 2002/*php_network_getaddresses: getaddrinfo failed*/) {
					$type = Error::TYPE_HOST_UNKNOWN;
				}
			}
			$this->error = Error::from_exception($e, $quit_on_error, $type);
		}
	}

	/**
	 * @return Error|false
	 */
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
		if (self::$singleton === null || !isset(self::$singleton->pdo)) {
			if ($quit_on_error) {
				new Error("ERROR_DB_NOT_INITIALIZED", "ERROR_DB_NOT_INITIALIZED");
			}
			return false;
		}
		return self::$singleton;
	}

	/**
	 * Can be used to invalidate Database to force Page to be Standalone.
	 * Better: Catch recursion when outputting page.
	 */
	public static function destroy() {
		self::$singleton = null;
	}

	public static function init($host, $dbname, $user, $password, $core_prefix = 'core') {

		if (self::$singleton !== null) {
			new Error("ERROR_DB_ALREADY_INITIALIZED", "Database is already initialized!", null, 1);
		}

		self::$singleton = new Database($host, $dbname, $user, $password, 1, false, $core_prefix);
		$err = self::$singleton->getError();

		if ($err !== false) {
			if ($err->getType() == Error::TYPE_PDO_1049_UNKNOWN_DATABASE) {
				self::$singleton = Install_wizard::init_db($host, $dbname, $user, $password);
				$err = false;
			} else if ($err->getType() == Error::TYPE_HOST_UNKNOWN) {
				new Error(Error::TYPE_HOST_UNKNOWN, "Database host unknown! Please check config.", null, 1);
			}
			if ($err !== false) {
				$err->report();
			}
		}

		return self::$singleton;
	}

	/**
	 * @deprecated TODO: Use Database_Service instead.
	 */
	public static function select_($query, $substitutions = array(), $halt_on_error = true) {
		return self::get_singleton()->select($query, $substitutions, 1, $halt_on_error);
	}

	public static function delete_($query, $substitutions = array()) {
		return self::get_singleton()->delete($query, $substitutions, 1);
	}

	/**
	 * @param string $query
	 * @param array  $substitutions
	 * @param int    $backtrace_depth
	 * @param bool   $halt_on_error
	 * @return array|false Result of the SELECT query in form of an associative array
	 */
	public function select($query, $substitutions = array(), $backtrace_depth = 0, $halt_on_error = true) {
		return $this->iquery($query, $substitutions, self::RETURN_ASSOC, $backtrace_depth + 1, $halt_on_error);
	}

	/**
	 * @param string     $query
	 * @param null|array $substitutions
	 * @param int        $backtrace_depth
	 * @return int|false
	 */
	public function insert($query, $substitutions = null, $backtrace_depth = 0) {
		return self::iquery($query, $substitutions, self::RETURN_LASTINSERTID, $backtrace_depth + 1);
	}

	/**
	 * @param string     $query
	 * @param array|null $substitutions
	 * @param int        $backtrace_depth
	 * @return array|false
	 */
	public static function select_single_($query, $substitutions = null, $backtrace_depth = 0) {
		return self::get_singleton()->select_single($query, $substitutions, $backtrace_depth + 1);
	}

	/**
	 * @param string     $query
	 * @param array|null $substitutions
	 * @param int        $backtrace_depth
	 * @param bool       $ignore_following
	 * @return array|false|int
	 */
	public function select_single($query, $substitutions = null, $backtrace_depth = 0, $ignore_following = true) {
		$result = self::iquery($query, $substitutions, self::RETURN_ASSOC, $backtrace_depth + 1);
		if (!$result) {
			return false;
		}
		if (!$ignore_following && ($c = count($result)) > 1) {
			return $c;
		}
		return $result[0];
	}

	public function get_pdo() {
		return $this->pdo;
	}

	/**
	 * Handles different types of queries, specified by $return
	 * @param string $query
	 * @param array  $substitutions
	 * @param int    $return_type Database::RETURN_...
	 * @param bool   $halt_on_error
	 * @param int    $backtrace_depth
	 * @return array|int|false|null
	 */
	private function iquery($query, $substitutions, $return_type, $backtrace_depth = 0, $halt_on_error = true) {
		$this->error = false;
		/** @var \PDOStatement $statement */
		$statement = $this->pdo->prepare($query);
		$ok = @$statement->execute($substitutions);
		$this->debuginfo($statement, $query, $backtrace_depth + 1);
		if (!$ok) {
			$this->error_handling($statement, $query, $halt_on_error, $backtrace_depth + 1);
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

	private function error_handling(\PDOStatement $statement, $query, $halt_on_error, $backtrace_depth = 0) {
		$eInfo = $statement->errorInfo();
		$errorCode = $eInfo[0];
		$errorInfo = "[$errorCode] " . $eInfo[2];
		$errorType = Error::TYPE_SQL;
		if (!$eInfo[2]) {
			if ($errorCode === 'HY093'/*Invalid parameter number: parameter was not defined*/) {
				$errorInfo = "Invalid parameter number: parameter was not defined";
			}
		}
		if ($errorCode === "42S02"/*Unknown table*/) {
			$errorType = Error::TYPE_TABLE_NOT_FOUND;
		}
		ob_flush();
		ob_start();
		$statement->debugDumpParams();
		$debugDump = ob_get_clean();
		$compiled_query = self::get_compiled_query_from_debugDump($debugDump);
		if (!$compiled_query) {
			$compiled_query = ($debugDump ?: $query);
		}

		$this->error = new Error($errorType, $errorInfo, $compiled_query, $backtrace_depth + 1, $halt_on_error);
	}

	private function debuginfo(\PDOStatement $statement, $query, $backtrace_depth = 0) {
		if (Config::$DEVMODE) {
			$backtrace = debug_backtrace();

			ob_flush();
			ob_start();
			$statement->debugDumpParams();
			$debugDump = ob_get_clean();
			$compiled_query = self::get_compiled_query_from_debugDump($debugDump);
			if (!$compiled_query) {
				$compiled_query = ($debugDump ?: $query);
			}

			$caller = (isset($backtrace[$backtrace_depth + 1]['function']) ? $backtrace[$backtrace_depth + 1]['function'] . " " : "")
				. "( " . Debug::backtrace($backtrace_depth + 1, "\n", false) . " )";

			$core_query_class = "";
			if ($caller2 = in_array(str_replace('\\', '/', $caller), Debug::get_core_queries())) {
				$core_query_class = " core_query_class";
				Debug::$queries_corequeries_count++;
				Debug::mark_core_query_checked($caller2);
			}

			$query_html = (new Html("span", $caller, array("class" => "detail_functionSource$core_query_class")))
				. "\n" . (new Html("span", htmlentities($compiled_query), array("class" => "detail_sqlDump$core_query_class")));
			Debug::$queries[] = $query_html;
		}
	}

	/**
	 * @param string $table
	 * @param array  $data_set
	 * @param int    $backtrace_depth
	 * @return false|int
	 */
	public static function insert_assoc_($table, $data_set, $backtrace_depth = 0) {
		return self::get_singleton()->insert_assoc2($table, $data_set, $backtrace_depth + 1);
	}

	/**
	 * @param string $tabelle
	 * @param array  $data_set
	 * @param int    $backtrace_depth
	 * @return false|int
	 */
	public static function insert_assoc2($tabelle, $data_set, $backtrace_depth = 0) {
		$keys_array = array_keys($data_set);
		$keys_prefixed = Arrays::prefix_values(':', $keys_array);
		$database = self::get_singleton();
		$substitutions = array();
		foreach ($data_set as $key => $value) {
			$substitutions[':' . $key] = $value;
		}
		$keys = implode(",", $keys_array);
		$values = implode(",", $keys_prefixed);
		return $database->insert("INSERT INTO $tabelle ($keys) VALUES ($values);", $substitutions, $backtrace_depth+1);
	}

	/**
	 * @param string $query
	 * @param array  $substitutions
	 * @param int    $stacktrace_depth
	 * @return int|false Number of updated rows or FALSE on error
	 */
	public function update($query, $substitutions = array(), $stacktrace_depth = 0) {
		return $this->iquery($query, $substitutions, self::RETURN_ROWCOUNT, $stacktrace_depth + 1);
	}

	/**
	 * @param string $query
	 * @param array  $substitutions
	 * @param int    $stacktrace_depth
	 * @return int|false
	 */
	public function delete($query, $substitutions = array(), $stacktrace_depth = 0) {
		return $this->iquery($query, $substitutions, self::RETURN_ROWCOUNT, $stacktrace_depth + 1);
	}

	/**
	 * @param string $tabelle
	 * @param string $where
	 * @param array  $data_set
	 * @return int|false Number of updated rows or FALSE on error
	 */
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
	 * @return int|false Number of modified rows or ID (*-1) of the inserted data or false in case of any failure
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
			$id = $this->insert_assoc2($tabelle, $data_alltogehter);
			return $id ? -$id : false;
		}
	}

	/**
	 * @param string $dump
	 * @return string|false
	 */
	public static function get_compiled_query_from_debugDump($dump) {
		$compiled_query = false;
		if (preg_match("/^SQL: \\[[0-9]*?\\] (.*?)\nParams:  0$/", $dump, $matches)) {
			$compiled_query = $matches[1];
		} else {
			preg_match("/\\nSent SQL: \\[([0-9]*?)\\] /", $dump, $matches);
			if (isset($matches[1])) {
				$count = $matches[1];
				preg_match("/\\nSent SQL: \\[$count\\] (.{{$count}})\nParams:/s", $dump, $matches);
				if (isset($matches[1])) {
					$compiled_query = $matches[1];
				}
			}
		}
		return $compiled_query;
	}

}