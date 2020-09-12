<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\form;

class Formfield_header2 extends Formfield {

	public function __construct($value, $title="", $more_params = array()) {
		parent::__construct("header2", "", $value, false, $more_params);
		$this->setTitle($title);
	}

	public function setTitle($title = null){
		$this->title = $title;
	}

	protected function toHtml() {
		return "<div" . $this->getParams_outer() . ">\n"
			. ($this->title===null?"":"\t<label>$this->title</label>\n")
			. "\t".$this->inner_html()."\n"
			. "</div>";
	}

	public function inner_html() {
		return $this->value;
	}

}
