<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

/*
require_once ROOT_DIR . '/core/service/User.php';
 */

namespace service;

require_once ROOT_DIR . '/core/service/Login.php';

use t2\core\Error_fatal;

class User {

	private static $ID = false;
	public static $IS_ADMIN = false;

	public static function init(){
		self::$ID = Login::get_uid();
	}

	/**
	 * TODO: revert logic ($halt_on_error=false)
	 */
	public static function id($continue_on_error=false){
		if (self::$ID===false){
			if($continue_on_error){
				return false;
			}
			new Error_fatal(Error_fatal::TYPE_UNKNOWN, "Please init User first", 1, "( \service\User::init() )");
		}
		return self::$ID;
	}

}