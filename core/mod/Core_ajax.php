<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\mod;

use t2\admin\Admin;
use t2\api\Ajax;
use t2\core\Ajax_response;

class Core_ajax extends Ajax {

	/**
	 * @param string   $cmd
	 * @param array[] $keyValues
	 * @return Ajax_response|false
	 */
	public function return_by_cmd($cmd, $keyValues) {
		switch ($cmd){
			case 'update_shell':
				return new Ajax_response(Ajax_response::TYPE_HTML,Admin::update_shell());
				break;
			case 'update_db':
				return new Ajax_response(Ajax_response::TYPE_HTML,Admin::update_dbase());
				break;
			case 'update_includes':
				return new Ajax_response(Ajax_response::TYPE_HTML,Admin::update_includes());
				break;
		}
		return $this->unknown_command($cmd, 1);
	}

}