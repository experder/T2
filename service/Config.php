<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


/*
require_once ROOT_DIR . '/service/Config.php';
 */

namespace service;

require_once ROOT_DIR . '/service/Strings.php';

use core\Database;
use core\Error;
use core\Message;
use core\Page;

class Config {

	// Set to false if in production environment:
	public static $DEVMODE = true;

	/**
	 * @see \tethys_root\Start::init_database()
	 */
	#public static $PROJECT_TITLE;

	/**
	 * All config values that has been loaded from the database are stored here.
	 * The structure of the 3-level associative array is: self::$config[module][user_id|0][key]
	 * @var array $config
	 */
	private static $config = array();

	/**
	 * The default value is NOT cached (self::$config),
	 * so the next call of this function can return a different value.
	 * @param string        $id
	 * @param string|null        $module
	 * @param int|null           $user
	 * @param string|null         $default_value
	 * @param bool          $use_cache
	 * @param Database|null $database
	 * @return string|null
	 */
	public static function get_value($id, $module = null, $user = null, $default_value = null, $use_cache = true, $database = null) {
		if ($database === null) {
			$database = Database::get_singleton();
		}
		if ($use_cache) {
			$value = self::recall_val($module, $user, $id);
			if($value!==false){
				return $value;
			}
		}
		$data = $database->select_single(
			"SELECT `content` FROM core_config WHERE `idstring`=:id AND module<=>:module AND userid<=>:userid;",
			array(
				"id" => $id,
				"module" => $module,
				"userid" => $user,
			)
		);
		if (!$data) {
			return $default_value;
		}
		$value = $data["content"];
		if ($use_cache) {
			self::store_val($module, $user, $id, $value);
		}
		return $value;
	}

	/**
	 * @param string $id
	 * @param string|null $default_value
	 * @param int|null $user
	 * @return string|null
	 */
	public static function get_value_core($id, $default_value = null, $user = null) {
		return self::get_value($id, null, $user, $default_value);
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
	}

	/**
	 * @param string[] $ids
	 * @param string|null   $module
	 * @param int|null      $user
	 */
	public static function load_values($ids, $module = null, $user = null) {
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
		if($error=Database::get_singleton()->getError()){
			if($error instanceof Error){
				if($error->get_type()==Error::TYPE_TABLE_NOT_FOUND){
					require_once ROOT_DIR . '/admin/Install_wizard.php';
					$msg = new Message(Message::TYPE_CONFIRM, "DB \"".Database::get_singleton()->get_dbname()."\" initialized. ".\admin\Install_wizard::init3_db_config());
				}else{
					$msg = $error->report();
				}
				Page::$compiler_messages[] = $msg;
			}else{
				new Error("Wrong Error Type");
			}
		}
		if ($data) {
			foreach ($data as $val) {
				self::store_val($module, $user, $val['idstring'], $val['content']);
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