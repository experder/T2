<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\mod;

use t2\api\Default_values;

class Core_values extends Default_values {

	protected $default_values = array(
		"MODULES" => '{
				"core_demo":{
					"custom_apis":{
						"Ajax":{
							"include":":ROOT_DIR\/dev\/demo\/Ajaxdemo_my.php",
							"class":"t2\\\\modules\\\\core_demo\\\\Ajaxdemo_my"
						},
						"Navigation":{
							"include":":ROOT_DIR\/dev\/demo\/My_Navigation.php",
							"class":"t2\\\\modules\\\\core_demo\\\\My_Navigation"
						}
					}
				}
				
			}',//TODO:remove dev_tools!     ,"dev_tools":{}
		//TODO: Navigation "class" is not necessary! it compiles to the default!
		"MODULE_ROOT"=>":ROOT_DIR/../modules",
		"DEFAULT_API_DIR"=>"tethys",
		"LOGIN_HTML"=>"<h1>Login</h1>",
		"PROJECT_TITLE"=>"T2",
		"SKIN"=>"bare",
		"EXTENSION"=>"php",
		"SESSION_EXPIRES"=>"86400",//24 hours
	);

}