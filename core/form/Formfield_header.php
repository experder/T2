<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


namespace t2\core\form;

class Formfield_header extends Formfield {

	public function __construct($value, $more_params = array()) {
		parent::__construct("header", "", $value, false, $more_params);
	}

	protected function toHtml() {
		return "<div" . $this->getParams_outer() . ">\n"
			. "\t<div class='formfield_inne2' ".$this->getParams_inner(false, false).">"
			.$this->value
			. "</div>\n"
			. "</div>";
	}

	public function inner_html() {
		return "???";//(never used)
	}

}
