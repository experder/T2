<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/
/**TPLDOCSTART
 * This template is used by the installer wizard to create the configuration file "config_exclude.php".
 * @see \admin\Install_wizard::init_config()
 * TPLDOCEND*/

//You might want to store this file in your project's repository instead:
#require_once ROOT_DIR . "/../config_exclude.php";

//Set to TRUE in your development environment, FALSE (default) for production:
#\service\Config::$DEVMODE = true;

//Initialize database connection:
\t2\core\Database::init(':server_addr', ':tethysdb', ':username', ':dbpass');
