<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\service;

class Arrays {

	public static function remove_from_array_by_value($array, $key){
		if (($key = array_search($key, $array)) !== false) {
			unset($array[$key]);
		}
		return $array;
	}

	public static function value_from_array($array, $key, $default_value=null){
		if(isset($array[$key])){
			return $array[$key];
		}
		return $default_value;
	}

	public static function prefix_values($prefix, $values, $suffix = '') {
		$resulting = array();
		foreach ($values as $val) {
			$resulting[] = $prefix . $val . $suffix;
		}
		return $resulting;
	}

//	public static function prefix_keys($prefix, $array, $suffix = '') {
//		$resulting = array();
//		foreach ($values as $val) {
//
//		}
//		return $resulting;
//	}

}