<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/
/**TPLDOCSTART
 * This template is used by the installer wizard to create the configuration file "config_exclude.php".
 * @see \service\Install_wizard::prompt_dbParams()
 * TPLDOCEND*/

//As long as we don't have a first stylesheet:
define("POST_CSS", false);

//You might want to store this file in your project's repository:
#require_once ROOT_DIR . "/../config.php";

//Put this database connection in your project's config file:
\core\Database::init(':server_addr', ':tethysdb', ':username', ':dbpass');
