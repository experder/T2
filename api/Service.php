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
namespace t2\api;


use core\Error;
use service\Config;

class Service {

	public static function get_api_class($module, $classname){
		if(!class_exists($api_classname = "\\t2\\api\\$classname")){
			Error::quit("Invalid API-Request (class $api_classname does not exist)", 1);
		}
		$modules = Config::MODULES();
		if(!isset($modules[$module][$classname]['include'])||!isset($modules[$module][$classname]['class'])){
			Error::quit("Module configuration is invalid. Cannot get: {\"$module\":{\"$classname\"}} (must contain \"include\" and \"class\")");
		}
		$include_file= str_replace(':ROOT_DIR',ROOT_DIR,$modules[$module][$classname]['include']);
		if(!file_exists($include_file)){
			Error::quit("Module configuration is corrupt. {\"$module\":{\"$classname\":{\"include\":...}}}:\nFile not found: $include_file");
		}
		/** @noinspection PhpIncludeInspection */
		include_once $include_file;
		$class = $modules[$module][$classname]['class'];
		if (!class_exists($class)){
			Error::quit("Module configuration defines not-existing class: \"$class\" {\"$module\":{\"$classname\":{\"class\":...}}}");
		}
		try {
			$reflection = new \ReflectionClass($class);
			$ref_api = new \ReflectionClass($api_classname);
		} catch (\ReflectionException $e) {
			Error::from_exception($e);
		}
		if(!($reflection->isSubclassOf($ref_api))){
			Error::quit("Module configuration defines class that is not of type of $classname: \"$class\" {\"$module\":{\"$classname\":{\"class\":...}}}");
		}
		if(($constructor=$reflection->getConstructor()) && $constructor->getNumberOfRequiredParameters()!=0){
			Error::quit("Module configuration: Constructor of class \"$class\" has the wrong number of required arguments.");
		}
		$instance = $reflection->newInstance();
		return $instance;
	}

}