<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

/*
require_once ROOT_DIR . '/core/Database.php';
 */

namespace t2\core;

require_once ROOT_DIR . '/core/Error_.php';

use t2\core\service\Config;
use t2\core\service\Strings;
use t2\dev\Debug;
use t2\dev\Install_wizard;

#Debug::out();

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

	public $core_prefix;

	private $pdo;

	private $error = false;

	/**
	 * @deprecated TODO: Use Error Class instead (\t2\core\Database::$exception)
	 */
	private $exception = false;

	private $dbname;

	public function __construct($host, $dbname, $user, $password, $stacktrace_depth = 0, $quit_on_error = true, $core_prefix = 'core') {
		$this->core_prefix = $core_prefix;
		$this->dbname = $dbname;
		try {
			$this->pdo = new \PDO("mysql:host=" . $host . ";dbname=" . $dbname, $user, $password);
			$this->pdo->query('SET NAMES utf8');
		} catch (\Exception $e) {
			$this->exception = $e;
			if ($quit_on_error) {
				require_once ROOT_DIR . '/core/Error_.php';
				Error_::from_exception($e);
			}
		}
	}

	/**
	 * @return Error_|false
	 */
	public function getError() {
		return $this->error;
	}

	/**
	 * @deprecated TODO: s.o.
	 */
	public function getException() {
		return $this->exception;
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
				new Error_("ERROR_DB_NOT_INITIALIZED");
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
			new Error_("Database is already initialized!", "ERROR_DB_ALREADY_INITIALIZED", null, 1);
		}

		self::$singleton = new Database($host, $dbname, $user, $password, 1, false, $core_prefix);
		$e = self::$singleton->getException();

		if ($e !== false) {
			require_once ROOT_DIR . '/core/Error_.php';
			if ($e instanceof \PDOException) {
				if ($e->getCode() === 1049/*Unknown database*/) {
					require_once ROOT_DIR . '/dev/Install_wizard.php';
					self::$singleton = Install_wizard::init_db($host, $dbname, $user, $password);
					$e = false;
				} else if ($e->getCode() === 2002/*php_network_getaddresses: getaddrinfo failed*/) {
					new Error_("Database host unknown! Please check config.", Error_::TYPE_HOST_UNKNOWN, null, 1);
				}
			}
			if ($e !== false) {
				Error_::from_exception($e);
			}
		}

		return self::$singleton;
	}

	public static function select_($query, $substitutions = array(), $halt_on_error = true) {
		return self::get_singleton()->select($query, $substitutions, 1, $halt_on_error);
	}

	/**
	 * @param string $query
	 * @param array  $substitutions
	 * @param int    $backtrace_depth
	 * @return array|false
	 */
	public function select($query, $substitutions = array(), $backtrace_depth = 0, $halt_on_error = true) {
		return self::iquery($query, $substitutions, self::RETURN_ASSOC, $backtrace_depth + 1, $halt_on_error);
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
		//TODO(3):subroutines für debug_info und fehler-handling
		if (Config::$DEVMODE) {
			require_once ROOT_DIR . '/dev/Debug.php';
			require_once ROOT_DIR . '/core/Html.php';
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
				. "\n" . (new Html("span", $compiled_query, array("class" => "detail_sqlDump$core_query_class")));
			Debug::$queries[] = $query_html;
		}
		if (!$ok) {
			$eInfo = $statement->errorInfo();
			$errorCode = $eInfo[0];
			$errorInfo = "[$errorCode] " . $eInfo[2];
			$errorType = Error_::TYPE_SQL;
			if (!$eInfo[2]) {
				if ($errorCode === 'HY093'/*Invalid parameter number: parameter was not defined*/) {
					$errorInfo = "Invalid parameter number: parameter was not defined";
				}
			}
			if ($errorCode === "42S02"/*Unknown table*/) {
				$errorType = Error_::TYPE_TABLE_NOT_FOUND;
			}
			ob_flush();
			ob_start();
			$statement->debugDumpParams();
			$debugDump = ob_get_clean();
			$compiled_query = self::get_compiled_query_from_debugDump($debugDump);
			if (!$compiled_query) {
				$compiled_query = ($debugDump ?: $query);
			}

			require_once ROOT_DIR . '/core/Error_.php';
			$this->error = new Error_($errorInfo, $errorType, $compiled_query, $backtrace_depth + 1, $halt_on_error);

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

	/**
	 * @param string $table
	 * @param array  $data_set
	 * @param int    $backtrace_depth
	 * @return false|int
	 */
	public static function insert_assoc_($table, $data_set, $backtrace_depth = 0) {
		return self::get_singleton()->insert_assoc($table, $data_set, $backtrace_depth + 1);
	}

	/**
	 * @param string $tabelle
	 * @param array  $data_set
	 * @param int    $backtrace_depth
	 * @return false|int
	 */
	public function insert_assoc($tabelle, $data_set, $backtrace_depth = 0) {
		$keys_sql = array();
		$values_sql = array();
		foreach ($data_set as $key => $value) {
			$keys_sql[] = "`$key`";
			$values_sql[] = ($value === null ? "NULL" : ("'" . Strings::escape_sql($value) . "'"));
		}
		$keys = implode(", ", $keys_sql);
		$values = implode(", ", $values_sql);
		$query2 = "INSERT INTO $tabelle ($keys) VALUES ($values);";
		return self::insert($query2, null, $backtrace_depth + 1);
	}

	/**
	 * @param string $query
	 * @param array  $substitutions
	 * @param int    $stacktrace_depth
	 * @return int|false
	 */
	public function update($query, $substitutions = array(), $stacktrace_depth = 0) {
		return $this->iquery($query, $substitutions, self::RETURN_ROWCOUNT, $stacktrace_depth + 1);
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