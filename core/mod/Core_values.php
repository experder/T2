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
		//TODO(1):move to custom_apis to module specific config
		"MODULES" => '{
				"core_demo":{
					"custom_apis":{
						"Ajax":{
							"include":":ROOT_DIR\/dev\/demo\/Ajaxdemo_my.php",
							"class":"t2\\\\modules\\\\core_demo\\\\Ajaxdemo_my"
						},
						"Navigation":{
							"include":":ROOT_DIR\/dev\/demo\/My_Navigation.php"
						}
					}
				}
			}',
		/**
		 * @see Start::init_database()
		 * @see Admin::get_config_form()
		 */
		"EXTENSION" => "php",
		"PROJECT_TITLE" => "T2",
		"SKIN" => "bare",
		"SESSION_EXPIRES" => "86400",//24 hours
		/**
		 * @see Admin::get_config_form()
		 */
		"MODULE_ROOT" => ":ROOT_DIR/../modules",
		"MODULE_PATH" => ":HTTP_ROOT/../modules",
		"DEFAULT_API_DIR" => "tethys",
		"LOGIN_HTML" => "<h1>Login</h1>",
		"fixedheaderoffset" => "0",//px
	);

}