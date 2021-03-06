<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\form;

//TODO(1): Submission of a form on a page called with "?key=val" results in an $_REQUEST array that contains key=val instead of submitted value
use t2\core\Html;

class Form {

	/**
	 * @var string|false $action
	 */
	private $action;
	/**
	 * @var string $method
	 */
	private $method;
	private $buttons = array();
	private $fields = array();

	private $params;

	/**
	 * @param string|false $action An URL that is called on form submission.
	 *                             Can be left empty (same page is called).
	 *                             Set to FALSE to disable html native send functionality.
	 *                             (Which will still send form via get)
	 * @param string|false $submit_text Label of the submit button. FALSE to turn off submit button.
	 * @param string|null  $CMD_ If set, a hidden key "cmd" is sent on submission.
	 * @param string|null  $method ["get"|"post"] Form submission method. The submission method is "post" by default.
	 * @param array        $params Associative array width additional params for the HTML form tag.
	 */
	public function __construct($CMD_ = null, $action = "", $submit_text = "Send", $method = "post", $params = array()) {

		$this->params = $params;

		$this->action = $action;

		$this->method = $method;

		if ($CMD_) {
			$this->fields[] = new Formfield_hidden("cmd", $CMD_);
		}

		if ($submit_text !== false) {
			$this->buttons[] = "<input type='submit' value='$submit_text'>";
		}

	}

	public function add_field(Formfield $formfield) {
		//PHP type check does this:
//		if(!($formfield instanceof Formfield)){
//			new Error_("Please pass formfields only.");
//		}
		$this->fields[] = $formfield;
	}

	public function __toString() {
		return $this->toHtml();
	}

	public function add_button($button) {
		$this->buttons[] = $button;
	}

	public function toHtml() {
		$buttons = "";
		if ($this->buttons) {
			$buttons = "\n" . new Html("div", "", array(
					"class" => "buttons"
				), $this->buttons);
		}
		$fields_html = implode("\n", $this->fields);
		$action = ($this->action === false ? "" : (" action=\"$this->action\" method='$this->method'"));
		return "<form$action " . Html::tag_keyValues($this->params) . ">\n$fields_html$buttons\n</form>";
	}

}
