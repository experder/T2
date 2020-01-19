<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


namespace t2\core;


class Formfield_checkbox extends Formfield {

	public function __construct($name, $title = null, $value = null, $val_from_request = true, $more_params = array()) {
		parent::__construct($name, $title, $value, $val_from_request, $more_params);
	}

	public function inner_html() {
		return "<input type='checkbox'".($this->value?" checked":"") . $this->getParams_inner() . " />";
	}

}
