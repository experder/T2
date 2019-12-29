<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


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