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

namespace service;

require_once ROOT_DIR . '/core/Database.php';
//require_once ROOT_DIR . '/dev/api/Default_values.php';

use t2\core\api\Core_values;
use admin\Install_wizard;
use t2\api\Default_values;
use t2\core\Database;
use t2\core\Error_;
use t2\core\Message;
use t2\core\Page;

class Config {

	public static $DEVMODE = true;

	/**
	 * @see \t2\Start::init_database()
	 */
	#public static $PROJECT_TITLE;

	private static $MODULES = null;

	private static $PLATFORM = null;

	const PLATFORM_WINDOWS = 'windows';
	const PLATFORM_LINUX = 'linux';

	public static function PLATFORM(){
		if(self::$PLATFORM===null){
			//TODO(2):Detect platform!
			#new Error_("Can't detect platform!");
			self::$PLATFORM=self::PLATFORM_WINDOWS;
		}
		return self::$PLATFORM;
	}

	/**
	 * @return array
	 */
	public static function MODULES(){
		if(self::$MODULES===null){
			$modules_json = self::get_value('MODULES', null, null, false);
			if($modules_json!==false && !(self::$MODULES = json_decode($modules_json, true))){
				//TODO:Warning, not Error.
				new Error_("Module configuration is invalid JSON. Switching to default.");
				$modules_json=false;
			}
			if($modules_json===false){
				self::set_modules_default();
			}
		}
		return self::$MODULES;
	}

	private static function set_modules_default(){
		require_once ROOT_DIR . '/core/api/Core_values.php';
		$dv = new Core_values();
		$modules_json = $dv->get_default_value("MODULES");
		if(!(self::$MODULES = json_decode($modules_json, true))){
			Error_::quit("Invalid JSON:\n\\admin\\Core_values->\$default_values:\n$modules_json");
		}
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
				, $backtrace_depth+1
			);
		}
		if (!$data) {
			if($default_value===true){
				$default_value = self::get_default_value($module?:'core', $id, $backtrace_depth+1);
			}
			return $default_value;
		}
		$value = $data["content"];
		if ($use_cache) {
			self::store_val($module, $user, $id, $value);
		}
		return $value;
	}

	public static function get_default_value($module, $id, $backtrace_depth=0){
		require_once ROOT_DIR.'/dev/api/Default_values.php';
		$module = $module?:'core';
		$singleton = Default_values::get_singleton_by_module($module);
		$value = $singleton->get_default_value($id);
		if($value===null){
			new Error_("No default value provided for: $module|$id",0,"Add here: \\t2\\core\\api\\Core_values", $backtrace_depth+1);
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
	 * @see \t2\core\api\Core_values
	 * TODO: aufräumen bei get config und default Berechnung für modules, http root und platform
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

	/**
	 * @param string[] $ids
	 * @param string|null   $module
	 * @param int|null      $user
	 */
	public static function load_values($ids, $module = null, $user = null, $init_modules=false) {
		require_once ROOT_DIR . '/core/service/Strings.php';
		$ids_sql = Strings::build_sql_collection($ids);
		#$core_config = DB_CORE_PREFIX.'_config';
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
				Database::destroy();//Make Page Standalone (TODO: Necessary?)
				$error->report();
			}
		}

		if ($data) {
			foreach ($data as $val) {
				self::store_val($module, $user, $val['idstring'], $val['content']);
			}
			if($init_modules){
				if(self::recall_val($module, $user, 'MODULES')===false){
					self::set_modules_default();
				}
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