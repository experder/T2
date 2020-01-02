<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

/*
require_once ROOT_DIR . '/service/User.php';
 */

namespace service;

require_once ROOT_DIR . '/service/Login.php';

use core\Error;

class User {

	private static $ID = false;
	public static $IS_ADMIN = false;

	public static function init(){
		self::$ID = Login::get_uid();
	}

	public static function id(){
		if (self::$ID===false){
			Error::quit("UID not set.", 1);
		}
		return self::$ID;
	}

}