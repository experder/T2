<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


namespace t2\core;


class Formfield_radio extends Formfield {

	/**
	 * @var Formfield_radio_option[] $options
	 */
	private $options;

	public function __construct($name, $options, $title = null, $value = null, $val_from_request = true, $more_params = array()) {
		//TODO(1):Accept associative array for options
		$this->options=$options;
		parent::__construct($name, $title, $value, $val_from_request, $more_params);
	}

	protected function toHtml() {
		return "<div" . $this->getParams_outer() . ">"
			. "<label" . $this->get_title() . ">".$this->get_label()."</label>"
			. "<div class='formfield_inner' ".$this->getParams_inner(false, false).">\n"
			.$this->options_html()
			. "</div>"
			. "</div>";
	}

	private function options_html(){
		$options_html=array();
		foreach ($this->options as $option){
			$options_html[] = $option->to_form_html($this->name, $this->value)."\n";
		}
		return implode("", $options_html);
	}

	public function inner_html() {
		return "???";//(never used)
	}

}
