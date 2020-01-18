<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/
/*
require_once ROOT_DIR . '/core/api/Core_ajax.php';
 */

namespace t2\core\api;


use t2\api\Ajax;

class Core_ajax extends Ajax {

	/**
	 * @param string   $cmd
	 * @param string[] $keyValues
	 * @return string JSON or HTML, depending on $cmd
	 */
	public function return_by_cmd($cmd, $keyValues) {
		return "CMD=$cmd / ".print_r($keyValues, 1);
	}

}