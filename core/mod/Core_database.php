<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\mod;

use t2\api\Update_database;

class Core_database extends Update_database {

	protected $module = null;
	protected $start_ver = 1;

	/**
	 * @inheritdoc
	 */
	protected function do_update() {

		$core_config = DB_CORE_PREFIX.'_config';
		$core_user = DB_CORE_PREFIX.'_user';
		$core_sessions = DB_CORE_PREFIX.'_sessions';
		$core_toc = DB_CORE_PREFIX.'_toc';

		/**
		 * The table "core_config" is initialized in Install_wizard::init3_db_config().
		 * @see \t2\dev\Install_wizard::init_db_config()
		 */

		$this->q(1, "ALTER TABLE `$core_config` MODIFY COLUMN `module`  VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_bin NULL AFTER `idstring`;");

		$this->q(2, "CREATE TABLE `$core_user` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `ref_id` int(11) DEFAULT NULL,
			  `username` varchar(40) COLLATE utf8_bin NOT NULL,
			  `pass_hash` varchar(40) COLLATE utf8_bin NOT NULL,
			  `display_name` varchar(80) COLLATE utf8_bin DEFAULT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `username` (`username`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");

		$this->q(3,"CREATE TABLE `$core_sessions` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `user` int(11) NOT NULL,-- TODO:rename column 'user' in core_sessions
			  `session_id` varchar(20) COLLATE utf8_bin NOT NULL,
			  `expires` int(11) NOT NULL,
			  PRIMARY KEY (`id`),
			  KEY `user` (`user`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;");

		$this->q(4,"ALTER TABLE `$core_sessions`
			  ADD CONSTRAINT `{$core_sessions}_ibfk_1` FOREIGN KEY (`user`) REFERENCES `$core_user` (`id`);");

		$this->q(5,"CREATE TABLE IF NOT EXISTS `$core_toc` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `module` varchar(40) NOT NULL,
			  `idstring` varchar(40) NOT NULL,
			  `title` varchar(200) NOT NULL,
			  `hint` text,
			  `file` text NOT NULL,
			  `icon` text,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

		#$this->q(,"");

	}

}