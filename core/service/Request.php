<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

/*
//equire_once ROOT_DIR . '/core/service/Request.php';
 */

namespace t2\core\service;


class Request {

	/**
	 * Encapsulates reading of a value from the $_REQUEST array.
	 * @param string      $key
	 * @param string|null $default
	 * @return string|null
	 */
	public static function value($key, $default = null) {
		if (isset($_REQUEST[$key])) {
			return $_REQUEST[$key];
		}
		return $default;
	}

	public static function value_unset($key, $default = null) {
		$val = self::value($key, $default);
		unset($_REQUEST[$key]);
		return $val;
	}

	/**
	 * Checks, if the $_REQUEST value of "cmd" is set to command $cmd.
	 * @param string $cmd
	 * @return bool
	 */
	public static function cmd($cmd) {
		return (isset($_REQUEST["cmd"]) && ($_REQUEST["cmd"] == $cmd));
	}


}
