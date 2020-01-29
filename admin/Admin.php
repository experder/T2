<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\admin;

use t2\core\Error_;
use t2\core\mod\Core_database;
use t2\core\Page;
use t2\core\service\Config;
use t2\core\service\includes\Includes;
use t2\dev\Install_wizard;

class Admin {

	public static function update_includes(){
		#Includes::$host_includes=true;
		$page = new Page("","");

		//List of all includes:
		Includes::js_jquery341($page);
		Includes::php_parsedown174();

		$result = $page->get_messages_plain();
		if(!$result){
			$result="Already up to date.\n";
		}
		$result = "\n========= Download Includes =========\n$result";
		return $result;
	}

	public static function update_shell(){

		$platform = Config::get_check_platform();
		$project_root = PROJECT_ROOT;

		if ($platform == Config::PLATFORM_WINDOWS) {
			if (!file_exists(PROJECT_ROOT . '/update.cmd')) {
				Install_wizard::init_updater($platform);
			}
			$result = `cd "$project_root" && update.cmd 2>&1`;
			$result = mb_convert_encoding($result, "utf-8", "cp850");

		} else if ($platform == Config::PLATFORM_LINUX) {
			if (!file_exists(PROJECT_ROOT . '/update.sh')) {
				Install_wizard::init_updater($platform);
			}
			$result = `cd '$project_root' && ./update.sh 2>&1`;

		} else {
			//Should not happen because $platform should be checked already
			new Error_("Unknown platform.");
		}

		$result = "\n".htmlentities($result);

		return $result;
	}

	public static function update_dbase(){

		$updater = new Core_database();
		$result = "\n========= Update_database =========\n";
		$result .= $updater->update() ?: "Already up to date.";
		$result .= "\n";

		return $result;

	}

}