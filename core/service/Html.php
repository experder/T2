<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\service;

/**
 * TODO(1):Move service\Html to t2\core\Html
 */
class Html {

	/**
	 * Creates key-value pairs as used by HTML tags.
	 * @param array $params
	 * @return string
	 */
	public static function tag_keyValues($params) {
		//equire_once ROOT_DIR . '/core/service/Strings.php';
		if (!is_array($params)) {
			return "";
		}
		$html = "";
		foreach ($params as $key => $value) {
			$html .= " $key='" . Strings::escape_value_html($value) . "'";
		}
		return $html;
	}

	public static function A($content, $href, $class = null, $params = array()) {
		$params["href"] = $href;
		$params["class"] = $class;
		return new \t2\core\Html("a", $content, $params);
	}

	public static function DIV($content, $class = null, $params = array()) {
		$params["class"] = $class;
		return new \t2\core\Html("div", $content, $params);
	}

	public static function H1($content, $id = null) {
		return new \t2\core\Html("h1", $content, array("id" => $id));
	}

	public static function H2($content, $id = null) {
		return new \t2\core\Html("h2", $content, array("id" => $id));
	}

	public static function H3($content, $id = null) {
		return new \t2\core\Html("h3", $content, array("id" => $id));
	}

	public static function H4($content, $id = null) {
		return new \t2\core\Html("h4", $content, array("id" => $id));
	}

	public static function BUTTON($value, $js = null, $params = array()) {
		$params["type"] = "button";
		if ($js) {
			$params["onclick"] = $js;
		}
		return new \t2\core\Html("button", $value, $params);
	}

	public static function PRE($content, $classes = array(), $params = array()) {
		$params["class"] = implode(" ", $classes);
		return new \t2\core\Html("pre", $content, $params);
	}

	public static function PRE_console($content, $id = null) {
		$params = array("class" => "console_inner");
		if ($id) {
			$params["id"] = $id;
		}
		return self::PRE(new \t2\core\Html("div", $content, $params), array("console"));
	}

	public static function TEXTAREA_console($content, $id = null) {
		$params = array("class" => "console");
		if ($id) {
			$params["id"] = $id;
		}
		return new \t2\core\Html("textarea", $content, $params);
	}

	public static function UL($children = array(), $params = null) {
		return self::list_builder("ul", $children, $params);
	}

	private static function list_builder($elem, $children, $params) {
		$html = new \t2\core\Html($elem, "", $params);
		foreach ($children as $child) {
			if (!($child instanceof \t2\core\Html) || strtolower($child->get_tag()) != 'li') {
				$child = new \t2\core\Html("li", null, null, $child);
			}
			$html->addChild($child);
		}
		return $html;
	}

	public static function A_button($content, $href, $classes = array(), $params = array()) {
		$html = self::A($content, $href, "abutton", $params);
		$html->addClasses($classes);
		return $html;
	}

	public static function P($content, $children = null, $params = array()) {
		return new \t2\core\Html("p", $content, $params, $children);
	}

	public static function A_external($content, $href, $params = array()) {
		$params['href'] = $href;
		$params['target'] = '_blank';
		$html = new \t2\core\Html("a", $content, $params);
		return $html;
	}

	private static $extension = null;

	public static function href_internal($relative_page_without_extension) {
		return Config::get_value_core('HTTP_ROOT') . '/' . self::href_internal_relative($relative_page_without_extension);
	}

	public static function href_internal_relative($relative_page_without_extension) {
		if (self::$extension === null) {
			self::$extension = Config::get_value_core("EXTENSION");
		}
		return $relative_page_without_extension . '.' . self::$extension;
	}

}
