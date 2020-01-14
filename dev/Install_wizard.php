<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


/*
require_once ROOT_DIR . '/dev/Install_wizard.php';
 */

namespace admin;//TODO: move all namespaces to t2

require_once ROOT_DIR . '/core/Message.php';
require_once ROOT_DIR . "/core/api/Core_database.php";
require_once ROOT_DIR . '/core/service/Request.php';
require_once ROOT_DIR . '/core/form/Form.php';
require_once ROOT_DIR . '/core/service/Templates.php';

use service\Config;
use service\Html;
use service\Request;
use service\Templates;
use t2\core\Database;
use t2\core\Error_;
use t2\core\Form;
use t2\core\Formfield_password;
use t2\core\Formfield_text;
use t2\core\Message;
use t2\core\Page;

class Install_wizard {

	public static function prompt_http_root() {
		if (Request::cmd("submit_http_root")) {
			$http_root = Request::value('http_root');
			if(!$http_root){
				#Page::$compiler_messages[] = new Message(Message::TYPE_ERROR, "Please");
			}
			return $http_root;
		}

		$proposal = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);

		$form = new Form("submit_http_root");
		$form->add_field(new Formfield_text("http_root", "HTTP_ROOT", $proposal));

		$html=Html::H1("Server configuration")."Please enter your servers alias or path to the T2 root.".$form;

		$message = new Message(Message::TYPE_INFO, $html);

		Error_::abort("Server configuration - Installer", array($message), null, "PAGEID_CORE_INSTALLER_PROMPTHTTPROOT");
	}

	public static function prompt_dbParams() {
		if (Request::cmd("submit_db_credentials")) {
			Page::$compiler_messages[] = self::init_config();
			return;
		}

		$form = new Form("submit_db_credentials");
		$form->add_field(new Formfield_text("server_addr", "Host", "localhost"));
		$form->add_field(new Formfield_text("tethysdb", "DB name", "tethys"));
		$form->add_field(new Formfield_text("username", "Admin account", "root"));
		$form->add_field(new Formfield_password("dbpass", "Admin password", ""));

		$html=Html::H1("Database connection")."Please enter sql connection parameters".$form;

		$message = new Message(Message::TYPE_INFO, $html);

		Error_::abort("Database connection - Installer", array($message), null, "PAGEID_CORE_INSTALLER_PROMPTDBPARAMS");
	}

	private static function prompt_coreUser() {
		if (Request::cmd("submit_db_rootUser")) {
			if (!Request::value('username')){
				Page::$compiler_messages[] = new Message(Message::TYPE_ERROR, "Username required.");
			}else if (Request::value('password')!==Request::value('password2')){
				Page::$compiler_messages[] = new Message(Message::TYPE_ERROR, "Passwords doesn't match.");
			}else{
				return;
			}
		}

		$form = new Form("submit_db_rootUser");
		$form->add_field(new Formfield_text("username", "Username", "root"));
		$form->add_field(new Formfield_password("password", "Password", ""));
		$form->add_field(new Formfield_password("password2", "Repeat password", ""));

		$html=Html::H1("Root user")."Please create first user account.".$form;

		$message = new Message(Message::TYPE_INFO, $html);

		Database::destroy();
		Error_::abort("Root user - Installer", array($message), null, "PAGEID_CORE_INSTALLER_PROMPTROOTUSER");
	}

	private static function init_config() {
		$target_file = ROOT_DIR . '/config_exclude.php';
		Templates::create_file($target_file, ROOT_DIR . '/config_template.php', array(
			":server_addr" => Request::value("server_addr", "(please specify)"),
			":tethysdb" => Request::value("tethysdb", "(please specify)"),
			":username" => Request::value("username", "(please specify)"),
			":dbpass" => Request::value("dbpass", "(please specify)"),
		));
		return new Message(Message::TYPE_CONFIRM, "Config file \"$target_file\" has been created.");
	}

	public static function init_db($host, $dbname, $user, $password) {

		try {
			$dbh = new \PDO("mysql:host=" . $host, $user, $password);
			$dbh->exec("CREATE DATABASE `" . $dbname . "`;") or die(print_r($dbh->errorInfo(), true) . "Error65");

		} catch (\PDOException $e) {
			Error_::from_exception($e);
		}

		$database = new Database($host, $dbname, $user, $password);

		Page::$compiler_messages[] = new Message(Message::TYPE_CONFIRM, "Database \"$dbname\" created.");
		#self::dev_step_by_step();

		return $database;
	}

	public static function init_db_config() {
		$database=Database::get_singleton();
		self::prompt_coreUser();

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

		$root_user = Request::value('username', 'root');
		$root_pass = Request::value('password', '');
		$database->insert_assoc('core_user', array(
			"username"=>$root_user,
			"pass_hash"=>md5($root_pass),
		));

		return $msg;
	}

}
