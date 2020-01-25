<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


namespace t2\core\form;

class Formfield_radio_option {

	private $value;
	private $title;

	/**
	 * Formfield_radio_option constructor.
	 * @param string $value
	 * @param string $title
	 */
	public function __construct($value, $title) {
		$this->value = $value;
		$this->title = $title;
	}

	public function to_form_html($name, $checked_val=null) {
		$checked=($this->value==$checked_val?"checked":"");
		return "<div class='ff_radiooption'><input type='radio' $checked name='$name' value='$this->value'/>$this->title</div>";
	}

}
