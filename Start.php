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


use inst\Wizard;

class Start {

	public static function init_constants(){
		if (!defined("ROOT_DIR")) {
			define("ROOT_DIR", __DIR__);
		}
		if (!defined("DEVMODE")) {
			define("DEVMODE",false);
		}
	}

	public static function init_dependencies(){
		require_once ROOT_DIR.'/core/Page.php';
		require_once ROOT_DIR.'/core/Error.php';
		require_once ROOT_DIR.'/core/Database.php';
		require_once ROOT_DIR.'/core/Message.php';
	}

	public static function init_config(){
		$config_file = ROOT_DIR.'/config_exclude.php';
		if (!file_exists($config_file)){
			require_once ROOT_DIR.'/install/Wizard.php';
			Wizard::prompt_dbParams();
		}
		/** @noinspection PhpIncludeInspection */
		require_once $config_file;
	}

	public static function init_database(){

	}

}

Start::init_constants();
Start::init_dependencies();
Start::init_config();
Start::init_database();
