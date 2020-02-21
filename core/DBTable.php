<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core;

use t2\core\service\Config;

class DBTable {

	protected $_t2_table_name = null;

	public function __construct($data_array = null, $depth = 0) {
		if (is_array($data_array)) {
			$this->set_data($data_array, $depth + 1);
		}
	}

	public function set_data($data_array, $depth = 0) {
		$all_fields = $this->get_object_vars();
		if (is_array($data_array)) {
			foreach ($data_array as $key => $value) {
				if (!array_key_exists($key, $all_fields)) {
					if (Config::$DEVMODE) {
						new Warning("DBT_UNDEFINED", "Skipped key '$key' when setting data for: " . get_class($this), null, $depth + 1);
					}
				} else {
					$this->$key = $value;
				}
			}
		}
	}

	public function get_table_name($depth = 0) {
		if ($this->_t2_table_name === null) {
			new Error('DBT_NONAMESET', "Table name not set for: " . get_class($this), null, $depth + 1);
		}
		return $this->_t2_table_name;
	}

	public function get_object_vars() {
		$all_fields = get_object_vars($this);
		unset($all_fields['_t2_table_name']);
		return $all_fields;
	}

	public function q_insert_all($depth = 0) {
		Database::insert_assoc2($this->get_table_name($depth + 1), $this->get_object_vars());
	}

}