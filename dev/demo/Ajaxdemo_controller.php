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

	public static function example_1(Page $page){
		$page->add(Html::H2("Example 1: HTML response"));

		$page->add(Html::PRE_code_html("\$page->add(new Html(\"input\", \"\", array(\"type\" => \"text\", \"id\" => \"input1\"), null, true));
\$page->add(Html::BUTTON(\"Calculate!\", \"t2_spinner_start();\" . Js::ajax_post(
	'core_demo',
	'md5_html',
	\"{input_string:$(\\\"#input1\\\").val()}\",
	\"$('#out_node1').html(data.html);\"
	. \"t2_spinner_stop();\"
)));
\$page->add(new Html(\"code\", \"&hellip;\", array(\"id\" => \"out_node1\")));
", array("class" => "language-php")));

		$page->add(Html::H3("Test"));

		$page->add(new Html("input", "", array("type" => "text", "id" => "input1"), null, true));
		$page->add(Html::BUTTON("Calculate!", "t2_spinner_start();" . Js::ajax_post(
			'core_demo',
			'md5_html',
			"{input_string:$(\"#input1\").val()}",
			"$('#out_node1').html(data.html);"
			. "t2_spinner_stop();"
		)));
		$page->add(new Html("code", "&hellip;", array("id" => "out_node1")));
	}

	public static function example_2(Page $page){
		$page->add(Html::H2("Example 2: JSON response"));

		$page->add(Html::PRE_code_html("\$page->add(new Html(\"input\", \"\", array(\"type\" => \"text\", \"id\" => \"input2\"), null, true));
\$page->add(Html::BUTTON(\"Calculate!\", \"t2_spinner_start();\" . Js::ajax_post(
	'core_demo',
	'md5_json',
	\"{input_string:$(\\\"#input2\\\").val()}\",
	\"$('#out_node2').html('md5='+data.json.md5);\"
	. \"t2_spinner_stop();\"
)));
\$page->add(new Html(\"code\", \"&hellip;\", array(\"id\" => \"out_node2\")));
", array("class" => "language-php")));

		$page->add(Html::H3("Test"));

		$page->add(new Html("input", "", array("type" => "text", "id" => "input2"), null, true));
		$page->add(Html::BUTTON("Calculate!", "t2_spinner_start();" . Js::ajax_post(
			'core_demo',
			'md5_json',
			"{input_string:$(\"#input2\").val()}",
			"$('#out_node2').html('md5='+data.json.md5);"
			. "t2_spinner_stop();"
		)));
		$page->add(new Html("code", "&hellip;", array("id" => "out_node2")));
	}

}