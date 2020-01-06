<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


namespace t2\core;

require_once ROOT_DIR.'/api/Default_values.php';


class Default_values extends \t2\api\Default_values {

	protected $default_values = array(
		"MODULES" => '{
				"core":{
					"Default_values":{
						"include":":ROOT_DIR\/templates\/Default_values.php",
						"class":"\\\t2\\\core\\\Default_values"
					}
				}
			}',
		"LOGIN_H1"=>"Login",
	);

}