<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\form;

class Formfield_select extends Formfield {

	private $options;

	public function __construct($name, $title = null, $options = null, $selected_index = null, $val_from_request = true, $more_params = array()) {
		parent::__construct($name, $title, $selected_index, $val_from_request, $more_params);
		$this->options = $options;
	}

	public function inner_html() {
		$options = array();
		if(!is_array($this->options) || count($this->options)==0){
			return "(Keine Optionen)";
		}
		foreach ($this->options as $key=>$value){
			$selected = ($this->value==$key)?" selected":"";
			$options[] = "<option value='$key'$selected>$value</option>\n";
		}
		return "<select" . $this->getParams_inner(false) . ">\n" . implode("", $options) . "</select>";
	}

}
