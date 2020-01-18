<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/
/*
require_once ROOT_DIR . '/dev/api/Ajax.php';
 */

namespace t2\api;

require_once ROOT_DIR . '/core/service/Request.php';
require_once ROOT_DIR . '/dev/api/Service.php';

use service\Request;
use t2\core\Error_;

abstract class Ajax {

	/**
	 * @param string $cmd
	 * @param string[] $keyValues
	 * @return string JSON or HTML, depending on $cmd
	 */
	abstract public function return_by_cmd($cmd, $keyValues);

	public static function ajax_call_from_request(){
		$module = Request::value_unset("module",false);
		if($module===false){
			new Error_("ERROR_NO_MODULE");
		}
		$cmd = Request::value_unset("cmd",false);
		if($cmd===false){
			new Error_("ERROR_NO_CMD");
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

}