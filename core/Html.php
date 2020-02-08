<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


namespace t2\core;


use t2\core\service\Config;
use t2\core\service\Includes;
use t2\core\service\Strings;

class Html {

	protected $tag;
	private $content;
	private $void;
	protected $params;
	/**
	 * @var Html[] $children
	 */
	private $children = array();

	private static $extension = null;

	/**
	 * Html constructor.
	 * @param string     $tag e.g. DIV, P, A, BUTTON
	 * @param string     $content
	 * @param array|null $params Key-Value pairs of HTML-Attributes
	 * @param mixed      $children
	 * @param boolean    $void
	 */
	public function __construct($tag, $content, $params = null, $children=null, $void=false) {
		$this->tag = $tag;
		$this->content = $content;
		$this->void= $void;
		$this->addParams($params);
		if($children!==null){
			self::addChildren($children);
		}
	}

	/**
	 * @param Html|string $child
	 */
	public function addChild($child) {
		$this->children[] = $child;
	}

	/**
	 * @param array $childs
	 */
	public function addChildren($childs) {
		if(!is_array($childs)){
			/** @noinspection PhpParamsInspection */
			self::addChild($childs);
			return;
		}
		foreach ($childs as $child) {
			$this->addChild($child);
		}
	}

	public function addClasses($classes) {
		if(is_array($classes)){
			foreach ($classes as $class){
				$this->addClass($class);
			}
		}
	}

	public function addClass($class) {//TODO(2): Check, if class already exists
		if ($class === null) {
			return;
		}
		if (isset($this->params["class"])) {
			$this->params["class"] .= ' '.$class;
		} else {
			$this->params["class"] = $class;
		}
	}

	public function set_param($key, $value) {
		$key = strtolower($key);
		if ($value === null) {
			unset($this->params[$key]);
		}
		$this->params[$key] = $value;
	}

	public function set_id($value) {
		$this->set_param("id", $value);
	}

	/**
	 * @return string
	 */
	public function get_tag() {
		return $this->tag;
	}

	public function addParams($array) {
		if (!is_array($array)) {
			return;
		}
		foreach ($array as $key => $value) {
			$this->set_param($key, $value);
		}
	}

	public function __toString() {
		$params = self::tag_keyValues($this->params);
		$html = "<$this->tag$params";
		if($this->void){
			$html.=" />".implode("", $this->children);
		}else{
			$html.=">".$this->content . implode("", $this->children) . "</$this->tag>";
		}
		return $html;
	}

	//================================== STATIC's ====================================================

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

	public static function A($content, $href, $class = null, $params = array()) {
		$params["href"] = $href;
		$params["class"] = $class;
		return new Html("a", $content, $params);
	}

	public static function DIV($content, $class = null, $params = array()) {
		$params["class"] = $class;
		return new Html("div", $content, $params);
	}

	public static function H1($content, $id = null) {
		return new Html("h1", $content, array("id" => $id));
	}

	public static function H2($content, $id = null) {
		return new Html("h2", $content, array("id" => $id));
	}

	public static function H3($content, $id = null) {
		return new Html("h3", $content, array("id" => $id));
	}

	public static function H4($content, $id = null) {
		return new Html("h4", $content, array("id" => $id));
	}

	public static function BUTTON($value, $js = null, $params = array()) {
		$params["type"] = "button";
		if ($js) {
			$params["onclick"] = $js;
		}
		return new Html("button", $value, $params);
	}

	public static function PRE($content, $classes = array(), $params = array()) {
		$params["class"] = implode(" ", $classes);
		return new Html("pre", $content, $params);
	}

	public static function PRE_code($content, $params = array(), $highlight=true) {
		if($highlight){
			Includes::js_highlight9181();
		}
		$code = new Html("code", $content, $params);
		return new Html("pre", null, null, $code);
	}

	public static function PRE_console($content, $id = null, $outer_id=null) {
		$params = array("class" => "console_inner");
		if ($id) {
			$params["id"] = $id;
		}
		$outer_params = array();
		if($outer_id){
			$outer_params["id"] = $outer_id;
		}
		return self::PRE(new Html("div", $content, $params), array("console"), $outer_params);
	}

	public static function TEXTAREA_console($content, $id = null) {
		$params = array("class" => "console");
		if ($id) {
			$params["id"] = $id;
		}
		return new Html("textarea", $content, $params);
	}

	public static function UL($children = array(), $params = null) {
		return self::list_builder("ul", $children, $params);
	}

	private static function list_builder($elem, $children, $params) {
		$html = new Html($elem, "", $params);
		foreach ($children as $child) {
			if (!($child instanceof Html) || strtolower($child->get_tag()) != 'li') {
				$child = new Html("li", null, null, $child);
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
		return new Html("p", $content, $params, $children);
	}

	public static function A_external($content, $href, $params = array()) {
		$params['href'] = $href;
		$params['target'] = '_blank';
		$html = new Html("a", $content, $params);
		return $html;
	}

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
