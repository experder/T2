<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\service;

class Config_core {
//TODO(1): Class config_core with getters for every cfg-value
	public static function skin_dir() {
		$skin_dir = Config::get_value_core("SKIN");
		if ($skin_dir == 'bare') {
			$root = Config::get_value_core('HTTP_ROOT');
			$skin_dir = "$root/skins/$skin_dir";
		}
		return $skin_dir;
	}

}