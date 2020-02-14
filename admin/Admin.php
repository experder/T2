<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\admin;

use t2\api\Service;
use t2\api\Update_database;
use t2\core\Error;
use t2\core\mod\Core_database;
use t2\core\Page;
use t2\core\service\Config;
use t2\core\service\Includes;
use t2\dev\Install_wizard;

class Admin {

	public static function update_includes(){
		$page = new Page("","");

		Includes::load_all_available($page);

		$result = $page->get_messages_plain();
		if(!$result){
			$result="Already up to date.\n";
		}
		$result = "\n========= Download Includes =========\n$result";
		return $result;
	}

	public static function get_update_script_name(){
		$shellname = "?";
		$platform = Config::get_check_platform();
		if ($platform == Config::PLATFORM_WINDOWS) {
			$shellname = 'update.cmd';
		} else if ($platform == Config::PLATFORM_LINUX) {
			$shellname = 'update.sh';
		} else {
			//Should not happen because $platform should be checked already
			new Error("Unknown_platform","Unknown platform.");
		}
		return $shellname;
	}

	public static function update_shell(){

		$platform = Config::get_check_platform();
		$project_root = PROJECT_ROOT;

		#$shellname = Admin::get_update_script_name();

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
			new Error("Unknown_platform","Unknown platform.");
			$result="";
		}

		$result = "\n".htmlentities($result);

		return $result;
	}

	public static function update_dbase(){

		$results = array();

		$updater = new Core_database();
		if(($ver=$updater->update())!==false){
			$results[]="core: ".$ver;
		}

		$modules = Config::get_modules_ids();

		foreach ($modules as $module){
			$update = Service::get_api_class($module, "Update_database", $error, $return);
			if(!($update instanceof Update_database)){
				if($error==Service::API_ERROR_FILE_NOT_FOUND){
					if(Config::$DEVMODE){
						$results[]="NOTE! $module has no updater!";
					}
				}else{
					new Error("API_ERROR_INT", "Unknown error in internal api.");
				}
			}else{
				if(($ver=$update->update())!==false){
					$results[]=$module.': '.$ver;
				}
			}
		}

		$result = "\n========= Update_database =========\n";
		$result .= $results?implode("\n",$results): "All up to date.";
		$result .= "\n";

		return $result;

	}

}