<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/
/**TPLDOCSTART
 * This template is used by the installer wizard (\t2\dev\Install_wizard::init_config)
 * to create the configuration redirection file "config.php"
 * which is called from \t2\Start::init_config
 * @see \t2\dev\Install_wizard::init_config
 * @see \t2\Start::init_config
 */
if (true) exit;/*
 * TPLDOCEND*/

//Project configuration file:
global $t2_config_file;
$t2_config_file = ":project_root/config.php";
if (!file_exists($t2_config_file)) {
	new \t2\core\Error("CFG_FILE_NOT_FOUND", "Configuration file (\"$t2_config_file\") not found!");
}
/** @noinspection PhpIncludeInspection */
require_once $t2_config_file;
