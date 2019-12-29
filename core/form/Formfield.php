<?php
/*
require_once ROOT_DIR . '/core/form/Formfield.php';
 */

namespace core;

use service\Request;
use service\Strings;

require_once ROOT_DIR . '/core/Html.php';
require_once ROOT_DIR . '/service/Html.php';
require_once ROOT_DIR . '/service/Strings.php';
require_once ROOT_DIR . '/service/Request.php';


/**
 * Class Formfield
 * Generic class representing all formfields.
 */
abstract class Formfield {

	//Formfield:
	protected $name;
	protected $value;
	public $id = null;
	/**
	 * @var array All params except "name", "value" and "id".
	 */
	public $more_params;

	//Label:
	protected $title;
	public $tooltip = "";

	//Surrounding div:
	public $outer_id = null;
	public $outer_class = null;
	public $outer_more_params = array();

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
	function __construct($name, $title = null, $value = null, $val_from_request = true, $more_params = array()) {
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
		$label = $this->title;
		$tooltip = $this->tooltip;

		//Tooltip? Change label
		if ($tooltip) $label .= " (!)";

		//Developers see the fieldname
		if (DEVMODE) $tooltip .= " [" . $this->name . "]";

		$title = $tooltip ? "title='" . Strings::escape_value_html($tooltip) . "'" : "";

		return "<div" . $this->getParams_outer() . ">"
			. "<label $title>$label</label>"
			. $this->inner_html()
			. "</div>";
	}

	/**
	 * Every formfield has a name, a value, an id and possibly a list of some other parameters ($more_params).
	 * This function creates the corresponding HTML-snippet.
	 * @param bool $value If set to false, the parameter "value" is skipped.
	 * @return string String to insert into the HTML code.
	 */
	protected function getParams_inner($value = true) {
		$params = $this->more_params;

		if ($this->name) {
			$params["name"] = $this->name;
		}
		if ($value) {
			if ($this->value) $params["value"] = $this->value;
		}
		if ($this->id) {
			$params["id"] = $this->id;
		}

		return \service\Html::tag_keyValues($params);
	}

	/**
	 * For documentation @see getParams_inner.
	 */
	protected function getParams_outer() {
		$params = $this->outer_more_params;

		if ($this->outer_id) $params["id"] = $this->outer_id;
		$params["class"] = "form_field" . ($this->outer_class ? " " . $this->outer_class : "");

		return \service\Html::tag_keyValues($params);
	}

}
