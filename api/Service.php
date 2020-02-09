<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\api;

use t2\core\Error;
use t2\core\service\Config;
use t2\core\mod\Core_ajax;

class Service {

	public static function get_api_class_core($classname){
		switch ($classname) {
			case "Ajax":
				return new Core_ajax();
				break;
			default:
				new Error("Unknown_classname","Unknown classname \"$classname\".", "Please specify class in \"\\t2\\api\\Service::get_api_class_core\".");
				break;
		}
		return false;
	}
	public static function get_api_class($module, $classname){
		if($module=='core'){
			return self::get_api_class_core($classname);
		}
		if(!class_exists($api_classname = "\\t2\\api\\$classname")){
			new Error("INVALID_INTERNAL_API_ACCESS","Invalid API access (class $api_classname does not exist)",null,1);
		}
		$modules = Config::MODULES();
		if(!isset($modules[$module]['custom_apis'][$classname]['include'])||!isset($modules[$module]['custom_apis'][$classname]['class'])){
			new Error("ERROR_CONFIG_CORRUPT/2","Module configuration is invalid. Cannot get: {\"$module\":{\"custom_apis\":{\"$classname\"}}} (must contain \"include\" and \"class\")");
		}
		$include_file= str_replace(':ROOT_DIR',ROOT_DIR,$modules[$module]['custom_apis'][$classname]['include']);
		if(!file_exists($include_file)){
			new Error("ERROR_CONFIG_CORRUPT/3", "Module configuration is corrupt. {\"$module\":{\"$classname\":{\"include\":...}}}:\nFile not found: $include_file");
		}
		/** @noinspection PhpIncludeInspection */
		require_once $include_file;
		$class = $modules[$module]['custom_apis'][$classname]['class'];
		if (!class_exists($class)){
			new Error("ERROR_IN_MOD_CFG","Module configuration defines not-existing class: \"$class\" {\"$module\":{\"$classname\":{\"class\":...}}}");
		}
		try {
			$reflection = new \ReflectionClass($class);
			$ref_api = new \ReflectionClass($api_classname);
		} catch (\ReflectionException $e) {
			Error::from_exception($e);
			exit;
		}
		if(!($reflection->isSubclassOf($ref_api))){
			new Error("MOD_CFG_TYPE_ERROR","Module configuration defines class that is not of type of $classname: \"$class\" {\"$module\":{\"$classname\":{\"class\":...}}}");
		}
		if(($constructor=$reflection->getConstructor()) && $constructor->getNumberOfRequiredParameters()!=0){
			new Error("MOD_CFG_CONST_ARGS_ERROR","Module configuration: Constructor of class \"$class\" has the wrong number of required arguments.");
		}
		$instance = $reflection->newInstance();
		return $instance;
	}

}