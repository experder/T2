<?php

namespace t2\modules\core_template\api;//(:moduleIdLc)
/**TPLDOCSTART
 *
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 *
 * This template is used to create a new module.
 * @see \t2\dev\Tools::prompt_new_module()
TPLDOCEND*/

use t2\api\Ajax;
use t2\core\Ajax_response;
use t2\core\service\Arrays;
use t2\modules\core_template\Controller;

class My_Ajax extends Ajax {

	/**
	 * @param string   $cmd
	 * @param string[] $keyValues
	 * @return Ajax_response|false
	 */
	public function return_by_cmd($cmd, $keyValues) {
		switch ($cmd) {
			case 'cmd1':
				return new Ajax_response(Ajax_response::TYPE_HTML,
					Controller::calculate_whatever(
						Arrays::value_from_array($keyValues, 'foo')
					)
				);
				break;
		}
		return $this->unknown_command($cmd, 1);
	}

}
