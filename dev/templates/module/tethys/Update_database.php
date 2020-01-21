<?php
namespace t2\modules\core_template\api;//(:moduleIdLc)
/**TPLDOCSTART
GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
GPL
 * This template is used to create a new module.
 * @see \t2\dev\Tools::prompt_new_module()
TPLDOCEND*/

require_once ROOT_DIR . "/api/Update_database.php";

use t2\api\Update_database;

class Core_database extends Update_database {

	protected $module = "(:MODULE_ID_LC)";
	protected $start_ver = 1;

	/**
	 * @inheritdoc
	 */
	protected function do_update() {

//		$this->q(1, "CREATE TABLE `(:MODULE_ID_LC)_(:MODULE_ID_LC)` (
//			  `id` int(11) NOT NULL AUTO_INCREMENT,
//			  `uid` int(11) NOT NULL,
//			  `property1` text NOT NULL,
//			  PRIMARY KEY (`id`),
//			  KEY `uid` (`uid`)
//			) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

		#$this->q(2, "ALTER TABLE `(:MODULE_ID_LC)_(:MODULE_ID_LC)` ADD CONSTRAINT `(:MODULE_ID_LC)_(:MODULE_ID_LC)_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `core_user` (`id`);");

		#$this->q(, "");

	}

}