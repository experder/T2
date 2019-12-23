<?php
/*
require_once ROOT_DIR.'/service/Request.php';
 */
namespace service;


class Request {

	/**
	 * Encapsulates reading of a value from the $_REQUEST array.
	 * @param string      $key
	 * @param string|null $default
	 * @return string|null
	 */
	public static function value($key, $default = null) {
		if (isset($_REQUEST[$key])){
			return $_REQUEST[$key];
		}
		return $default;
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
