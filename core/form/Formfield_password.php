<?php

namespace core;


class Formfield_password extends Formfield {

	public function inner_html() {
		return "<input type='password'" . $this->getParams_inner() . " />";
	}

}
