<?php

/*
require_once ROOT_DIR.'/service/Html.php';
 */

namespace service;

require_once ROOT_DIR.'/service/Strings.php';


class Html {

	/**
	 * Creates key-value pairs as used by HTML tags.
	 * @param array $params
	 * @return string
	 */
	public static function tag_keyValues($params) {
		if(!is_array($params)){
			return "";
		}
		$html = "";
		foreach ($params as $key => $value) {
			$html .= " $key='" . Strings::escape_value_html($value) . "'";
		}
		return $html;
	}

}