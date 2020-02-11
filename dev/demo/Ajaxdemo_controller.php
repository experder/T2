<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\modules\core_demo;

use t2\core\Html;
use t2\core\Page;
use t2\core\service\Js;

class Ajaxdemo_controller {

	public static function calculate_md5($string) {
		$md5 = md5($string);
		return $md5;
	}

	public static function example_2(Page $page){
		$page->add(Html::H2("Example 2: FORM &rarr; HTML with precalculation JS"));

		$page->add(Html::PRE_code_html("\$page->add(new Html(\"input\", \"\", array(\"type\" => \"text\", \"id\" => \"in\"), null, true));
\$page->add(Html::BUTTON(\"Calculate!\", \"ajax_example_2();\"));
\$page->add_inline_js(\"
	function ajax_example_2(){
		let value1 = $('#in').val();
		\" . Js::ajax_post('core_demo', 'md5', \"{input_string:value1}\", \"$('#out_node').html(data.html);\") . \"
	}
\");
\$page->add(new Html(\"code\", \"&hellip;\", array(\"id\" => \"out_node\")));

// And in the ajax class:
class Ajaxdemo_my extends Ajax {
	public function return_by_cmd(\$cmd, \$keyValues) {
		switch (\$cmd){
			case 'md5':
				new Ajax_response(Ajax_response::TYPE_HTML,
					\"md5=\" . Ajaxdemo_controller::calculate_md5(
						Arrays::value_from_array(\$keyValues, 'input_string')
					)
				);
				break;
		}
	}
}", array("class" => "language-php")));

		$page->add(Html::H3("Test"));
		$page->add(new Html("input", "", array("type" => "text", "id" => "in2"), null, true));
		$page->add(Html::BUTTON("Calculate!", "ajax_example_2();"));
		$page->add_inline_js("
				function ajax_example_2(){
					let value1 = $('#in2').val();
					" . Js::ajax_post('core_demo', 'md5', "{input_string:value1}", "$('#out_node2').html(data.html);") . "
				}
			");
		$page->add(new Html("code", "&hellip;", array("id" => "out_node2")));

	}

	public static function example_1(Page $page){
		$page->add(Html::H2("Example 1: FORM &rarr; HTML"));

		$page->add(Html::PRE_code_html("\$page->add(Html::H3(\"Test\"));
\$page->add(new Html(\"input\", \"\", array(\"type\" => \"text\", \"id\" => \"input1\"), null, true));
\$page->add(Html::BUTTON(\"Calculate!\", Js::ajax_post('core_demo', 'md5', \"{input_string:$(\\\"#input1\\\").val()}\", \"$('#out_node').html(data.html);\")));
\$page->add(new Html(\"code\", \"&hellip;\", array(\"id\" => \"out_node\")));

// And in the ajax class:
class Ajaxdemo_my extends Ajax {
	public function return_by_cmd(\$cmd, \$keyValues) {
		switch (\$cmd){
			case 'md5':
				new Ajax_response(Ajax_response::TYPE_HTML,
					\"md5=\" . Ajaxdemo_controller::calculate_md5(
						Arrays::value_from_array(\$keyValues, 'input_string')
					)
				);
				break;
		}
	}
}", array("class" => "language-php")));

		$page->add(Html::H3("Test"));
		$page->add(new Html("input", "", array("type" => "text", "id" => "input1"), null, true));
		$page->add(Html::BUTTON("Calculate!", Js::ajax_post('core_demo', 'md5', "{input_string:$(\"#input1\").val()}", "$('#out_node1').html(data.html);")));
		$page->add(new Html("code", "&hellip;", array("id" => "out_node1")));

	}

	public static function example_3(Page $page){
		$page->add(Html::H2("Example 3: FORM &rarr; HTML with postcalculation JS"));

		$page->add(Html::PRE_code_html("

// And in the ajax class:
class Ajaxdemo_my extends Ajax {
	public function return_by_cmd(\$cmd, \$keyValues) {
		switch (\$cmd){
			case 'md5':
				new Ajax_response(Ajax_response::TYPE_HTML,
					\"md5=\" . Ajaxdemo_controller::calculate_md5(
						Arrays::value_from_array(\$keyValues, 'input_string')
					)
				);
				break;
		}
	}
}", array("class" => "language-php")));

		$page->add(Html::H3("Test"));
		$page->add(new Html("input", "", array("type" => "text", "id" => "input3"), null, true));
		$page->add(Html::BUTTON("Calculate!", Js::ajax_post_to_id(
			'core_demo',
			'md5',
			'out_node3',
			"alert('response='+data.html);",
			"{input_string:$('#input3').val()}"
		)));
		$page->add(new Html("code", "&hellip;", array("id" => "out_node3")));

	}

}