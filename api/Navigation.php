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
	 * @var Navigation[] $children
	 */
	private $children;

	/**
	 * @var Navigation|null $parent
	 */
	private $parent = null;

	/**
	 * Navigation constructor.
	 * @param string       $id
	 * @param Navigation[] $children
	 */
	public function __construct($id, $children = array()) {
		$this->id = $id;
		$this->addChildren($children);
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
		foreach ($children as $navi){
			$navi->parent = $this;
			$this->children[] = $navi;
		}
	}

	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	public function toHtml(){
		if($this->id){
			$html = new Html("div", $this->id);
		}else{
			$html="";
		}
		$children = $this->getChildren();
		if($children){
			$children_html=array();
			foreach ($children as $subnavi){
				$children_html[]=$subnavi->toHtml();
			}
			$html.=Html::UL($children_html);
		}
		return $html;
	}

}
