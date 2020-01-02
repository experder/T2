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

	protected $module = null;
	protected $start_ver = 1;

	/**
	 * @inheritdoc
	 */
	protected function do_update() {

		/**
		 * The table "core_config" is initialized in Install_wizard::init3_db_config().
		 * @see \admin\Install_wizard::init3_db_config()
		 */

		#$core_config = DB_CORE_PREFIX.'_config';
		$this->q(1, "ALTER TABLE `core_config` MODIFY COLUMN `module`  VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_bin NULL AFTER `idstring`;");

		#$core_user = DB_CORE_PREFIX.'_user';
		$this->q(2, "CREATE TABLE `core_user` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `ref_id` int(11) DEFAULT NULL,
			  `username` varchar(40) COLLATE utf8_bin NOT NULL,
			  `pass_hash` varchar(40) COLLATE utf8_bin NOT NULL,
			  `display_name` varchar(80) COLLATE utf8_bin DEFAULT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `username` (`username`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");

		$this->q(3,"CREATE TABLE `core_sessions` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `user` int(11) NOT NULL,
			  `session_id` varchar(20) COLLATE utf8_bin NOT NULL,
			  `expires` int(11) NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `user` (`user`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;");

		$this->q(4,"ALTER TABLE `core_sessions`
			  ADD CONSTRAINT `core_sessions_ibfk_1` FOREIGN KEY (`user`) REFERENCES `core_user` (`id`);");

		#$this->q(,"");

	}

}