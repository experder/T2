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

class Config {

	/**
	 * All config values that has been loaded from the database are stored here.
	 * The structure of the 3-level associative array is: self::$config[module][user_id|0][key]
	 * @var array $config
	 */
	private static $config = array();

	/**
	 * The default value is NOT cached (self::$core_config),
	 * so the next call of this function can return a different value.
	 * @param string        $id
	 * @param string        $module
	 * @param int           $user
	 * @param mixed         $default_value
	 * @param bool          $use_cache
	 * @param Database|null $database
	 * @return string|mixed string|$default_value
	 */
	public static function get_value($id, $module = "core", $user = 0, $default_value = null, $use_cache = true, $database = null) {
		if ($database === null) {
			$database = Database::get_singleton();
		}
		if ($use_cache && isset(self::$config[$module][$user][$id])) {
			return self::$config[$module][$user][$id];
		}
		$data = $database->select_single(
			"SELECT `content` FROM core_config WHERE `idstring`=:id AND module=:module AND userid<=>:userid;",
			array(
				"id" => $id,
				"module" => $module,
				"userid" => $user ?: null,
			)
		);
		if (!$data) {
			return $default_value;
		}
		$value = $data["content"];
		if ($use_cache) {
			self::$config[$module][$user][$id] = $value;
		}
		return $value;
	}

	public static function set_value($id, $value, $module = "core", $user = null, $database = null) {
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
	 * @param string   $module
	 * @param int      $user
	 */
	public static function load_values($ids, $module = "core", $user = 0) {
		$ids_sql = Strings::build_sql_collection($ids);
		$data = Database::select_(
			"SELECT idstring,`content` FROM core_config WHERE `idstring` in ($ids_sql) AND module=:module AND userid <=> :userid;",
			array(
				"module" => $module,
				"userid" => $user ?: null,
			)
		);
		if ($data) {
			foreach ($data as $val) {
				self::$config[$module][$user][$val['idstring']] = $val['content'];
			}
		}
	}


}