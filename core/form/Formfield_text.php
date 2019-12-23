<?php

namespace core;


class Formfield_text extends Formfield {

	public function inner_html() {
		return "<input type='text'" . $this->getParams_inner() . " />";
	}

}
