<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/
/*
require_once ROOT_DIR.'/api/Service.php';
 */
namespace t2\api;//TODO:check/reorg namespaces

require_once ROOT_DIR . '/core/service/Config.php';
require_once ROOT_DIR . '/core/Error_.php';

use service\Config;
use t2\core\mod\Core_ajax;
use t2\core\Error_;

class Service {

	public static function get_api_class_core($classname){
		switch ($classname) {
			case "Ajax":
				require_once ROOT_DIR . '/core/mod/Core_ajax.php';
				return new Core_ajax();
				break;
			default:
				new Error_("Unknown classname \"$classname\".", 0, "Please specify class in \"\\t2\\api\\Service::get_api_class_core\".");
				break;
		}
	}
	public static function get_api_class($module, $classname){
		if($module=='core'){
			return self::get_api_class_core($classname);
		}
		if(!class_exists($api_classname = "\\t2\\api\\$classname")){
			Error_::quit("Invalid API-Request (class $api_classname does not exist)", 1);
		}
		$modules = Config::MODULES();
		if(!isset($modules[$module][$classname]['include'])||!isset($modules[$module][$classname]['class'])){
			Error_::quit("Module configuration is invalid. Cannot get: {\"$module\":{\"$classname\"}} (must contain \"include\" and \"class\")");
		}
		$include_file= str_replace(':ROOT_DIR',ROOT_DIR,$modules[$module][$classname]['include']);
		if(!file_exists($include_file)){
			Error_::quit("Module configuration is corrupt. {\"$module\":{\"$classname\":{\"include\":...}}}:\nFile not found: $include_file");
		}
		/** @noinspection PhpIncludeInspection */
		include_once $include_file;
		$class = $modules[$module][$classname]['class'];
		if (!class_exists($class)){
			Error_::quit("Module configuration defines not-existing class: \"$class\" {\"$module\":{\"$classname\":{\"class\":...}}}");
		}
		try {
			$reflection = new \ReflectionClass($class);
			$ref_api = new \ReflectionClass($api_classname);
		} catch (\ReflectionException $e) {
			Error_::from_exception($e);
		}
		if(!($reflection->isSubclassOf($ref_api))){
			Error_::quit("Module configuration defines class that is not of type of $classname: \"$class\" {\"$module\":{\"$classname\":{\"class\":...}}}");
		}
		if(($constructor=$reflection->getConstructor()) && $constructor->getNumberOfRequiredParameters()!=0){
			Error_::quit("Module configuration: Constructor of class \"$class\" has the wrong number of required arguments.");
		}
		$instance = $reflection->newInstance();
		return $instance;
	}

}