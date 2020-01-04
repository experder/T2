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

namespace admin;

require_once ROOT_DIR . '/core/form/Form.php';
require_once ROOT_DIR . '/service/Templates.php';
require_once ROOT_DIR . "/templates/Core_database.php";

use core\Database;
use core\Error;
use core\Form;
use core\Formfield_password;
use core\Formfield_text;
use core\Message;
use core\Page;
use installer\Core_database;
use service\Config;
use service\Request;
use service\Templates;

class Install_wizard {

	public static function prompt_dbParams() {
		if (Request::cmd("submit_db_credentials")) {
			Page::$compiler_messages[] = self::init1_config();
			return;
		}

		$page = self::get_pre_page("Database connection");

		$form = new Form("submit_db_credentials");
		$form->add_field(new Formfield_text("server_addr", "Host", "localhost"));
		$form->add_field(new Formfield_text("tethysdb", "DB name", "tethys"));
		$form->add_field(new Formfield_text("username", "Admin account", "root"));
		$form->add_field(new Formfield_password("dbpass", "Admin password", ""));

		$page->add_message(Message::TYPE_INFO, $form);

		$page->send_and_quit();
	}

	public static function init1_config() {
		$target_file = ROOT_DIR . '/config_exclude.php';
		Templates::create_file($target_file, ROOT_DIR . '/config_template.php', array(
			":server_addr" => Request::value("server_addr", "(please specify)"),
			":tethysdb" => Request::value("tethysdb", "(please specify)"),
			":username" => Request::value("username", "(please specify)"),
			":dbpass" => Request::value("dbpass", "(please specify)"),
		));
		return new Message(Message::TYPE_CONFIRM, "Config file \"$target_file\" has been created.");
	}

	/**
	 * @param string $title
	 * @return Page
	 */
	public static function get_pre_page($title) {
		$page = new Page("T2_INSTWIZARD", "Installation: " . $title);
		return $page;
	}

	public static function init2_db($host, $dbname, $user, $password, $backtrace_depth = 0) {

		try {
			$dbh = new \PDO("mysql:host=" . $host, $user, $password);
			$dbh->exec("CREATE DATABASE `" . $dbname . "`;") or die(print_r($dbh->errorInfo(), true) . "Error65");

		} catch (\PDOException $e) {
			Error::quit($e->getMessage(), $backtrace_depth + 1);
		}

		$database = new Database($host, $dbname, $user, $password);

		Page::$compiler_messages[] = new Message(Message::TYPE_CONFIRM, "Database \"$dbname\" created.");

		return $database;
	}

	public static function init3_db_config() {
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

	public static function init4_config_params() {
		$HTTP_ROOT = Request::value('HTTP_ROOT', false);
		if($HTTP_ROOT!==false){
			Config::set_value('HTTP_ROOT', $HTTP_ROOT);
		}
		return new Message(Message::TYPE_CONFIRM, "Config params set.");
	}

	public static function prompt_config() {
		if (Request::cmd("submit_core_config")) {
			Page::$compiler_messages[] = self::init4_config_params();
			return;
		}

		$page = self::get_pre_page("Core config");

		$HTTP_ROOT_proposal = Config::get_value_core("HTTP_ROOT", pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME));

		$form = new Form("submit_core_config");
		$form->add_field($ff=new Formfield_text("HTTP_ROOT", "HTTP_ROOT", $HTTP_ROOT_proposal));

		$page->add_message(Message::TYPE_INFO, $form);

		$page->send_and_quit();

	}

}
