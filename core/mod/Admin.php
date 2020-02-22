<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\mod;

use t2\api\Service;
use t2\api\Update_database;
use t2\core\Error;
use t2\core\form\Form;
use t2\core\form\Formfield_header;
use t2\core\form\Formfield_text;
use t2\core\form\Formfield_textarea;
use t2\core\Message;
use t2\core\Page;
use t2\core\service\Config;
use t2\core\service\Includes;
use t2\core\service\Request;
use t2\dev\Install_wizard;
use t2\Start;

class Admin {

	public static function update_includes() {
		$page = new Page("", "");

		Includes::load_all_available($page);

		$result = $page->get_messages_plain();
		if (!$result) {
			$result = "Already up to date.\n";
		}
		$result = "\n========= Download Includes =========\n$result";
		return $result;
	}

	public static function get_update_script_name() {
		$shellname = "?";
		$platform = Config::get_check_platform();
		if ($platform == Config::PLATFORM_WINDOWS) {
			$shellname = 'update.cmd';
		} else if ($platform == Config::PLATFORM_LINUX) {
			$shellname = 'update.sh';
		} else {
			//Should not happen because $platform should be checked already
			new Error("Unknown_platform", "Unknown platform.");
		}
		return $shellname;
	}

	public static function update_shell() {

		$platform = Config::get_check_platform();
		$project_root = PROJECT_ROOT;

		#$shellname = Admin::get_update_script_name();

		if ($platform == Config::PLATFORM_WINDOWS) {
			if (!file_exists(PROJECT_ROOT . '/update.cmd')) {
				Install_wizard::init_updater($platform);
			}
			$result = `cd "$project_root" && update.cmd 2>&1`;
			$result = mb_convert_encoding($result, "utf-8", "cp850");

		} else if ($platform == Config::PLATFORM_LINUX) {
			if (!file_exists(PROJECT_ROOT . '/update.sh')) {
				Install_wizard::init_updater($platform);
			}
			$result = `cd '$project_root' && ./update.sh 2>&1`;

		} else {
			//Should not happen because $platform should be checked already
			new Error("Unknown_platform", "Unknown platform.");
			$result = "";
		}

		$result = "\n" . htmlentities($result);

		return $result;
	}

	public static function update_dbase() {

		$results = array();

		$updater = new Core_database();
		if (($ver = $updater->update()) !== false) {
			$results[] = "core: " . $ver;
		}

		$modules = Config::get_modules_ids();

		foreach ($modules as $module) {
			$update = Service::get_api_class($module, "Update_database", $error, $return);
			if (!($update instanceof Update_database)) {
				if ($error == Service::API_ERROR_FILE_NOT_FOUND) {
					if (Config::$DEVMODE) {
						$results[] = "NOTE! $module has no updater!";
					}
				} else {
					new Error("API_ERROR_INT", "Unknown error in internal api.");
				}
			} else {
				if (($ver = $update->update()) !== false) {
					$results[] = $module . ': ' . $ver;
				}
			}
		}

		$result = "\n========= Update_database =========\n";
		$result .= $results ? implode("\n", $results) : "All up to date.";
		$result .= "\n";

		return $result;

	}

	private static function save_config() {
		$updated = array();
		foreach ($_POST as $key => $value) {
			$ok = self::save_config_value($key, $value);
			if ($ok) {
				$updated[] = $key;
			}
		}
		if ($updated) {
			Page::$compiler_messages[] = new Message(Message::TYPE_CONFIRM, "Updated: " . implode(", ", $updated));
		} else {
			Page::$compiler_messages[] = new Message(Message::TYPE_INFO, "(Nothing updated)");
		}
	}

	private static function cleanup_key($key, $maxlen = 0, $chars_regex = '0-9a-z_') {
		if ($maxlen !== 0) {
			$key = mb_substr($key, 0, $maxlen);
		}
		$key = preg_replace("/[^$chars_regex]/i", "", $key);
		return $key;
	}

	private static function save_config_value($key, $value, $module = null) {
		if (!$key) {
			new Error("!", "!");
			return false;
		}
		$key_clean = self::cleanup_key($key, 99, '0-9a-z_');
		if (!$key_clean) {
			new Error("!", "!");
			return false;
		}
		if ($key_clean !== $key) {
			new Error("INVALID_KEY", "Invalid key!", "'$key' (=> '$key_clean' )");
			return false;
		}
		$ok = Config::set_value($key_clean, $value, $module);
		return $ok;
	}

	public static function get_config_form() {

		if (Request::cmd('t2_update_cfg')) {
			unset($_POST['cmd']);
			self::save_config();
		}

		$form = new Form('t2_update_cfg');

		/**
		 * @see Start::init_database()
		 * @see Core_values::$default_values
		 */

		//TODO(1): It's all deprecated/going to config FILES. Database shall only store db-specific configuration
		$form->add_field(new Formfield_header("<h2>Admin stuff</h2>"));
		$form->add_field(new Formfield_text('PROJECT_TITLE', null, Config::get_value('PROJECT_TITLE')));
		$form->add_field(new Formfield_text('SKIN', null, Config::get_value('SKIN')));
		$form->add_field(new Formfield_textarea('MODULES', null, Config::get_value('MODULES')));
		$form->add_field(new Formfield_textarea('LOGIN_HTML', null, Config::get_value('LOGIN_HTML')));
		$form->add_field(new Formfield_text('SESSION_EXPIRES', null, Config::get_value('SESSION_EXPIRES')));

		$form->add_field(new Formfield_header("<h2>Developer stuff</h2>"));
		$form->add_field(new Formfield_text('EXTENSION', null, Config::get_value('EXTENSION')));
		$form->add_field(new Formfield_text('HTTP_ROOT', null, Config::get_value('HTTP_ROOT')));
		$form->add_field(new Formfield_text('PLATFORM', null, Config::get_value('PLATFORM')));
		$form->add_field(new Formfield_text('MODULE_ROOT', null, Config::get_value('MODULE_ROOT')));
		$form->add_field(new Formfield_text('MODULE_PATH', null, Config::get_value('MODULE_PATH')));
		$form->add_field(new Formfield_text('DEFAULT_API_DIR', null, Config::get_value('DEFAULT_API_DIR')));

		return $form;
	}

}