<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\mod;

use t2\api\Navigation;
use t2\api\Service;
use t2\core\Html;
use t2\core\Page;
use t2\core\service\Config;
use t2\dev\Tools;

class Core_navigation {

	public static function navi_default($id=null){
		$subnavis=array(
			new Navigation("PAGEID_CORE_INDEX", "Start", Html::href_internal_root("index")),
			self::navi_user(),
			self::navi_admin(),
		);
		foreach (self::all_modules() as $navi){
			$subnavis[] = $navi;
		}
		return new Navigation($id,null,null,$subnavis);
	}

	/**
	 * @return Navigation[]
	 */
	public static function all_modules(){
		$navis = array();
		$modules = Config::get_modules_ids();
		foreach ($modules as $mod_id){
			$navi = self::navi_by_module($mod_id);
			if($navi){
				$navis[] = $navi;
			}
		}
		return $navis;
	}

	public static function navi_by_module($mod_id) {
		$navi = Service::get_api_class($mod_id, 'Navigation', $error, $return);
		if(!$navi){
			if($error==Service::API_ERROR_FILE_NOT_FOUND){
				if(Config::$DEVMODE){
					if(isset($_REQUEST['initialize_module_navi'])){
						$msg = Tools::create_new_module($mod_id, $mod_id, dirname($return), array("My_Navigation.php"));
						Page::$compiler_messages[] = $msg;
					}else{
						Page::get_singleton()->add_message_error(Html::DIV("No Navigation set for module '$mod_id'! [<a href='?initialize_module_navi'>Create blank navigation</a>]","dev"));
					}
				}
				return false;
			}
		}
		return $navi;
	}

	public static function navi_user(){
		return new Navigation('NAVI_USER',"",Html::href_internal_root("index"),array(
			new Navigation('CORE_USER_SETTINGS',"",Html::href_internal_root("index")),
			new Navigation('CORE_USER_LOGOUT',"",Html::href_internal_root("index")),
		));
	}

	public static function navi_admin(){
		return new Navigation('NAVI_ADMIN',"","",array(
			new Navigation('PAGEID_CORE_ADMIN',"",Html::href_internal_root("index")
				/*TODO:Just for demonstration:*/,array(new Navigation('A',"",Html::href_internal_root("index"),array(new Navigation('A1',"",""),new Navigation('A2',"",Html::href_internal_root("index")))),new Navigation('B',"",Html::href_internal_root("index")))
			),
			new Navigation('PAGEID_CORE_DEVZONE',"",Html::href_internal_root("index")),
		));
	}

}