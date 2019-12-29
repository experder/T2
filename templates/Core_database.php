<?php

/*
require_once ROOT_DIR."/templates/Core_database.php";
 */

namespace installer;

require_once ROOT_DIR."/core/Update_database.php";

use core\Update_database;

class Core_database extends Update_database {

	protected $module = "core";

	/**
	 * @inheritdoc
	 */
	protected function do_update() {

		/**
		 * The table "core_config" is initializes in Install_wizard::initialize_database().
		 * @see Install_wizard::initialize_database()
		 */

		$this->q(1,"INSERT INTO `core_config` (`id`, `idstring`, `module`, `userid`, `content`) VALUES (NULL, 'EXTENSION', 'core', NULL, 't2');");

	}

}