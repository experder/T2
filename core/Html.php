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

namespace core;

require_once ROOT_DIR . '/service/Html.php';


class Html {

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
	public function __construct($tag, $content, $params = null) {
		$this->tag = $tag;
		$this->content = $content;
		$this->setParams($params);
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
		foreach ($childs as $child) {
			$this->addChild($child);
		}
	}

	public function addClass($class) {
		if ($class === null) {
			return;
		}
		if (isset($this->params["class"])) {
			$this->params["class"] .= $class;
		} else {
			$this->params["class"] = $class;
		}
	}

	public function setParam($key, $value) {
		if ($value === null) {
			unset($this->params[strtolower($key)]);
		}
		$this->params[strtolower($key)] = $value;
	}

	public function setId($value) {
		$this->setParam("id", $value);
	}

	public function setParams($array) {
		if (!is_array($array)) {
			return;
		}
		foreach ($array as $key => $value) {
			$this->setParam($key, $value);
		}
	}

	public function __toString() {
		$params = \service\Html::tag_keyValues($this->params);
		return "<$this->tag$params>$this->content" . implode("", $this->children) . "</$this->tag>";
	}

}
