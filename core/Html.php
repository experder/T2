<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


/*
require_once ROOT_DIR . '/core/Html.php';
 */

namespace t2\core;

require_once ROOT_DIR . '/core/service/Html.php';
require_once ROOT_DIR . '/core/Echoable.php';


class Html implements Echoable {

	protected $tag;
	private $content;
	protected $params;
	/**
	 * @var Html[] $children
	 */
	private $children = array();

	/**
	 * Html constructor.
	 * @param string     $tag e.g. DIV, P, A, BUTTON
	 * @param string     $content
	 * @param array|null $params Key-Value pairs of HTML-Attributes
	 */
	public function __construct($tag, $content, $params = null, $children=null) {
		$this->tag = $tag;
		$this->content = $content;
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
		foreach ($classes as $class){
			$this->addClass($class);
		}
	}

	public function addClass($class) {//TODO: Check, if class already exists
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
		$params = \service\Html::tag_keyValues($this->params);
		return "<$this->tag$params>$this->content" . implode("", $this->children) . "</$this->tag>";
	}

}
