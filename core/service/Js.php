<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\service;

use t2\core\Html;
use t2\core\Page;

class Js {

	public static function jquery_onload($content){
		Page::get_singleton()->add_js_jquery341();
		return "$(function(){{$content}});";
	}

	public static function ajax_to_id($module, $cmd, $keyVals, $id, $add=false, $function=null){
		Page::get_singleton()->add_js_core();
		$keyVals["module"]=$module;
		$keyVals["cmd"]=$cmd;
		$query = Strings::build_query_string($keyVals);
		/*
		 * TODO(2): AJAX: URL can get too long!
		 */
		return "t2_ajax_to_id('".Html::href_internal('core/ajax').$query."','$id',".($add?'true':'false').",".($function?"'$function'":'false').");";
	}

	public static function run_later($code,$delay_seconds,$repeat=false){
		$delay=$delay_seconds*1000;
		if ($repeat){
			return "setInterval(function(){{$code}},$delay);";
		}else{
			return "setTimeout(function(){{$code}},$delay);";
		}
	}

	public static function ajax_post($module, $cmd, $data, $function, $callback=true){
		Page::get_singleton()->add_js_core();

		$url = Html::href_internal('core/ajax');
		$url.="?t2_module=".$module;
		$url.="&t2_cmd=".$cmd;

		$Funk = "function( data ){ $function }";

		return "t2_ajax_post('$url', $Funk, $data, ".(Config::$DEVMODE?'true':'false').", ".($callback?'true':'false').");";
	}

}
