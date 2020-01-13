<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/
/*
require_once ROOT_DIR.'/dev/api/Default_values.php';
 */

namespace t2\api;

require_once ROOT_DIR . '/dev/api/Service.php';

use admin\Core_values;


abstract class Default_values {

	protected $default_values = null;

	/**
	 * @var Default_values[] $singleton_by_module Array of singletons
	 */
	private static $singleton_by_module = array();

	public function get_default_value($key){
		if(!isset($this->default_values[$key])){
			return null;
		}
		return $this->default_values[$key];

	}

	/**
	 * @return Default_values
	 */
	public static function get_singleton_by_module($module){
		if(!isset(self::$singleton_by_module[$module])){
			if($module=='core'){
				require_once ROOT_DIR . '/core/api/Core_values.php';
				self::$singleton_by_module[$module]=new Core_values();
			}else{
				self::$singleton_by_module[$module] = Service::get_api_class($module, "Default_values");
			}
		}
		return self::$singleton_by_module[$module];
	}

}