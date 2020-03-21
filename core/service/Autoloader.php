<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\service;

use t2\core\Error;

class Autoloader {
	//TODO(1):Autoloader: Look into modules folder

	private static $recursion_protection = true;

	public static function register() {
		spl_autoload_register(function ($class_name) {

			$ok = preg_match("/^t2\\\\(.*)/", $class_name, $matches);
			if (!$ok) {
				Autoloader::not_found($class_name . " (doesn't match ^t2\\)", null, 2);
			}
			$name = $matches[1];
			$file = ROOT_DIR . '/' . str_replace('\\', '/', $name) . '.php';
			if (!file_exists($file)) {
				Autoloader::not_found($class_name, $file, 2);
			} else {
				/** @noinspection PhpIncludeInspection */
				require_once $file;
			}
		});
	}

	private static function not_found($class, $file = null, $depth = 0) {
		if (!self::$recursion_protection) {
			echo ":-(39";
			exit;
		}
		self::$recursion_protection = false;
		require_once ROOT_DIR . '/core/Error_.php';
		new Error("AUTOLOADER_ERROR", "Can't load \"$class\"!", $file ? "Was trying: \"$file\"." : null, $depth + 1);
	}

}
