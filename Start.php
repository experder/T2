<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2;

use t2\api\Navigation;
use t2\core\Database;
use t2\core\Error;
use t2\core\Error_;
use t2\core\mod\Core_navigation;
use t2\core\Page;
use t2\core\service\Config;
use t2\core\service\User;
use t2\dev\Install_wizard;
use t2\service\Autoloader;

class Start {

	const TYPE_UNKNOWN = 0;
	/**
	 * @deprecated
	 */
	const TYPE_HTML = 1;
	const TYPE_PAGE = 1;//Returns HTML
	const TYPE_AJAX = 2;//Returns JSON
	const TYPE_CLI = 3;//Echoes plain text

	private static $type = self::TYPE_UNKNOWN;

	private static $dev_start_time = null;

	/**
	 * @var Navigation $navigation
	 */
	private static $navigation = null;

	/**
	 * @return Navigation
	 */
	public static function getNavigation($halt_on_error = true) {
		if (self::$navigation === null && $halt_on_error) {
			new Error("NO_NAVI_INIT", "Please initialize navigation!");
		}
		return self::$navigation;
	}

	public static function getNavigation_html($id) {
		$nav = self::getNavigation(false);
		if (!$nav) {
			//Blank navigation:
			$nav = new Navigation(null, null, null);
		}
		return $nav->toHtml($id);
	}

	/**
	 * @param Navigation $navigation
	 */
	public static function setNavigation($navigation) {
		self::$navigation = $navigation;
	}

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
			new Error_("Init called twice!", 0, null, 1);
		}
		if (!defined("ROOT_DIR")) {
			$dir = __DIR__;
			//Windwos:
			$dir = str_replace("\\", "/", $dir);
			define("ROOT_DIR", $dir);
		}
		require_once ROOT_DIR . '/core/service/Autoloader.php';
		Autoloader::register();
	}

	public static function dev_get_start_time() {
		return self::$dev_start_time;
	}

	private static function init_config() {
		$config_file = ROOT_DIR . '/config.php';
		if (!file_exists($config_file)) {
			if (Start::is_type(Start::TYPE_AJAX)) {
				new Error("ERROR_CONFIG_CORRUPT/1", "Local config file seems to be corrupt. Please check.", "Config file: " . $config_file);
			}

			Install_wizard::prompt_dbParams();

		}

		/** @noinspection PhpIncludeInspection */
		require_once $config_file;

		//Make the Test:
		if (Database::get_singleton(false) === false) {
			new Error("ERROR_CONFIG_CORRUPT/2", "Local config file seems to be corrupt. Please check.", "Config file: " . $config_file);
		}

		define('DB_CORE_PREFIX', Database::get_singleton()->core_prefix);

		//TODO(F):  Feature: Install wizard: Configure PROJECT_ROOT
		if (!defined("PROJECT_ROOT")) {
			$propose_project_root = dirname(ROOT_DIR);
			define('PROJECT_ROOT', $propose_project_root);
		}

	}

	private static function init_navigation() {
		if (self::$navigation === null) {
			self::$navigation = Core_navigation::navi_default();
		}
	}

	private static function init_database() {
		/**
		 * @see Core_values::$default_values
		 * @see Admin::get_config_form()
		 */
		Config::load_values(array(
			"MODULES",
			"SESSION_EXPIRES",
			"HTTP_ROOT",
			"EXTENSION",
			"MODULE_ROOT",
			"MODULE_PATH",
			"PROJECT_TITLE",
			"SKIN",
		));
		Config::load_values_api();
	}

	private static function init_userrights() {
		User::init();
	}

	/**
	 * @deprecated
	 */
	public static function init($PAGEID_, $title) {
		return self::init_($PAGEID_);
	}

	/**
	 * @param string $PAGEID_
	 * @return Page
	 */
	public static function init_($PAGEID_) {
		self::$type = self::TYPE_HTML;
		Start::init_config();
		Start::init_database();
		Start::init_userrights();
		Start::init_navigation();
		$page = Page::init2($PAGEID_);
		return $page;
	}

	public static function init_ajax() {
		self::$type = self::TYPE_AJAX;
		Start::init_config();
		//TODO(F): init rights
	}

	public static function check_type($type) {
		if (!defined('ROOT_DIR')) {
			self::init_constants();
		}
		if (self::$type == self::TYPE_UNKNOWN) {
			Error::plain_abort_("Unknown type. T2 has not been initialized. \"Start::init\" should be the very first call.", 1);
		}
		if (self::$type != $type) {
			Error::plain_abort_("Wrong type: " . self::$type . ". Please call proper \"Start::init...\".", 1);
		}
	}

}

Start::init_constants();
