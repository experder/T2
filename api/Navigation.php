<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\api;

class Navigation {

	/**
	 * @var Navigation $parent
	 */
	private $parent;
	/**
	 * @var Navigation[] $children
	 */
	private $children = array();
	/**
	 * @var string $id
	 */
	private $id;
	/**
	 * @var string $label
	 */
	private $label = null;
	/**
	 * @var string $title
	 */
	private $title = null;
	/**
	 * @var string $link
	 */
	private $link = null;
	/**
	 * @var string $icon
	 */
	private $icon = null;

	/**
	 * Navigation constructor.
	 * @param Navigation  $parent
	 * @param string      $id
	 */
	public function __construct(Navigation $parent, $id) {
		$this->parent = $parent;
		$this->id = $id;
	}

}
