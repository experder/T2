<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\service;

use t2\api\Default_values;
use t2\core\Database;
use t2\core\Database_Service;
use t2\core\Error;
use t2\core\Html;
use t2\core\Message;
use t2\core\Page;
use t2\core\Warning;
use t2\dev\Install_wizard;

class Config {

	public static $DEVMODE = false;

	private static $cfg_modules = null;

	public static $dev_lv_line = 0;

	const PLATFORM_WINDOWS = 'windows';
	const PLATFORM_LINUX = 'linux';

	public static function init_platform() {
		if (($value = Config::recall_val(null, null, 'PLATFORM')) !== false) {
			if (Config::$DEVMODE) {
				new Error("PLATFORM_ERROR", "PLATFORM already initialized!");
			}
			return $value;
		}

		$value = false;

		#Debug::out(PHP_OS.','.PHP_OS_FAMILY."(Verfügbar ab PHP 7.2.0)");
		if(PHP_OS==="WINNT"){
			$value = self::PLATFORM_WINDOWS;
		}
		if(PHP_OS==="Linux"){
			$value = self::PLATFORM_LINUX;
		}

		if($value===false){
			new Error("INTERNAL_ERROR_PLATFORMDETECTION","Can't detect platform!");
		}
		Config::store_val(null, null, 'PLATFORM', $value);
		return $value;
	}

	/**
	 *
	 * <code>
	 * if ($platform_checked == Config::PLATFORM_WINDOWS) {
	 * } else if ($platform_checked == Config::PLATFORM_LINUX) {
	 * } else {
	 *    //Should not happen because $platform_checked should be checked already
	 *    new Error_("Unknown platform.");
	 * }
	 * </code>
	 *
	 * @return string
	 */
	public static function get_check_platform() {
		$platform = Config::get_value_core("PLATFORM");
		if ($platform != self::PLATFORM_WINDOWS
			&& $platform != self::PLATFORM_LINUX
		) {
			new Error("Unknown_platform", "Unknown platform (\"$platform\")!", "Try this: DELETE FROM `core_config` WHERE (`idstring`='PLATFORM');");
		}
		return $platform;
	}

	/**
	 * @deprecated TODO
	 */
	public static function cfg_http_root() {
		return self::get_value_core('HTTP_ROOT');
	}

	/**
	 * @deprecated
	 */
	public static function cfg_http_project() {
		$path = self::get_value_core('HTTP_ROOT') . '/' . Files::relative_path(ROOT_DIR, PROJECT_ROOT);//TODO(F):Feature: Wizard: Prompt HTTP_PROJECT
		$path = Files::cleanup_relative_path($path);
		return $path;
		#return self::get_value_core('HTTP_PROJECT');
	}

	public static function MODULES() {
		//TODO(F): Make modules configuration an object?
		if (self::$cfg_modules === null) {
			$modules_json = self::get_value('MODULES', null, null);
			self::$cfg_modules = json_decode($modules_json, true);
			if (self::$cfg_modules === null) {
				Error::or_Warning("MODULE_CFG_INVALID_JSON", "Module configuration is invalid JSON."
					, "Review/Fix it here: ".Html::href_internal_root("core/mod/admin_config"));
				$modules_json = self::get_default_value('core', 'MODULES');
				self::$cfg_modules = json_decode($modules_json, true);
			}
		}
		return self::$cfg_modules;
	}

	/**
	 * All config values that has been loaded from the database are stored here.
	 * The structure of the 3-level associative array is: self::$config[module][user_id|0][key]
	 * @var array $config
	 */
	private static $config = array();

	/**
	 * @param string        $id
	 * @param string|null   $module
	 * @param int|null      $user
	 * @param string|true   $default_value
	 *                      TRUE: get default value from module-specific configured source
	 * @param bool          $use_cache
	 * @param Database|null $database
	 * @param int           $backtrace_depth
	 * @return string
	 */
	public static function get_value($id, $module = null, $user = null, $default_value = true, $use_cache = true, $database = null, $backtrace_depth = 0) {
		if ($database === null) {
			$database = Database::get_singleton(false);
		}
		if ($use_cache) {
			$value = self::recall_val($module, $user, $id);
			if ($value !== false) {
				return $value;
			}
		}
		$ignore_errors = !Config::$DEVMODE;
		if ($database === false) {
			$data = false;
		} else {
			$core_config = DB_CORE_PREFIX . '_config';
			$data = $database->select_single(
				"SELECT `content` FROM $core_config WHERE `idstring`=:id AND module<=>:module AND userid<=>:userid;",
				array(
					"id" => $id,
					"module" => $module,
					"userid" => $user,
				)
				, $backtrace_depth + 1, $ignore_errors
			);
		}
		if (!$data) {
			if ($default_value === true) {
				$default_value = self::get_default_value($module ?: 'core', $id, $backtrace_depth + 1);
			}
			return $default_value;
		}
		if (is_int($data)) {
			new Warning("CONFIG_DATABASE_CORRUPT1", "Config database corrupt!", "Multiple entries found for \"$id\" (module " . ($module ?: 'core') . ").");
		}
		$value = $data["content"];
		if ($use_cache) {
			self::store_val($module, $user, $id, $value);
		}
		return $value;
	}

	public static $prompting_http_root = false;

	public static function init_http_root($relative = false) {
		if (($value = Config::recall_val(null, null, 'HTTP_ROOT')) !== false) {
			if (Config::$DEVMODE) {
				new Warning("HTTP_ROOT already initialized!", "HTTP_ROOT already initialized!");
			}
			return $value;
		}

		//Guess HTTP_ROOT:
		$value = Files::relative_path($_SERVER["SCRIPT_FILENAME"], ROOT_DIR);

		if ($relative
			|| self::$prompting_http_root//We're just prompting for it.
		) {
			Config::store_val(null, null, 'HTTP_ROOT', $value);
			return $value;
		}

		//Prompt HTTP_ROOT:
		$value = Install_wizard::prompt_http_root();
		if ($value === false) {
			new Error("SET_HTTP_ROOT", "Could not set HTTP_ROOT.");
		}
		Config::set_value('HTTP_ROOT', $value);
		Page::add_message_confirm_("HTTP_ROOT set to: \"$value\"");
		return $value;
	}

	public static function get_default_value($module, $id, $backtrace_depth = 0) {
		#Debug::out($module.':'.$id,"default_value");
		$module = $module ?: 'core';
		if ($module === 'core') {
			if ($id == 'HTTP_ROOT') {
				return self::init_http_root();
			}
			if ($id == 'PLATFORM') {
				return Config::init_platform();
			}
		}
		$singleton = Default_values::get_singleton_by_module($module);
		$value = $singleton->get_default_value($id);
		if ($value === null) {
			$hint = "???(TODO)190";//TODO(3): Determine Default_values for given module
			if ($module === 'core') {
				$class = '\\t2\\core\\mod\\Core_values';
				try {
					$reflection_class = new \ReflectionClass($class);
					$class = $reflection_class->getFileName();
				}catch(\ReflectionException $e){}
				$hint = "Add here: ".$class;
			}
			new Error("NO_DEFAULT_VALUE", "No default value provided for: $module|$id", $hint, $backtrace_depth + 1);
		}
		return $value;
	}


	/**
	 * @param string      $id
	 * @param string|true $default_value
	 *                      TRUE: get default value from \admin\Core_values
	 * @param int|null    $user
	 * @param int         $backtrace_depth
	 * @return string
	 * @see \t2\core\mod\Core_values
	 */
	public static function get_value_core($id, $default_value = true, $user = null, $backtrace_depth = 0) {
		return self::get_value($id, null, $user, $default_value, true, null, $backtrace_depth + 1);
	}

	/**
	 * @param string        $id
	 * @param string        $value
	 * @param string|null   $module
	 * @param int|null      $user
	 * @param Database|null $database
	 * @return int|false Number of modified rows or ID of the inserted data or false in case of any failure
	 */
	public static function set_value($id, $value, $module = null, $user = null, $database = null) {
		if ($database === null) {
			$database = Database::get_singleton();
		}
		$where = array(
			"idstring" => $id,
			"module" => $module,
			"userid" => $user
		);
		$core_config = DB_CORE_PREFIX . '_config';
		$r = $database->update_or_insert($core_config, $where, array("content" => $value));
//		if(Config::$DEVMODE){
//			if ($r && $r>1){
//				new Warning("CONFIG_CORRUPT", "Redundant config values found.", ($module?:'core').'|'.$id);
//			}
//		}
		self::store_val($module, $user, $id, $value);
		return $r;
	}

	public static $dev_line_api = __LINE__;
	public static function load_values_api() {
		$modules = Config::get_modules_ids();

		$substitutions = array();
		$ids_keys = array();
		$i = 1;
		foreach ($modules as $id) {
			$key = ':key' . ($i++);
			$ids_keys[] = $key;
			$substitutions[$key] = $id;
		}
		$ids_sql = implode(',', $ids_keys);

		$core_config = DB_CORE_PREFIX . '_config';
		/*$dev_line_api*/$result = Database_Service::select(
			"SELECT module,`content`,idstring FROM $core_config WHERE `idstring` IN ('API_DIR') AND module IN ($ids_sql) AND userid<=>NULL;"
				, $substitutions);
		foreach ($result as $row){
			$module = $row['module'];
			$key = $row['idstring'];
			$value = $row['content'];
			self::store_val($module, '', $key, $value);
		}
	}

	/**
	 * @param string[]    $ids
	 * @param string|null $module
	 * @param int|null    $user
	 */
	public static function load_values($ids, $module = null, $user = null) {
		$substitutions = array();
		$ids_keys = array();
		$i = 1;
		foreach ($ids as $id) {
			$key = ':key' . ($i++);
			$ids_keys[] = $key;
			$substitutions[$key] = $id;
		}
		$ids_sql = implode(',', $ids_keys);

		$substitutions['module'] = $module;
		$substitutions['userid'] = $user;

		$core_config = DB_CORE_PREFIX . '_config';
		self::$dev_lv_line = __LINE__ + 3;
		$data = Database_Service::select(
			"SELECT idstring,`content` FROM $core_config WHERE `idstring` in ($ids_sql) AND module<=>:module AND userid <=> :userid;",
			$substitutions, 0, false
		);
		$error = Database::get_singleton()->getError();
		if ($error !== false) {
			if ($error->isType(Error::TYPE_TABLE_NOT_FOUND)) {
				$report = Install_wizard::init_db_config();
				$msg = new Message(Message::TYPE_CONFIRM, "DB \"" . Database::get_singleton()->get_dbname() . "\" initialized. " . $report);
				Page::add_message($msg);
			} else {
				Database::destroy();//Make Page Standalone (TODO(1)-check out!)
				$error->report();
			}
		}

		if ($data) {
			$ignore_errors = !Config::$DEVMODE;
			if (!$ignore_errors) {
				$values = array();
			}
			foreach ($data as $val) {
				if (!$ignore_errors) {
					if (isset($values[$val['idstring']])) {
						new Error("CONFIG_DATABASE_CORRUPT2", "Config database corrupt!", "Multiple entries found for \"" . $val['idstring'] . "\" (module " . ($module ?: 'core') . ").");
					}
					$values[$val['idstring']] = true;
				}
				self::store_val($module, $user, $val['idstring'], $val['content']);
			}
			//Default values:
			foreach ($ids as $id) {
				if (self::recall_val($module, $user, $id) === false) {
					$default_value = self::get_default_value($module, $id);
					self::store_val($module, $user, $id, $default_value);
				}
			}
		}
	}

	private static function store_val($module, $user, $key, $value) {
		$module_index = ($module === null ? "core" : $module);
		$user_index = 'u' . $user;
		self::$config[$module_index][$user_index][$key] = $value;
	}

	private static function recall_val($module, $user, $key) {
		$module_index = ($module === null ? "core" : $module);
		$user_index = 'u' . $user;
		if (!isset(self::$config[$module_index][$user_index][$key])) {
			return false;
		}
		return self::$config[$module_index][$user_index][$key];
	}

	/**
	 * @deprecated TODO: Module as a class!
	 */
	public static function get_modules_ids() {
		$modules = Config::MODULES();
		$module_ids = array();
		foreach ($modules as $module_id => $dummy) {
			$module_ids[] = $module_id;
		}
		return $module_ids;
	}

}

Config::$dev_line_api+=16;
