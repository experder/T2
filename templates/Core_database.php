<?php

/*
require_once ROOT_DIR . "/templates/Core_database.php";
 */

namespace installer;

require_once ROOT_DIR . "/core/Update_database.php";

use core\Update_database;

class Core_database extends Update_database {

	protected $module = "core";
	protected $start_ver = 1;

	/**
	 * @inheritdoc
	 */
	protected function do_update() {

		/**
		 * The table "core_config" is initialized in Install_wizard::init2_db().
		 * @see Install_wizard::init2_db()
		 */

		#$this->q(1,"INSERT INTO `core_config` (`id`, `idstring`, `module`, `userid`, `content`) VALUES (NULL, 'EXTENSION', 'core', NULL, 't2');");

	}

}