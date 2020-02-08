<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\modules\core_demo;

use t2\core\Ajax_response;

class Ajaxdemo_controller {

	public static function calculate_test1($foo){
		$bar = "got: \$foo=$foo";
		return $bar."\n";
	}

	public static function calculate_test2($foo){
		$bar = "got also: \$foo=$foo";
		return new Ajax_response(Ajax_response::TYPE_HTML,$bar."\n");
	}

	public static function calculate_test4($foo){
		$bar = $foo;
		$data = array("bar"=>$bar);
		return new Ajax_response(Ajax_response::TYPE_JSON,$data);
	}

}