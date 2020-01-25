<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


namespace t2\modules\core_template\api;

//equire_once ROOT_DIR . '/dev/templates/module/Controller.php';
//equire_once ROOT_DIR . '/api/Ajax.php';

use t2\core\service\Arrays;
use t2\modules\core_template\Controller;

class Ajax extends \t2\api\Ajax {

	/**
	 * @param string   $cmd
	 * @param string[] $keyValues
	 * @return string JSON or HTML, depending on $cmd
	 */
	public function return_by_cmd($cmd, $keyValues) {
		switch ($cmd){
			case 'test2':
				return Controller::calculate_test2(
					Arrays::value_from_array($keyValues, 'foo')
				);
				break;
		}
		$this->unknown_command($cmd, 1);
	}

}
