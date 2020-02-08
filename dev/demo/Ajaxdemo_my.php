<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\modules\core_demo;

require_once ROOT_DIR . '/dev/demo/Ajaxdemo_controller.php';

use t2\api\Ajax;
use t2\core\Ajax_response;
use t2\core\service\Arrays;

class Ajaxdemo_my extends Ajax {

	/**
	 * @param string   $cmd
	 * @param string[] $keyValues
	 * @return Ajax_response|false
	 */
	public function return_by_cmd($cmd, $keyValues) {
		switch ($cmd){
			case 'test1':
				new Ajax_response(Ajax_response::TYPE_HTML,Ajaxdemo_controller::calculate_test1(
					Arrays::value_from_array($keyValues, 'foo')
				));
				break;
			case 'test2':
				return Ajaxdemo_controller::calculate_test2(
					Arrays::value_from_array($keyValues, 'foo')
				);
				break;
			case 'test4':
				return Ajaxdemo_controller::calculate_test4(
					Arrays::value_from_array($keyValues, 'foo')
				);
				break;
		}
		return $this->unknown_command($cmd, 1);
	}

}
