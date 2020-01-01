<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


/*
require_once ROOT_DIR . '/service/Html.php';
 */

namespace service;

require_once ROOT_DIR . '/service/Strings.php';
require_once ROOT_DIR . '/core/Html.php';

class Html {

	/**
	 * Creates key-value pairs as used by HTML tags.
	 * @param array $params
	 * @return string
	 */
	public static function tag_keyValues($params) {
		if (!is_array($params)) {
			return "";
		}
		$html = "";
		foreach ($params as $key => $value) {
			$html .= " $key='" . Strings::escape_value_html($value) . "'";
		}
		return $html;
	}

	public static function A($content, $href=null, $class=null){
		return new \core\Html("a",$content,array("href"=>$href,"class"=>$class));
	}

	public static function A_button($content, $href=null, $classes=array()){
		$html = new \core\Html("a",$content,array("href"=>$href,"class"=>"abutton"));
		$html->addClasses($classes);
		return $html;
	}

	public static function href_internal($relative_page_without_extension){
		return HTTP_ROOT . '/' . $relative_page_without_extension .'.'.EXT;
	}

}
