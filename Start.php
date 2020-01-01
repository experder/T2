<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


/*
 * <code>
require_once '../../tethys/Start.php';
$page = \core\Page::init("PAGEID_MYMODULE_MYPAGE", "My page");
$page->add("Hello World!");
$page->send_and_quit();
 * </code>
 */

namespace tethys_root;

use core\Database;
use core\Error;
use service\Config;
use admin\Install_wizard;

class Start {

	private static $dev_start_time;

	public static function init_constants() {
		self::$dev_start_time = microtime(true);
		if (!defined("ROOT_DIR")) {
			$dir = __DIR__;
			//Windwos:
			$dir = str_replace("\\", "/", $dir);
			define("ROOT_DIR", $dir);
		}
	}

	public static function get_dev_stats() {
		$end_time = microtime(true);
		return round($end_time - self::$dev_start_time, 3) . " Seconds";
	}

	public static function init_dependencies() {
		require_once ROOT_DIR . '/core/Page.php';
		require_once ROOT_DIR . '/core/Error.php';
		require_once ROOT_DIR . '/core/Database.php';
		require_once ROOT_DIR . '/core/Message.php';
		require_once ROOT_DIR . '/service/Config.php';
	}

	public static function init_config() {
		$config_file = ROOT_DIR . '/config_exclude.php';
		if (!file_exists($config_file)) {
			require_once ROOT_DIR . '/admin/Install_wizard.php';
			Install_wizard::prompt_dbParams();
		}
		/** @noinspection PhpIncludeInspection */
		require_once $config_file;
		//Make the Test:
		if (Database::get_singleton(false) === false) {
			Error::quit("Local config file (\"$config_file\") not found or corrupt. Please check.");
		}
	}

	public static function init_database() {
		Config::load_values(array(
			"EXTENSION",
			"PROJEKT_TITLE",
			"STYLE",
			"HTTP_ROOT",
		));
		define("EXT", Config::get_value_core("EXTENSION", "php"));

		define("HTTP_ROOT", Config::get_value_core("HTTP_ROOT", ""));

		#Config::$PROJECT_TITLE = Config::get_value_core("PROJEKT_TITLE", 'T2');
	}

}

Start::init_constants();
Start::init_dependencies();
Start::init_config();
Start::init_database();
