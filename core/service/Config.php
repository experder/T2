<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


/*
require_once ROOT_DIR . '/core/service/Config.php';
 */

namespace t2\core\service;

require_once ROOT_DIR . '/core/Database.php';

use t2\dev\Install_wizard;
use t2\api\Default_values;
use t2\core\Database;
use t2\core\Error_;
use t2\core\Message;
use t2\core\Page;

class Config {

	public static $DEVMODE = true;

	private static $cfg_modules = null;

	const PLATFORM_WINDOWS = 'windows';
	const PLATFORM_LINUX = 'linux';

	public static function init_platform(){
		if(($value = Config::recall_val(null, null, 'PLATFORM'))!==false){
			if(Config::$DEVMODE){
				new Error_("PLATFORM already initialized!");
			}
			return $value;
		}
		//TODO(2):Detect platform!
		#new Error_("Can't detect platform!");
		$value = self::PLATFORM_WINDOWS;
		Config::store_val(null, null, 'PLATFORM', $value);
		return $value;
	}

	public static function MODULES(){
		//TODO(3): Make modules configuration an object!
		if(self::$cfg_modules===null){
			$modules_json = self::get_value('MODULES', null, null);
			self::$cfg_modules = json_decode($modules_json, true);
			if(self::$cfg_modules===null){
				new Error_("Module configuration is invalid JSON.");
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
	public static function get_value($id, $module = null, $user = null, $default_value = true, $use_cache = true, $database = null, $backtrace_depth=0) {
		if ($database === null) {
			$database = Database::get_singleton(false);
		}
		if ($use_cache) {
			$value = self::recall_val($module, $user, $id);
			if($value!==false){
				return $value;
			}
		}
		$ignore_errors = !Config::$DEVMODE;
		if($database===false){
			$data=false;
		}else{
			$data = $database->select_single(
				"SELECT `content` FROM core_config WHERE `idstring`=:id AND module<=>:module AND userid<=>:userid;",
				array(
					"id" => $id,
					"module" => $module,
					"userid" => $user,
				)
				, $backtrace_depth+1, $ignore_errors
			);
		}
		if (!$data) {
			if($default_value===true){
				$default_value = self::get_default_value($module?:'core', $id, $backtrace_depth+1);
			}
			return $default_value;
		}
		if(is_int($data)){
			new Error_("Config database corrupt!","CONFIG_DATABASE_CORRUPT1","Multiple entries found for \"$id\" (module ".($module?:'core').").");
		}
		$value = $data["content"];
		if ($use_cache) {
			self::store_val($module, $user, $id, $value);
		}
		return $value;
	}

	public static $prompting_http_root = false;

	public static function init_http_root($relative = false){
		if (($value = Config::recall_val(null, null, 'HTTP_ROOT')) !== false) {
			if (Config::$DEVMODE) {
				new Error_("HTTP_ROOT already initialized!");
			}
			return $value;
		}

		//Guess HTTP_ROOT:
		require_once ROOT_DIR . '/core/service/Files.php';
		$value = Files::relative_path($_SERVER["SCRIPT_FILENAME"], ROOT_DIR);

		if ($relative
			|| self::$prompting_http_root//We're just prompting for it.
		) {
			Config::store_val(null, null, 'HTTP_ROOT', $value);
			return $value;
		}

		//Prompt HTTP_ROOT:
		require_once ROOT_DIR . '/dev/Install_wizard.php';
		$value = Install_wizard::prompt_http_root();
		if ($value === false) {
			new Error_("Could not set HTTP_ROOT.");
		}
		Config::set_value('HTTP_ROOT', $value);
		Page::$compiler_messages[] = new Message(Message::TYPE_CONFIRM, "HTTP_ROOT set to: \"$value\"");
		return $value;
	}

	public static function get_default_value($module, $id, $backtrace_depth = 0) {
		require_once ROOT_DIR . '/api/Default_values.php';
		$module = $module ?: 'core';
		if($module==='core'){
			if($id=='HTTP_ROOT'){
				return self::init_http_root();
			}
			if($id=='PLATFORM'){
				return Config::init_platform();
			}
		}
		$singleton = Default_values::get_singleton_by_module($module);
		$value = $singleton->get_default_value($id);
		if ($value === null) {
			$hint = "???";//TODO(3): Determine Default_values for given module
			if ($module === 'core') {
				$hint = "Add here: \\t2\\core\\mod\\Core_values";
			}
			new Error_("No default value provided for: $module|$id", 0, $hint, $backtrace_depth + 1);
		}
		return $value;
	}



	/**
	 * @param string        $id
	 * @param string|true   $default_value
	 *                      TRUE: get default value from \admin\Core_values
	 * @param int|null      $user
	 * @param int           $backtrace_depth
	 * @return string
	 * @see \t2\core\mod\Core_values
	 */
	public static function get_value_core($id, $default_value = true, $user = null, $backtrace_depth=0) {
		return self::get_value($id, null, $user, $default_value, true, null, $backtrace_depth+1);
	}

	public static function set_value($id, $value, $module = null, $user = null, $database = null) {
		if ($database === null) {
			$database = Database::get_singleton();
		}
		$where = array(
			"idstring" => $id,
			"module" => $module,
			"userid" => $user
		);
		$database->update_or_insert("core_config", $where, array("content" => $value));
		self::store_val($module, $user, $id, $value);
	}


	public static $dev_lv_line = 0;

	/**
	 * @param string[]    $ids
	 * @param string|null $module
	 * @param int|null    $user
	 */
	public static function load_values($ids, $module = null, $user = null) {
		require_once ROOT_DIR . '/core/service/Strings.php';
		$ids_sql = Strings::build_sql_collection($ids);
		#$core_config = DB_CORE_PREFIX.'_config';
		self::$dev_lv_line = __LINE__ + 7;
		$data = Database::select_(
			"SELECT idstring,`content` FROM core_config WHERE `idstring` in ($ids_sql) AND module<=>:module AND userid <=> :userid;",
			array(
				"module" => $module,
				"userid" => $user,
			)
			,false
		);
		$error=Database::get_singleton()->getError();
		if($error!==false){
			if($error->isType(Error_::TYPE_TABLE_NOT_FOUND)){
				require_once ROOT_DIR . '/dev/Install_wizard.php';
				$report = Install_wizard::init_db_config();
				$msg = new Message(Message::TYPE_CONFIRM, "DB \"".Database::get_singleton()->get_dbname()."\" initialized. ".$report);
				Page::$compiler_messages[] = $msg;
			}else{
				Database::destroy();//Make Page Standalone (TODO(3)-Necessary?)
				$error->report();
			}
		}

		if ($data) {
			$ignore_errors = !Config::$DEVMODE;
			if(!$ignore_errors){
				$values = array();
			}
			foreach ($data as $val) {
				if(!$ignore_errors){
					if(isset($values[$val['idstring']])){
						new Error_("Config database corrupt!","CONFIG_DATABASE_CORRUPT2","Multiple entries found for \"".$val['idstring']."\" (module ".($module?:'core').").");
					}
					$values[$val['idstring']] = true;
				}
				self::store_val($module, $user, $val['idstring'], $val['content']);
			}
			//Default values:
			foreach ($ids as $id){
				if(self::recall_val($module, $user, $id)===false){
					$default_value = self::get_default_value($module, $id);
					self::store_val($module, $user, $id, $default_value);
				}
			}
		}
	}

	private static function store_val($module, $user, $key, $value){
		$module_index = ($module === null?"core":$module);
		$user_index = 'u'.$user;
		self::$config[$module_index][$user_index][$key] = $value;
	}

	private static function recall_val($module, $user, $key){
		$module_index = ($module === null?"core":$module);
		$user_index = 'u'.$user;
		if(!isset(self::$config[$module_index][$user_index][$key])){
			return false;
		}
		return self::$config[$module_index][$user_index][$key];
	}


}