<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\api;

use t2\core\Error;
use t2\core\mod\Core_ajax;
use t2\core\service\Config;

class Service {

	const API_ERROR_FILE_NOT_FOUND = 1;

	public static function get_api_class_core($classname) {
		switch ($classname) {
			case "Ajax":
				return new Core_ajax();
				break;
			default:
				new Error("Unknown_classname", "Unknown classname \"$classname\".", "Please specify class in \"\\t2\\api\\Service::get_api_class_core\".");
				break;
		}
		return false;
	}

	public static function get_api_class($module, $classname, &$error = false, &$return = null) {
		if ($module == 'core') {
			return self::get_api_class_core($classname);
		}
		if (!class_exists($api_classname = "\\t2\\api\\$classname")) {
			new Error("INVALID_INTERNAL_API_ACCESS", "Invalid API access (class $api_classname does not exist)", null, 1);
		}
		$modules = Config::MODULES();
		if (!isset($modules[$module]['custom_apis'][$classname]['include'])) {
			//Default API file name:
			$module_root = Config::get_value_core('MODULE_ROOT');
			$api_dir = Config::get_value('API_DIR', $module, null, false);
			if (!$api_dir) {
				$api_dir = Config::get_value_core('DEFAULT_API_DIR');
			}
			$include_file = $module_root . "/$module/" . $api_dir . "/My_$classname.php";
		} else {
			$include_file = $modules[$module]['custom_apis'][$classname]['include'];
		}
		if (!isset($modules[$module]['custom_apis'][$classname]['class'])) {
			//Default API class name:
			$class = "t2\\modules\\$module\\api\\My_$classname";
		} else {
			$class = $modules[$module]['custom_apis'][$classname]['class'];
		}
		$include_file = str_replace(':ROOT_DIR', ROOT_DIR, $include_file);
		if (!file_exists($include_file)) {
			if ($error !== false) {
				$error = self::API_ERROR_FILE_NOT_FOUND;
				$return = $include_file;
				return false;
			}
			new Error("ERROR_CONFIG_CORRUPT/3", "Module configuration is corrupt. {\"$module\":{\"$classname\":{\"include\":...}}}:\nFile not found: $include_file");
		}
		/** @noinspection PhpIncludeInspection */
		require_once $include_file;
		if (!class_exists($class)) {
			new Error("ERROR_IN_MOD_CFG", "Module configuration defines not-existing class: \"$class\" {\"$module\":{\"$classname\":{\"class\":...}}}");
		}
		try {
			$reflection = new \ReflectionClass($class);
			$ref_api = new \ReflectionClass($api_classname);
		} catch (\ReflectionException $e) {
			Error::from_exception($e);
			exit;
		}
		if (!($reflection->isSubclassOf($ref_api))) {
			new Error("MOD_CFG_TYPE_ERROR", "Module configuration defines class that is not of type of $classname: \"$class\" {\"$module\":{\"$classname\":{\"class\":...}}}");
		}
		if (($constructor = $reflection->getConstructor()) && $constructor->getNumberOfRequiredParameters() != 0) {
			new Error("MOD_CFG_CONST_ARGS_ERROR", "Module configuration: Constructor of class \"$class\" has the wrong number of required arguments.");
		}
		$instance = $reflection->newInstance();
		return $instance;
	}

}