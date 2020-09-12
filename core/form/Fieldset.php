<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\form;

class Fieldset extends Formfield {

	/**
	 * @var Formfield[] $fields
	 */
	private $fields = array();

	public function __construct($legend = null) {
		parent::__construct(null, $legend);
	}

	public function inner_html() {
		return implode("\n", $this->fields);
	}

	public function addField(Formfield $field) {
		$this->fields[] = $field;
	}

	protected function toHtml() {
		return "<fieldset>\n"
			.($this->title===null?"":"<legend>$this->title</legend>\n")
			. $this->inner_html()
			. "\n</fieldset>";
	}

}
