<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\api;

use t2\core\Html;

class Navigation {

	/**
	 * @var string $id
	 */
	private $id;

	/**
	 * @var string $link
	 */
	private $link;

	/**
	 * @var string $title
	 */
	private $title;

	/**
	 * @var Navigation[] $children
	 */
	protected $children;

	/**
	 * @var Navigation|null $parent
	 */
	private $parent = null;

	private $highlight = false;
	protected $visible = true;
	private $external = false;

	/**
	 * Navigation constructor.
	 * @param string       $id
	 * @param string       $link
	 * @param string       $title
	 * @param Navigation[] $children
	 */
	public function __construct($id, $title, $link, $children = null) {
		$this->id = $id;
		$this->link = $link;
		$this->title = $title;
		$this->addChildren($children);
	}

	public function set_invisible() {
		$this->visible = false;
	}

	public function set_external() {
		$this->external = true;
	}

	/**
	 * @return Navigation[]
	 */
	public function getChildren() {
		return $this->children;
	}

	/**
	 * @param Navigation[] $children
	 */
	public function addChildren($children) {
		if (!$children) {
			return;
		}
		foreach ($children as $navi) {
			$navi->parent = $this;
			$this->children[] = $navi;
		}
	}

	public function do_highlight($highlight_id) {
		if ($this->id === $highlight_id) {
			$this->highlight = true;
		}
		$children = $this->getChildren();
		if ($children) {
			foreach ($children as $child) {
				$this->highlight = $child->do_highlight($highlight_id) || $this->highlight;
			}
		}
		return $this->highlight;
	}

	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	public function toHtml($highlight_id = null) {
		if ($highlight_id) {
			$this->do_highlight($highlight_id);
		}
		if (!$this->visible) {
			return "";
		}
		if ($this->id) {
			$classes = array();
			if ($this->highlight) {
				$classes[] = "high";
			}
			if (!$this->link) {
				$classes[] = "nolink";
			}
			$params = $classes ? array("class" => implode(" ", $classes)) : null;
			$label = $this->title ?: $this->id;
			$link_params = $this->external ? array("target" => "_blank") : array();
			$item = $this->link ? Html::A($label, $this->link, null, $link_params) : $label;
			$html = new Html("div", $item, $params);
		} else {
			$html = "";
		}
		$children = $this->getChildren();
		if ($children) {
			$children_html = array();
			foreach ($children as $subnavi) {
				$children_html[] = new Html('li', $subnavi->toHtml(), array("class" => "nav_" . $subnavi->id));
			}
			$html .= Html::UL($children_html, null);
		}
		return $html;
	}

	public function getTitle($id) {
		if($this->id===$id){
			return $this->title;
		}
		if($children=$this->getChildren()){
			foreach ($children as $navi){
				$title = $navi->getTitle($id);
				if($title!==false){
					return $title;
				}
			}
		}
		return false;
	}

}
