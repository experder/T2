<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\api;


use t2\core\Ajax_response;
use t2\core\Error;
use t2\core\service\Request;

abstract class Ajax {

	/**
	 * @param string  $cmd
	 * @param array[] $keyValues
	 * @return Ajax_response|false
	 */
	abstract public function return_by_cmd($cmd, $keyValues);

	/**
	 * @return Ajax_response
	 */
	public static function ajax_call_from_request(){
		$module = Request::value_unset("t2_module",false);
		if($module===false){
			new Error("AJAX_NO_MODULE","Module not set!", "Please specify t2_module in ajax query string (\"".$_SERVER['QUERY_STRING']."\")");
		}
		$cmd = Request::value_unset("t2_cmd",false);
		if($cmd===false){
			new Error("AJAX_NO_CMD","Command not set!", "Please specify t2_cmd in ajax query string (\"".$_SERVER['QUERY_STRING']."\")");
		}
		return self::ajax_call($module, $cmd, $_REQUEST);
	}

	private static function ajax_call($module, $cmd, $keyValues){
		/**
		 * @var Ajax $ajax
		 */
		$ajax = Service::get_api_class($module, "Ajax");
		return $ajax->return_by_cmd($cmd, $keyValues);
	}

	protected function unknown_command($cmd, $depth=0){
		new Error("AJAX_UNKNOWN_CMD","Unknown command \"$cmd\"! (Class ".get_class($this).")",null,$depth+1);
		return false;
	}

}