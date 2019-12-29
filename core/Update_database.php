<?php

namespace core;

use service\Config;

abstract class Update_database {

	protected $module = null;

	private $ver = 0;
	private $ceil = 0;

	private $database;

	public function __construct($database = null) {
		if($database===null){
			$database=Database::get_singleton();
		}
		$this->database=$database;
	}

	protected function q($ver, $query) {

		//Check version:
		$new_version = ++$this->ver;

		//Too new:
		if ($ver > $new_version) {
			Error::quit("Order is violated!", 1);
		}

		//Too old:
		if ($ver <= $this->ceil) {
			Error::quit("Order is violated!", 1);
		}
		$this->ceil = $new_version;

		//Next version:
		if ($ver == $new_version) {
			$statement = $this->database->get_pdo()->query($query);
			if ($statement === false) {
				Error::quit("Database update #$ver failed!\n".$this->database->get_pdo()->errorInfo()[2], 1);
			}
			//Update:
			Config::set_value("DB_VERSION", $ver, $this->module, $this->database);
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
		$this->ver = Config::get_value("DB_VERSION", $this->module, 0, 0, false, $this->database);
		$db_version1 = $this->ver;
		$this->do_update();
		$db_version2 = Config::get_value("DB_VERSION", $this->module, 0, 0, false, $this->database);
		if ($db_version1 == $db_version2) {
			return false;
		}
		return "v$db_version1 &rarr; v$db_version2";
	}

}