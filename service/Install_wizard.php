<?php

/*
require_once ROOT_DIR.'/service/Install_wizard.php';
 */

namespace service;

require_once ROOT_DIR.'/core/form/Form.php';
require_once ROOT_DIR.'/service/Templates.php';
require_once ROOT_DIR."/templates/Core_database.php";

use core\Database;
use core\Form;
use core\Formfield_password;
use core\Formfield_text;
use core\Message;
use core\Page;

class Install_wizard {

	public static function prompt_dbParams() {
		if(Request::cmd("submit_db_credentials")){
			$target_file = ROOT_DIR.'/config_exclude.php';
			Templates::create_file($target_file, ROOT_DIR.'/config_template.php', array(
				":server_addr"=>Request::value("server_addr", "(please specify)"),
				":tethysdb"=>Request::value("tethysdb", "(please specify)"),
				":username"=>Request::value("username", "(please specify)"),
				":dbpass"=>Request::value("dbpass", "(please specify)"),
			));
			Page::$compiler_messages[]=new Message(Message::TYPE_CONFIRM, "Config file \"$target_file\" has been created.");
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

	/**
	 * @return Page
	 */
	public static function get_pre_page($title) {
		$page = new Page("T2_INSTWIZARD", "Installation: ".$title);
		return $page;
	}

	public static function initialize_database($host, $dbname, $user, $password) {

		try {
			$dbh = new \PDO("mysql:host=" . $host, $user, $password);
			$dbh->exec("CREATE DATABASE `" . $dbname . "`;") or die(print_r($dbh->errorInfo(), true)."2");

		} catch (\PDOException $e) {
			die("DB ERROR: " . $e->getMessage());
		}

		$database = new Database($host, $dbname, $user, $password);

		$database->get_pdo()->exec("CREATE TABLE IF NOT EXISTS `core_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idstring` varchar(40) COLLATE utf8_bin NOT NULL,
  `module` varchar(40) COLLATE utf8_bin NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `content` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;");

		return $database;

		#Page::$compiler_messages[]=new Message(Message::TYPE_CONFIRM, "Database \"$dbname\" initialized.");

	}

}
