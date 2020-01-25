<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/
/*
//equire_once ROOT_DIR . '/dev/templates/module/Controller.php';
 */

namespace t2\modules\core_template;


class Controller {

	public static function calculate_test2($foo){
		$bar = "foo => $foo";
		//...
		return $bar."\n";
	}

}