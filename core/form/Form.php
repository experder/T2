<?php

/*
require_once ROOT_DIR.'/core/form/Form.php';
 */

namespace core;

require_once ROOT_DIR.'/core/Html.php';
require_once ROOT_DIR.'/core/form/Formfield.php';
require_once ROOT_DIR.'/core/form/Formfield_hidden.php';
require_once ROOT_DIR.'/core/form/Formfield_text.php';
require_once ROOT_DIR.'/core/form/Formfield_password.php';


class Form {

	/**
	 * $action is an URL that is called on form submission. Can be left empty (same page is called).
	 * @var string $action
	 */
	private $action;
	/**
	 * Form submission method. The submission method is "post" by default.
	 * @var string $method ["get"|"post"]
	 */
	private $method;
	private $buttons = array();
	private $fields = array();

	/**
	 * Form constructor.
	 * @param string       $action is an URL that is called on form submission. Can be left empty (same page is called).
	 * @param string|false $submit_text Label of the submit button. FALSE to turn off submit button.
	 * @param string|null  $cmd If set, a hidden key "cmd" is sent on submission.
	 * @param string|null  $method Form submission method. The submission method is "post" by default.
	 */
	public function __construct($cmd = null, $action = "", $submit_text = "Absenden", $method = "post") {

		$this->action = $action;

		$this->method = $method;

		if ($cmd) {
			$this->fields[] = new Formfield_hidden("cmd", $cmd);
		}

		if ($submit_text!==false) {
			$this->buttons[] = "<input type='submit' value='$submit_text'>";
		}

	}

	public function add_field(Formfield $formfield) {
		$this->fields[] = $formfield;
	}

	public function __toString() {
		return $this->toHtml();
	}

	public function toHtml() {
		$buttons = new Html("div",implode("\n", $this->buttons),array(
			"class"=>"buttons"
		));
		$fields_html = implode("\n", $this->fields);
		return "<form action=\"$this->action\" method='$this->method'>\n$fields_html\n$buttons\n</form>";
	}

}
