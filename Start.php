<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


namespace t2;

use t2\core\Database;
use t2\core\Error_;
use t2\core\Html;
use t2\core\Page;
use service\Config;
use admin\Install_wizard;
use service\User;

class Start {

	private static $dev_start_time;
	/**
	 * @deprecated 
	 */
	public static $dev_queries = array();
	public static $started = false;

	/**
	 * @return bool
	 */
	public static function isStarted() {
		return self::$started;
	}

	public static function init_constants() {
		self::$dev_start_time = microtime(true);
		if (!defined("ROOT_DIR")) {
			$dir = __DIR__;
			//Windwos:
			$dir = str_replace("\\", "/", $dir);
			define("ROOT_DIR", $dir);
		}
	}

	public static function dev_get_start_time() {
		return self::$dev_start_time;
	}

	/**
	 * @deprecated
	 */
	public static function get_dev_stats() {
		$end_time = microtime(true);
		return new Html("div", "<b>".round($end_time - self::$dev_start_time, 3) . "</b> Seconds", array("class"=>"dev_stats_runtime abutton"));
	}

	public static function init_dependencies() {
//		require_once ROOT_DIR . '/core/Page.php';
//		require_once ROOT_DIR . '/core/Error.php';
//		require_once ROOT_DIR . '/core/Error_warn.php';
//		require_once ROOT_DIR . '/core/Error_fatal.php';
//		require_once ROOT_DIR . '/core/Message.php';
//		require_once ROOT_DIR . '/core/service/User.php';
	}

	public static function init_config() {
		require_once ROOT_DIR . '/core/Database.php';
		require_once ROOT_DIR . '/core/service/Config.php';
		$config_file = ROOT_DIR . '/config_exclude.php';
		if (!file_exists($config_file)) {
			require_once ROOT_DIR . '/dev/Install_wizard.php';
			Install_wizard::prompt_dbParams();
		}
		/** @noinspection PhpIncludeInspection */
		require_once $config_file;
		//Make the Test:
		if (Database::get_singleton(false) === false) {
			new Error_("Local config file seems to be corrupt. Please check.", "ERROR_CONFIG_CORRUPT", "Config file: ".$config_file);
		}
		#define('DB_CORE_PREFIX', Database::get_singleton()->core_prefix);
	}

	public static function init_database() {
		require_once ROOT_DIR . '/core/service/Config.php';
		Config::load_values(array(
			"EXTENSION",
			"PROJECT_TITLE",
			"STYLE",
			"HTTP_ROOT",
			"SESSION_EXPIRES",
			"MODULES",
		),null,null,true);
		define("EXT", Config::get_value_core("EXTENSION"));

		#Config::$PROJECT_TITLE = Config::get_value_core("PROJECT_TITLE", 'T2');
	}

	public static function init_userrights() {
		require_once ROOT_DIR . '/core/service/User.php';//Start.php:99
		User::init();
	}

	/**
	 * @param string $PAGEID_
	 * @param string $title
	 * @return Page
	 */
	public static function init($PAGEID_, $title) {
		require_once ROOT_DIR . '/core/Page.php';//Start.php:113
		Start::init_dependencies();
		Start::init_config();
		Start::init_database();
		Start::init_userrights();
		$page = Page::init2($PAGEID_, $title);
		self::$started = true;
		return $page;
	}

}

Start::init_constants();
