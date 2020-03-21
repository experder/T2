<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\mod;

use t2\core\DBTable;

class D_User extends DBTable {

	protected $id;
	protected $ref_id;
	protected $username;
	protected $pass_hash;
	protected $display_name;

	public function get_table_name($depth = 0) {
		$core_user = DB_CORE_PREFIX . '_user';
		return $core_user;
	}

}