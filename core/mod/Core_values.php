<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\mod;


use t2\api\Default_values;

#echo "<pre>".Debug::backtrace()."</pre>";

class Core_values extends Default_values {

	protected $default_values = array(
		"MODULES" => '{
				"core_template":{
					"Default_values":{
						"include":":ROOT_DIR\/dev\/templates\/module\/tethys\/Default_values.php",
						"class":"\\\t2\\\modules\\\core_template\\\api\\\Core_values"
					},
					"Ajax":{
						"include":":ROOT_DIR\/dev\/templates\/module\/tethys\/Ajax.php",
						"class":"t2\\\modules\\\core_template\\\api\\\Ajax"
					}
				}
			}',
		"LOGIN_H1"=>"Login",
		"PROJECT_TITLE"=>"T2",
		"SKIN"=>"bare",
		"EXTENSION"=>"php",
		"SESSION_EXPIRES"=>"86400",//24 hours
	);

}