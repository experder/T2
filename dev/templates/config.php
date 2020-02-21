<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/
/**TPLDOCSTART
 * This template is used by the installer wizard (\t2\dev\Install_wizard::init_config)
 * to create the configuration file "config.php"
 * which is called from \t2\Start::init_config (maybe via redirection)
 * @see \t2\dev\Install_wizard::init_config
 * @see \t2\Start::init_config
 * TPLDOCEND*/

//Set to TRUE in your development environment, FALSE (default) for production:
#\t2\core\service\Config::$DEVMODE = true;    TODO: Prompt for devmode in installer
#\t2\core\Html::setDevBeautify();

//Where to find your project:
define('PROJECT_ROOT', ":project_root");

//Initialize database connection:
\t2\core\Database::init(':server_addr', ':tethysdb', ':username', ':dbpass');

//Set your own navigation:
#require_once '/var/www/myproject/MyNavigation.php';
#\t2\Start::setNavigation(new MyNavigation());

//Set your own header and footer:
#require_once '/var/www/myproject/MyHeader.php';
#\t2\core\Page::setHeader(new MyHeader());
