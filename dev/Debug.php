<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/
/*
require_once ROOT_DIR . '/dev/Debug.php';
 */

namespace t2\dev;


class Debug {

	/**
	 * @param mixed $val
	 * TODO: Move dev_stats to here. Show outputs in dev stats (header: "file:line")
	 */
	public static function out($val){
		echo "<pre>".print_r($val, 1)."</pre>";
	}

}