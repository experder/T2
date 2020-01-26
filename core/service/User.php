<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


namespace t2\core\service;


use t2\core\Error_;

class User {

	private static $ID = false;
	private static $all_info = null;
	public static $IS_ADMIN = false;

	public static function init(){
		self::$all_info = Login::get_user();
		self::$ID = self::$all_info['id'];
	}

	/**
	 * Examples:
	 * $uid_or_zero = User::id_()?:"0";
	 * $uid_or_error = User::id_(true);
	 *
	 * @param bool $halt_on_error
	 * @return int|false
	 */
	public static function id_($halt_on_error=false){
		if (self::$ID===false){
			if($halt_on_error){
				new Error_("Please init User first", "ERROR_USER_INIT", "( \\service\\User::init() )", 1);
			}
			return false;
		}
		return self::$ID;
	}

	/**
	 * @return array|null
	 */
	public static function info(){
		return self::$all_info;
	}

}