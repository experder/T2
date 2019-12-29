<?php

namespace core;

use service\Config;

abstract class Update_database {

	protected $module = null;

	private $ver_next = 0;
	protected $start_ver = 1;

	private $database;

	public function __construct($database = null) {
		if($database===null){
			$database=Database::get_singleton();
		}
		$this->database=$database;
	}

	protected function q($ver, $query) {

		if($ver!=$this->start_ver){
			Error::quit("Order is violated!", 1);
		}
		$this->start_ver++;

		if ($ver == $this->ver_next) {
			$statement = $this->database->get_pdo()->query($query);
			if ($statement === false) {
				$errorInfo = $this->database->get_pdo()->errorInfo();
				Error::quit("Database update #$ver failed!\n".$errorInfo[2], 1);
			}
			//Update:
			Config::set_value("DB_VERSION", $ver, $this->module, null, $this->database);
			$this->ver_next++;
		}

	}

	/**
	 * Sequence of database-updates.
	 * @return bool
	 */
	abstract protected function do_update();

	/**
	 * Runs Update (implemented in child classes) and returns version difference.
	 * @return string|false
	 */
	public function update() {
		$db_version1 = Config::get_value("DB_VERSION", $this->module, 0, 0, false, $this->database);
		$this->ver_next = $db_version1+1;
		$this->do_update();
		$db_version2 = Config::get_value("DB_VERSION", $this->module, 0, -1, false, $this->database);
		if ($db_version1 == $db_version2) {
			return false;
		}
		return "v$db_version1 &rarr; v$db_version2";
	}

}