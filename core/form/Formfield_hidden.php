<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


namespace t2\core;

require_once ROOT_DIR . '/core/form/Formfield.php';


class Formfield_hidden extends Formfield {

	public function __construct($name, $value) {
		parent::__construct($name, null, $value, false);
	}

	public function toHtml() {
		return "<input type='hidden'" . $this->getParams_inner() . " />";
	}

	/** Not in use. */
	protected function inner_html() {
		new Error_("Should never be called");
		return "NOT IN USE";
	}
}
