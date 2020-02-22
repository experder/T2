<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/
/**TPLDOCSTART
 * This template is used by the installer wizard (\t2\dev\Install_wizard::init_config)
 * to create the server specific configuration file "config_server_exclude.php"
 * which is called from \t2\Start::init_config
 * @see \t2\dev\Install_wizard::init_config
 * @see \t2\Start::init_config
 */if(true)exit;/*
 * TPLDOCEND*/

//TODO: Wizard to create this file if it doesn't exist

//Server-specific configuration
//TODO:Server-specific configuration

//Set to TRUE in your development environment, FALSE (default) for production:
#\t2\core\service\Config::$DEVMODE = true;    TODO: Prompt for devmode in installer
#\t2\core\Html::setDevBeautify();

//Where to find your project:
define('PROJECT_ROOT', ":project_root");

//TODO: T2 is a SUBmodule, so the first root is the parent project's root
define('HDDROOT_PROJECT', ':parent_root');
//TODO: The directory pointing to T2 is PROJECT SPECIFIC relative to this root. See HDDPATH_T2 in config.php


//Initialize database connection:
\t2\core\Database::init(':server_addr', ':tethysdb', ':username', ':dbpass');

//TODO: extension, http_root, platform

