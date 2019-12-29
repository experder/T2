<?php

namespace core;

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
		Error::quit_bare("Should never be called");
		return "NOT IN USE";
	}
}
