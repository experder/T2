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

	protected $_t2_table_name='mycore_user';//TODO(3): set table name with core prefix overriding getter-method get_table_name

	protected $id;
	protected $ref_id;
	protected $username;
	protected $pass_hash;
	protected $display_name;

}