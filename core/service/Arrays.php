<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/
/*
require_once ROOT_DIR . '/core/service/Arrays.php';
 */

namespace service;//TODO:check/reorg namespaces


class Arrays {

	public static function remove_from_array_by_value($array, $key){
		if (($key = array_search($key, $array)) !== false) {
			unset($array[$key]);
		}
		return $array;
	}

}