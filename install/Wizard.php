<?php

/*
require_once ROOT_DIR.'/install/Wizard.php';
 */

namespace inst;

require_once ROOT_DIR.'/core/form/Form.php';
require_once ROOT_DIR.'/install/Template.php';

use core\Form;
use core\Formfield_password;
use core\Formfield_text;
use core\Message;
use core\Page;
use service\Files;
use service\Request;

class Wizard {

	public static function prompt_dbParams() {
		if(Request::cmd("submit_db_credentials")){
			$target_file = ROOT_DIR.'/config_exclude.php';
			Template::create_file($target_file, ROOT_DIR.'/config_template.php', array(
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

}
