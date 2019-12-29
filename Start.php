<?php

/*
 * <code>
require_once '../../tethys/Start.php';
$page = \core\Page::init("PAGE_ID_MYPAGE", "My page");
$page->add("Hello World!");
$page->send_and_quit();
 * </code>
 */

namespace tethys_root;

use core\Database;
use core\Error;
use service\Config;
use service\Install_wizard;

class Start {

	private static $dev_start_time;

	public static function init_constants(){
		self::$dev_start_time=microtime(true);
		if (!defined("ROOT_DIR")) {
			$dir = __DIR__;
			//Windwos:
			$dir = str_replace("\\","/",$dir);
			define("ROOT_DIR", $dir);
		}
		if (!defined("DEVMODE")) {
			define("DEVMODE",false);
		}
	}

	public static function get_dev_stats(){
		$end_time = microtime(true);
		return round($end_time-self::$dev_start_time,3)." Seconds";
	}

	public static function init_dependencies(){
		require_once ROOT_DIR.'/core/Page.php';
		require_once ROOT_DIR.'/core/Error.php';
		require_once ROOT_DIR.'/core/Database.php';
		require_once ROOT_DIR.'/core/Message.php';
		require_once ROOT_DIR.'/service/Config.php';
	}

	public static function init_config(){
		$config_file = ROOT_DIR.'/config_exclude.php';
		if (!file_exists($config_file)){
			require_once ROOT_DIR.'/service/Install_wizard.php';
			Install_wizard::prompt_dbParams();
		}
		/** @noinspection PhpIncludeInspection */
		require_once $config_file;
		//Make the Test:
		if (Database::get_singleton(false)===false){
			Error::quit("Local config file (\"$config_file\") not found or corrupt. Please check.");
		}
	}

	public static function init_database(){
		Config::load_values(array(
			"EXTENSION",
		));
		define("EXT", Config::get_value("EXTENSION", 'core', 0, "php"));
	}

}

Start::init_constants();
Start::init_dependencies();
Start::init_config();
Start::init_database();
