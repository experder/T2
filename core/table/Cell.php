<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\table;

use t2\core\Html;

class Cell {

	public $tag = 'td';
	private $data;

	/**
	 * Cell constructor.
	 * @param string $data
	 */
	public function __construct($data) {
		$this->data = $data;
	}

	public function __toString() {
		$html = new Html($this->tag, $this->data);
		return $html->__toString();
	}

}