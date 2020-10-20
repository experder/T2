<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core;

class DB {

	public static function select($query, $substitutions = array(), $backtrace_depth = 0, $halt_on_error = true) {
		return Database::get_singleton()->select($query, $substitutions, $backtrace_depth + 1, $halt_on_error);
	}

	public static function select_indexed($index_key, $query, $substitutions = array()) {
		return Database::get_singleton()->select_indexed($index_key, $query, $substitutions);
	}

	public static function select_single($query, $substitutions = null, $ignore_following = true) {
		return Database::get_singleton()->select_single($query, $substitutions, 0, $ignore_following);
	}

	public static function insert_assoc($tabelle, $data_set) {
		return Database::get_singleton()->insert_assoc3($tabelle, $data_set);
	}

	public static function delete($query, $substitutions = array()) {
		return Database::get_singleton()->delete($query, $substitutions);
	}

}