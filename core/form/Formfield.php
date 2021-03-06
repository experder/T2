<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\form;

use t2\core\Html;
use t2\core\service\Config;
use t2\core\service\Request;
use t2\core\service\Strings;

/**
 * Class Formfield
 * Generic class representing all formfields.
 */
abstract class Formfield {

	//Formfield:
	protected $name;
	protected $value;
	protected $id = null;
	/**
	 * @var array All params except "name", "value" and "id".
	 */
	protected $more_params;

	//Label:
	protected $title;
	protected $tooltip = "";

	//Surrounding div:
	protected $outer_id = null;
	protected $outer_class = null;
	protected $outer_more_params = array();

	/**
	 * Formfield constructor.
	 * @param             $name
	 * @param string|null $title
	 *                    If set to null, the fieldname is used as label.
	 * @param string|null $value
	 * @param bool        $val_from_request
	 *                    If set to true, the default value ($value) can be overwritten by the request.
	 *                    Example: .../myform.php?myvalue=Foo
	 * @param array       $more_params
	 *                    All params except "name", "value" and "id".
	 */
	public function __construct($name, $title = null, $value = null, $val_from_request = true, $more_params = array()) {
		$this->name = $name;

		//Title: If set to null, the fieldname is used as label.
		$this->title = ($title === null ? $name : $title);

		$this->value = $val_from_request ? Request::value($name, $value) : $value;

		$this->more_params = $more_params;
	}

	/**
	 * Generic function is overwritten with the respective HTML by the children.
	 * @return string
	 */
	abstract protected function inner_html();

	public function __toString() {
		return $this->toHtml();
	}

	protected function toHtml() {
		return "<div" . $this->getParams_outer() . ">"
			. "<label" . $this->get_title() . ">" . $this->get_label() . "</label>"
			. $this->inner_html()
			. "</div>";
	}

	protected function get_title() {
		$tooltip = $this->tooltip;

		//Developers see the fieldname
		if (Config::$DEVMODE) $tooltip .= " [" . $this->name . "]";

		$title = $tooltip ? " title='" . Strings::escape_value_html($tooltip) . "'" : "";
		return $title;
	}

	protected function get_label() {
		$label = $this->title;
		//Tooltip? Change label
		if ($this->tooltip) {
			$label .= " (!)";//TODO(3): Mark label if has tooltip
		}
		return $label;
	}

	/**
	 * Every formfield has a name, a value, an id and possibly a list of some other parameters ($more_params).
	 * This function creates the corresponding HTML-snippet.
	 * @param bool $value If set to FALSE, the parameter "value" is skipped.
	 * @param bool $name If set to FALSE, the parameter "name" is skipped.
	 * @return string String to insert into the HTML code.
	 */
	protected function getParams_inner($value = true, $name = true) {
		$params = $this->more_params;

		if ($name && $this->name) {
			$params["name"] = $this->name;
		}
		if ($value && $this->value) {
			$params["value"] = $this->value;
		}
		if ($this->id) {
			$params["id"] = $this->id;
		}

		return Html::tag_keyValues($params);
	}

	/**
	 * For documentation see getParams_inner.
	 * @see getParams_inner
	 */
	protected function getParams_outer() {
		$params = $this->outer_more_params;

		if ($this->outer_id) $params["id"] = $this->outer_id;
		$params["class"] = "form_field ff_" . $this->name . ($this->outer_class ? " " . $this->outer_class : "");

		return Html::tag_keyValues($params);
	}

}
