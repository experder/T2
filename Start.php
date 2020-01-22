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
use t2\core\Page;
use t2\core\service\Config;
use t2\core\service\User;
use t2\dev\Install_wizard;

class Start {

	const TYPE_UNKNOWN = 0;
	const TYPE_HTML = 1;
	const TYPE_AJAX = 2;
	const TYPE_CLI = 3;

	private static $type = self::TYPE_UNKNOWN;

	private static $dev_start_time = null;

	public static function get_type() {
		return self::$type;
	}

	public static function is_type($type) {
		return self::$type == $type;
	}

	public static function init_constants() {
		if (self::$dev_start_time === null) {
			self::$dev_start_time = microtime(true);
		} else {
			require_once ROOT_DIR . '/core/Error_.php';
			new Error_("Init called twice!", 0, null, 1);
		}
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

	private static function init_config() {
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
			new Error_("Local config file seems to be corrupt. Please check.", "ERROR_CONFIG_CORRUPT", "Config file: " . $config_file);
		}
		#define('DB_CORE_PREFIX', Database::get_singleton()->core_prefix);
	}

	private static function init_database() {
		require_once ROOT_DIR . '/core/service/Config.php';
		Config::load_values(array(
			"EXTENSION",
			"PROJECT_TITLE",
			"SKIN",
			"HTTP_ROOT",
			"SESSION_EXPIRES",
			"MODULES",
			"PLATFORM",
		));
	}

	private static function init_userrights() {
		require_once ROOT_DIR . '/core/service/User.php';
		User::init();
	}

	/**
	 * @param string $PAGEID_
	 * @param string $title
	 * @return Page
	 */
	public static function init($PAGEID_, $title) {
		require_once ROOT_DIR . '/core/Page.php';
		self::$type = self::TYPE_HTML;
		Start::init_config();
		Start::init_database();
		Start::init_userrights();
		$page = Page::init2($PAGEID_, $title);
		return $page;
	}

	public static function init_ajax() {
		self::$type = self::TYPE_AJAX;
		//TODO(2): init rights
	}

	public static function check_type($type) {
		if (!defined('ROOT_DIR')) {
			self::init_constants();
		}
		require_once ROOT_DIR . '/core/Error_.php';
		if (self::$type == self::TYPE_UNKNOWN) {
			Error_::plain_abort_("Unknown type. T2 has not been initialized. \"Start::init\" should be the very first call.", 1);
		}
		if (self::$type != $type) {
			Error_::plain_abort_("Wrong type: " . self::$type . ". Please call proper \"Start::init...\".", 1);
		}
	}

}

Start::init_constants();
