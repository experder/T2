<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\service;

class Php7 {

	public static function random_bytes($length) {
		$string = "";
		for ($i = 0; $i < $length; $i++) {
			$string .= chr(rand(0, 255));
		}
		return $string;
	}

}