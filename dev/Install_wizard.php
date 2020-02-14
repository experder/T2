<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\dev;

use t2\core\Database;
use t2\core\Error_;
use t2\core\form\Form;
use t2\core\form\Formfield_header;
use t2\core\form\Formfield_password;
use t2\core\form\Formfield_radio;
use t2\core\form\Formfield_radio_option;
use t2\core\form\Formfield_text;
use t2\core\Message;
use t2\core\mod\Core_database;
use t2\core\Page;
use t2\core\service\Config;
use t2\core\service\Files;
use t2\core\Html;
use t2\core\service\Request;
use t2\core\service\Templates;

class Install_wizard {//TODO(3): Install wizard: Prompt all field in one form

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

		Page::abort("Server configuration - Installer", array($message), null, "PAGEID_CORE_INSTALLER_PROMPT_HTTPROOT");
		return false;
	}

	public static function prompt_dbParams() {
		if (Request::cmd("submit_db_credentials")) {
			Page::$compiler_messages[] = self::init_config();
			return;
		}

		$form = new Form("submit_db_credentials");
		$form->add_field(new Formfield_header(Html::H1("Database connection")));
		#$form->add_field(new Formfield_header("Please enter sql connection parameters"));
		$form->add_field(new Formfield_text("server_addr", "Host", "localhost"));
		$form->add_field(new Formfield_text("tethysdb", "DB name", "tethys"));
		$form->add_field(new Formfield_text("username", "Admin account", "root"));
		$form->add_field(new Formfield_password("dbpass", "Admin password", ""));

		$form->add_field(new Formfield_header(Html::H1("Project settings")));
		$form->add_field(new Formfield_text("project_root", "Project root directory", dirname(dirname(__DIR__))));
		$form->add_field(new Formfield_radio("config_redirect",array(
			new Formfield_radio_option("project", "Store config in project root"),
			new Formfield_radio_option("t2", "Store config in submodule t2"),
		),"", "project"));

		$html = $form;

		$message = new Message(Message::TYPE_INFO, $html);

		Page::abort("Basic configuration - Installer", array($message), null, "PAGEID_CORE_INSTALLER_PROMPT_DBPARAMS");
	}

	private static function prompt_coreUser() {
		if (Request::cmd("submit_db_rootUser")) {
			if (!Request::value('username')) {
				Page::$compiler_messages[] = new Message(Message::TYPE_ERROR, "Username required.");
			} else if (Request::value('password') !== Request::value('password2')) {
				Page::$compiler_messages[] = new Message(Message::TYPE_ERROR, "Passwords doesn't match.");
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

		Database::destroy();//TODO(3)-$prompting_coreUser
		if (false) {
			Database::destroy();//This would ba a usecase for Database::destroy.
		}
		Page::abort("Root user - Installer", array($message), null, "PAGEID_CORE_INSTALLER_PROMPTROOTUSER");
	}

	private static function init_config() {
		$target_file = ROOT_DIR . '/config.php';
		$store_locally = Request::value('config_redirect')=='t2';
		$project_root = Request::value("project_root", false);
		if($project_root===false){
			new Error_(true);
		}
		//Windows:
		$project_root = str_replace('\\','/',$project_root);
		$message = "";
		if(!$store_locally){
			Templates::create_file($target_file, ROOT_DIR . '/dev/templates/config_redirect.php', array(
				":project_root" => $project_root,
			));
			$message.="<br>Redirection has been created: \"$target_file\".";
			$target_file = $project_root . '/config.php';
		}
		$error = Templates::create_file($target_file, ROOT_DIR . '/dev/templates/config.php', array(
			":server_addr" => Request::value("server_addr", "(please specify)"),
			":tethysdb" => Request::value("tethysdb", "(please specify)"),
			":username" => Request::value("username", "(please specify)"),
			":dbpass" => Request::value("dbpass", "(please specify)"),
			":project_root" => $project_root,
		),false,false);
		if($error==-1/*File already exists*/){
			$message = "Using existing config file \"$target_file\".".$message;
		}else{
			$message = "Config file \"$target_file\" has been created.".$message;
		}
		return new Message(Message::TYPE_CONFIRM, $message);
	}

	public static function api_ini_navi($mod_id, $path){
		if(Config::$DEVMODE){
			if(isset($_REQUEST['initialize_module_navi'])){
				$msg = Tools::create_new_module($mod_id, $mod_id, $path, array("My_Navigation.php"));
				Page::$compiler_messages[] = $msg;
			}else{
				Page::get_singleton()->add_message_error(Html::DIV("No navigation set for module '$mod_id'! [<a href='?initialize_module_navi'>Create blank navigation</a>]","dev"));
			}
		}
	}

	/**
	 * @deprecated TODO: NOT IN USE
	 */
	public static function api_ini_updater($mod_id, $path){
		if(Config::$DEVMODE){
			if(isset($_REQUEST['initialize_ini_updater'])){
				$msg = Tools::create_new_module($mod_id, $mod_id, $path, array("Update_database.php"));
				Page::$compiler_messages[] = $msg;
			}else{
				Page::get_singleton()->add_message_error(Html::DIV("No updater set for module '$mod_id'! [<a href='?initialize_ini_updater'>Create blank updater</a>]","dev"));
			}
		}
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

		return $database;
	}

	public static function init_updater($platform_checked) {
		if ($platform_checked == Config::PLATFORM_WINDOWS) {
			$target = PROJECT_ROOT . '/update.cmd';
			Templates::create_file($target, ROOT_DIR . '/dev/templates/update.cmd', array(
				":rel_root"=>Files::relative_path(PROJECT_ROOT, ROOT_DIR),
			));
			Page::$compiler_messages[] = new Message(Message::TYPE_CONFIRM, "Updater file \"$target\" created.");
		} else if ($platform_checked == Config::PLATFORM_LINUX) {
			$target = PROJECT_ROOT . '/update.sh';
			Templates::create_file($target, ROOT_DIR . '/dev/templates/update.sh', array(
				//Set Linux line endings:
				"\r\n"=>"\n",
				":rel_root"=>Files::relative_path(PROJECT_ROOT, ROOT_DIR),
			));
			Page::$compiler_messages[] = new Message(Message::TYPE_CONFIRM, "Updater file \"$target\" created.");

			//Try to set rights:
			$result = `chmod 777 "$target" 2>&1`;

			if($result) {
				Page::$compiler_messages[] = new Message(Message::TYPE_INFO, "Please set appropriate rights [<a href='https://github.com/experder/T2/blob/master/help/install.md#linux' target='_blank'>HELP</a>]! $result");
			}
		} else {
			//Should not happen because $platform_checked should be checked already
			new Error_("Unknown Platform.");
		}
	}

	public static function init_db_config() {
		$database = Database::get_singleton();
		self::prompt_coreUser();

		$core_config = DB_CORE_PREFIX.'_config';
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
		$core_user = DB_CORE_PREFIX.'_user';
		$database->insert_assoc($core_user, array(
			"username" => $root_user,
			"pass_hash" => md5($root_pass),
		));
		Page::$compiler_messages[] = new Message(Message::TYPE_CONFIRM, "User \"$root_user\" created.");

		return $msg;
	}

}
