<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\dev;

use t2\core\Database;
use t2\core\Error;
use t2\core\form\Form;
use t2\core\form\Formfield_header;
use t2\core\form\Formfield_password;
use t2\core\form\Formfield_radio;
use t2\core\form\Formfield_radio_option;
use t2\core\form\Formfield_text;
use t2\core\Html;
use t2\core\Message;
use t2\core\mod\Core_database;
use t2\core\Page;
use t2\core\service\Config;
use t2\core\service\Files;
use t2\core\service\Request;
use t2\core\service\Templates;

class Install_wizard {//TODO(F): Install wizard: Prompt all field in one form

	public static function prompt_http_root() {
		Config::$prompting_http_root = true;

		if (Request::cmd("submit_http_root")) {
			Config::$prompting_http_root = false;
			return Request::value('http_root');
		}

		$proposal = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME);

		$form = new Form("submit_http_root");
		$form->add_field(new Formfield_text("http_root", "HTTP_ROOT", $proposal));

		$html = Html::H1("Server configuration") . "Please enter your servers alias or path to the T2 root." . $form;

		$message = new Message(Message::TYPE_INFO, $html);

		Page::abort("Server configuration - Installer", array($message), "PAGEID_CORE_INSTALLER_PROMPT_HTTPROOT");
		return false;
	}

	public static function prompt_dbParams() {
		if (Request::cmd("submit_db_credentials")) {
			$feedback = self::init_config();
			foreach ($feedback as $message){
				Page::add_message($message);
			}
			return;
		}

		$form = new Form("submit_db_credentials");
		$form->add_field(new Formfield_header(Html::H1("Database connection")));
		$form->add_field(new Formfield_text("server_addr", "Host", "localhost"));
		$form->add_field(new Formfield_text("tethysdb", "DB name", "tethys"));
		$form->add_field(new Formfield_text("username", "Admin account", "root"));
		$form->add_field(new Formfield_password("dbpass", "Admin password", ""));

		$form->add_field(new Formfield_header(Html::H1("Project settings")));
		$form->add_field(new Formfield_text("project_root", "Project root directory", dirname(dirname(__DIR__))));

		$form->add_field(new Formfield_text("t2_subdir", null, "tethys"));
		$form->add_field(new Formfield_text("this_server_name", null, ""));

		$html = $form;

		$message = new Message(Message::TYPE_INFO, $html);

		Page::abort("Basic configuration - Installer", array($message), "PAGEID_CORE_INSTALLER_PROMPT_DBPARAMS");
	}

	private static function prompt_coreUser() {
		if (Request::cmd("submit_db_rootUser")) {
			if (!Request::value('username')) {
				Page::add_message_error_("Username required.");
			} else if (Request::value('password') !== Request::value('password2')) {
				Page::add_message_error_("Passwords doesn't match.");
			} else {
				return;
			}
		}

		$form = new Form("submit_db_rootUser");
		$form->add_field(new Formfield_text("username", "Username", "root"));
		$form->add_field(new Formfield_password("password", "Password", ""));
		$form->add_field(new Formfield_password("password2", "Repeat password", ""));

		$html = Html::H1("Root user") . "Please create first user account." . $form;

		$message = new Message(Message::TYPE_INFO, $html);

		Database::destroy();//TODO(1)-check out! $prompting_coreUser
		if (false) {
			Database::destroy();//This would ba a usecase for Database::destroy.
		}
		Page::abort("Root user - Installer", array($message), "PAGEID_CORE_INSTALLER_PROMPTROOTUSER");
	}

	private static function init_config() {
		$project_root = Request::value("project_root", false);
		if ($project_root === false) {
			new Error("NO_ROOT", "No project root set/found.");
		}
		//Windows:
		$project_root = str_replace('\\', '/', $project_root);

		$messages = array();
		//Three files to create:

		//1.) Redirection to project root:
		$source_file = ROOT_DIR . '/dev/templates/config_redirect.php';
		$target_file = ROOT_DIR . '/config.php';
		Templates::create_file($target_file, $source_file, array(
			":project_root" => $project_root,
		));
		$messages[]= new Message(Message::TYPE_CONFIRM,"Redirection has been created: \"$target_file\".");

		//2.) Project-specific configuration in project's repo:
		$source_file = ROOT_DIR . '/dev/templates/config.php';
		$target_file = $project_root . '/config.php';
		$error = Templates::create_file($target_file, $source_file, array(
			":t2_subdir" => Request::value("t2_subdir", "(please specify)"),
		), false, false);
		if ($error == Templates::ERROR_FILE_EXISTS) {
			$messages[] = new Message(Message::TYPE_CONFIRM,"Using existing config file \"$target_file\".");
		} else {
			$messages[] = new Message(Message::TYPE_CONFIRM,"Config file \"$target_file\" has been created.");
		}

		//3.) Server-specific configuration:
		$source_file = ROOT_DIR . '/dev/templates/config_server_exclude.php';
		$target_file = $project_root . '/config_server_exclude.php';
		$error = Templates::create_file($target_file, $source_file, array(
			":server_addr" => Request::value("server_addr", "(please specify)"),
			":tethysdb" => Request::value("tethysdb", "(please specify)"),
			":username" => Request::value("username", "(please specify)"),
			":dbpass" => Request::value("dbpass", "(please specify)"),
			":project_root" => $project_root,
			"(YOUR_SERVER_HERE)" => Request::value("this_server_name")?:"(YOUR_SERVER_HERE)",
		), false, false);
		if ($error == Templates::ERROR_FILE_EXISTS) {
			$messages[] = new Message(Message::TYPE_CONFIRM,"Using existing server-config file \"$target_file\".");
		} else {
			$messages[] = new Message(Message::TYPE_CONFIRM,"Server-config \"$target_file\" has been created.");
		}

		return $messages;
	}

	public static function api_ini_navi($mod_id, $path) {
		if (Config::$DEVMODE) {
			if (isset($_REQUEST['initialize_module_navi'])) {
				$msg = Tools::create_new_module($mod_id, $mod_id, $path, array("My_Navigation.php"));
				Page::add_message($msg);
			} else {
				Page::add_message_error_(Html::DIV("No navigation set for module '$mod_id'! [<a href='?initialize_module_navi'>Create blank navigation</a>]", "dev"));
			}
		}
	}

	public static function init_db($host, $dbname, $user, $password) {

		try {
			$dbh = new \PDO("mysql:host=" . $host, $user, $password);
			$dbh->exec("CREATE DATABASE `" . $dbname . "`;") or die(print_r($dbh->errorInfo(), true) . "Error65");
		} catch (\PDOException $e) {
			Error::from_exception($e);
		}

		$database = new Database($host, $dbname, $user, $password);

		Page::add_message_confirm_("Database \"$dbname\" created.");

		return $database;
	}

	public static function init_updater($platform_checked) {
		if ($platform_checked == Config::PLATFORM_WINDOWS) {
			$target = PROJECT_ROOT . '/update.cmd';
			Templates::create_file($target, ROOT_DIR . '/dev/templates/update.cmd', array(
				":rel_root" => Files::relative_path(PROJECT_ROOT, ROOT_DIR),
			));
			Page::add_message_confirm_("Updater file \"$target\" created.");
		} else if ($platform_checked == Config::PLATFORM_LINUX) {
			$target = PROJECT_ROOT . '/update.sh';
			Templates::create_file($target, ROOT_DIR . '/dev/templates/update.sh', array(
				//Set Linux line endings:
				"\r\n" => "\n",
				":rel_root" => Files::relative_path(PROJECT_ROOT, ROOT_DIR),
			));
			Page::add_message_confirm_("Updater file \"$target\" created.");

			//Try to set rights:
			$result = `chmod 777 "$target" 2>&1`;

			if ($result) {
				Page::add_message_info_("Please set appropriate rights [<a href='" . Config::get_value('HTTP_ROOT') . "/help/install.md#linux' target='_blank'>HELP</a>]! $result");
			}
		} else {
			//Should not happen because $platform_checked should be checked already
			new Error("Unknown_Platform", "Unknown Platform.");
		}
	}

	public static function init_db_config() {
		$database = Database::get_singleton();
		self::prompt_coreUser();

		$core_config = DB_CORE_PREFIX . '_config';
		$database->get_pdo()->exec("CREATE TABLE IF NOT EXISTS `$core_config` (
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
		$core_user = DB_CORE_PREFIX . '_user';
		$database->insert_assoc2($core_user, array(
			"username" => $root_user,
			"pass_hash" => md5($root_pass),
		));
		Page::add_message_confirm_("User \"$root_user\" created.");

		return $msg;
	}

}
