<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/
/*
require_once ROOT_DIR . '/dev/Tools.php';
 */

namespace t2\dev;


use service\Html;
use service\Request;
use t2\core\Form;
use t2\core\Formfield_text;
use t2\core\Message;
use t2\core\Page;

class Tools {

	const empty_path = '(Leave empty for default)';//TODO(3):i18n

	public static function prompt_new_module(Page $page){

		if(Request::cmd("cmd_newmodule")){
			$msg = self::create_new_module(
				Request::value('module_name'),
				Request::value('module_id'),
				Request::value('path')
			);
			Page::$compiler_messages[] = $msg;
			if($msg->getType()==Message::TYPE_CONFIRM){
				$page->send_and_quit();
			}
		}

		$form = new Form("cmd_newmodule");

		$form->add_field(new Formfield_text("module_name", "Module name", "My module"));
		$form->add_field(new Formfield_text("module_id", "Module ID", "mymod"));
		$form->add_field(new Formfield_text("path", "Path", self::empty_path));//TODO: register module in Config::$MODULES
		//TODO:Multiselection: Possible templates

		$page->add(Html::H1("Create blank module"));
		$page->add($form);

	}

	private static function create_new_module($module_name, $module_id=null, $path=null){
		$module_name=trim($module_name);
		if(!$module_name){
			return new Message(Message::TYPE_ERROR, "Please specify module name!");
		}
		if($path==self::empty_path){
			$path=null;
		}
		//TODO:Create module id from module name
		//TODO:Create absolute_path
		//TODO:Create relative_path
		//TODO
		return new Message(Message::TYPE_CONFIRM, "Module \"$module_name\" created.");
	}

}