<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 T2 contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core;

class DB {
	//TODO(2):Move more functions here

	public static function select($query, $substitutions = array(), $backtrace_depth = 0, $halt_on_error = true) {
		return Database::getInstance()->select($query, $substitutions, $backtrace_depth + 1, $halt_on_error);
	}

}