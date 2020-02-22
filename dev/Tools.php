<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\dev;

use t2\core\form\Form;
use t2\core\form\Formfield_text;
use t2\core\Html;
use t2\core\Message;
use t2\core\Page;
use t2\core\service\Config;
use t2\core\service\Files;
use t2\core\service\Request;
use t2\core\service\Templates;

class Tools {

	const empty_path = '(Leave empty for default)';

	public static function prompt_new_module(Page $page) {

		if (Request::cmd("cmd_newmodule")) {
			$msg = self::create_new_module(
				Request::value('module_name'),
				Request::value('module_id'),
				Request::value('path')
			);
			Page::$compiler_messages[] = $msg;
			if ($msg->getType() == Message::TYPE_CONFIRM) {
				$page->send_and_quit();
			}
		}

		$form = new Form("cmd_newmodule");

		$form->add_field(new Formfield_text("module_name", "Module name", "My module"));
		$form->add_field(new Formfield_text("module_id", "Module ID", "mymod"));
		$form->add_field(new Formfield_text("path", "Path", self::empty_path));

		$page->add(Html::H1("Create blank module"));
		$page->add($form);

	}

	public static function create_new_module($module_name, $module_id = null, $path = null, $api_selection = true) {
		$module_name = trim($module_name);
		if (!$module_name) {
			return new Message(Message::TYPE_ERROR, "Please specify module name!");
		}
		if ($path == self::empty_path) {
			$path = null;
		}
		if ($path === null) {
			$path = Config::get_value_core('MODULE_ROOT');
			$path = str_replace(':ROOT_DIR', ROOT_DIR, $path);//TODO(3):replace t2 specific path variablesFunction in class Config for that conversion
		}
		$path = Files::cleanup_relative_path($path);
		if (!$module_id) {
			$module_id = $module_name;
		}
		if ($api_selection === true) {
			$api_selection = array();//TODO(3):create new module: copy all api classes
		}
		$module_id = preg_replace("/[^a-z0-9_]/", "", strtolower($module_id));

		foreach ($api_selection as $api_class) {
			Templates::create_file($path . '/' . $api_class, ROOT_DIR . "/dev/templates/module/tethys/$api_class", array(
				'namespace t2\modules\core_template\api;//(:moduleIdLc)' => "namespace t2\\modules\\$module_id\\api;",
				":moduleIdLc" => $module_id,
				":moduleIdUc" => strtoupper($module_id),
			));
		}

		return new Message(Message::TYPE_CONFIRM, "Module \"$module_name\" created.");
	}

}