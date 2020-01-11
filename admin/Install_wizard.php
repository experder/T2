<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


/*
require_once ROOT_DIR . '/admin/Install_wizard.php';
 */

namespace admin;//TODO: move all namespaces to t2

require_once ROOT_DIR . '/core/form/Form.php';
require_once ROOT_DIR . '/service/Templates.php';
require_once ROOT_DIR . "/admin/Core_database.php";

use t2\core\Database;
use t2\core\Error;
use t2\core\Error_warn;
use t2\core\Form;
use t2\core\Formfield_password;
use t2\core\Formfield_text;
use t2\core\Message;
use t2\core\Page;
use service\Config;
use service\Request;
use service\Templates;

class Install_wizard {//TODO: Make an installer class that must be called explicitly (not via index)

	public static function prompt_dbParams() {
		if (Request::cmd("submit_db_credentials")) {
			Page::$compiler_messages[] = self::init_config();
			Install_wizard::dev_step_by_step();
			return;
		}

		$form = new Form("submit_db_credentials");
		$form->add_field(new Formfield_text("server_addr", "Host", "localhost"));
		$form->add_field(new Formfield_text("tethysdb", "DB name", "tethys"));
		$form->add_field(new Formfield_text("username", "Admin account", "root"));
		$form->add_field(new Formfield_password("dbpass", "Admin password", ""));

		$message = new Message(Message::TYPE_INFO, $form);
		Page::$compiler_messages[] = $message;

		self::installer_exit("Database connection");
	}

	public static function init_config() {
		self::check_if_index();
		$target_file = ROOT_DIR . '/config_exclude.php';
		Templates::create_file($target_file, ROOT_DIR . '/config_template.php', array(
			":server_addr" => Request::value("server_addr", "(please specify)"),
			":tethysdb" => Request::value("tethysdb", "(please specify)"),
			":username" => Request::value("username", "(please specify)"),
			":dbpass" => Request::value("dbpass", "(please specify)"),
		));
		return new Message(Message::TYPE_CONFIRM, "Config file \"$target_file\" has been created.");
	}

	public static function init_db($host, $dbname, $user, $password, $backtrace_depth = 0) {
		self::check_if_index();

		try {
			$dbh = new \PDO("mysql:host=" . $host, $user, $password);
			$dbh->exec("CREATE DATABASE `" . $dbname . "`;") or die(print_r($dbh->errorInfo(), true) . "Error65");

		} catch (\PDOException $e) {
			Error::from_exception($e);
			#Error::quit($e->getMessage(), $backtrace_depth + 1);
		}

		$database = new Database($host, $dbname, $user, $password);

		Page::$compiler_messages[] = new Message(Message::TYPE_CONFIRM, "Database \"$dbname\" created.");
		self::dev_step_by_step();

		return $database;
	}

	public static function init_db_config() {
		self::check_if_index();
		$database=Database::get_singleton();

		#$core_config = DB_CORE_PREFIX.'_config';
		$database->get_pdo()->exec("CREATE TABLE IF NOT EXISTS `core_config` (
			  `id` INT(11) NOT NULL AUTO_INCREMENT,
			  `idstring` VARCHAR(40) COLLATE utf8_bin NOT NULL,
			  `module` VARCHAR(40) COLLATE utf8_bin NOT NULL,
			  `userid` INT(11) DEFAULT NULL,
			  `content` TEXT COLLATE utf8_bin NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");

		$updater = new Core_database($database);
		$msg = $updater->update();

		return $msg;
	}

//	private static function init4_config_params() {
//		foreach (array(
//			'HTTP_ROOT',
//				 ) as $param){
//			if(($val=Request::value($param, false))!==false){ Config::set_value($param, $val); }
//		}
//		return new Message(Message::TYPE_CONFIRM, "Config params set.");
//	}

	public static function check_if_index() {
		if(!isset($_SERVER['SCRIPT_FILENAME'])||$_SERVER['SCRIPT_FILENAME']!=ROOT_DIR.'/index.php'){
			Error::quit("Please run index.php in root directory to complete installation.");
		}
	}

	public static function init_set_http_root() {
		self::check_if_index();
		$http_root = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);
		Config::set_value('HTTP_ROOT', $http_root);
		Page::$compiler_messages[]=new Message(Message::TYPE_CONFIRM, "HTTP_ROOT set to: $http_root");
		self::dev_step_by_step();
	}

	public static function dev_step_by_step() {
		#self::installer_exit("STEP-BY-STEP");
	}

	/**
	 * @param string       $title
	 * @param Message[] $messages
	 * @param null|string   $body
	 * @param string $id
	 */
	public static function installer_exit($title, $messages=null, $body=null, $id="PAGEID_CORE_INSTALLER") {
		self::check_if_index();
		Error_warn::abort("$title - Installer", $messages, $body, $id);
		exit;
	}

//	public static function prompt_config() {
//		if (Request::cmd("submit_core_config")) {
//			Page::$compiler_messages[] = self::init4_config_params();
//			return;
//		}
//
//		$page = self::get_pre_page("Core config");
//
//		$HTTP_ROOT_proposal = Config::get_value_core("HTTP_ROOT", pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME));
//
//		$form = new Form("submit_core_config");
//		$form->add_field($ff=new Formfield_text("HTTP_ROOT", "HTTP_ROOT", $HTTP_ROOT_proposal));
//
//		$page->add_message(Message::TYPE_INFO, $form);
//
//		$page->send_and_quit();
//
//	}

}
