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
 */
if (true) exit;/*
 * TPLDOCEND*/

if(!file_exists('config_server_exclude.php')){
	//TODO(1):Start Wizard
}
require_once 'config_server_exclude.php';

//Subpath of the T2 toolbox within your project:
define('HDDPATH_T2', ':t2_subdir');

//Set your own navigation:
#require_once HDDROOT_PROJECT.'/MyNavigation.php';
#\t2\Start::setNavigation(new MyNavigation());

//Set your own header and footer:
#require_once HDDROOT_PROJECT.'/MyHeader.php';
#\t2\core\Page::setHeader(new MyHeader());

//TODO(1): module_root, module_path, default_api