<?php

namespace core;

use service\Config;

abstract class Update_database {

	protected $module = null;

	private $ver = 0;
	private $ceil = 0;

	protected function q($ver, $query) {

		//Check version:
		$new_version = ++$this->ver;

		//Too new:
		if ($ver > $new_version) {
			Error::quit("Datenbank-Versionsfolge ist verletzt!", 1);
		}

		//Too old:
		if ($ver <= $this->ceil) {
			Error::quit("Datenbank-Versionsfolge ist verletzt!", 1);
		}
		$this->ceil = $new_version;

		//Next version:
		if ($ver == $new_version) {
			$statement = Database::get_singleton()->get_pdo()->query($query);
			if ($statement === false) {
				Error::quit("Datenbank-Update #$ver fehlgeschlagen!\n".Database::get_singleton()->get_pdo()->errorInfo()[2], 1);
			}
			//Update:
			#Config::set_value("DB_VERSION", $ver, $this->module);
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
		$this->ver = Config::get_value("DB_VERSION", $this->module, 0, 0, false);
		$db_version1 = $this->ver;
		$this->do_update();
		$db_version2 = Config::get_value("DB_VERSION", $this->module, 0, 0, false);
		if ($db_version1 == $db_version2) {
			return false;
		}
		return "v$db_version1 &rarr; v$db_version2";
	}

}