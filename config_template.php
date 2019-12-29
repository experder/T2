<?php
/**TPLDOCSTART
 * This template is used by the installer wizard to create the configuration file "config_exclude.php".
 * @see \service\Install_wizard::prompt_dbParams()
 * TPLDOCEND*/

//As long as we don't have a first stylesheet:
define("POST_CSS",false);

//You might want to store this file in your project's repository:
#require_once ROOT_DIR."/../config.php";

//Put this database connection in your project's config file:
\core\Database::init(':server_addr', ':tethysdb', ':username', ':dbpass');
