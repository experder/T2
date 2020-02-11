<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\modules\core_demo;

require_once '../../Start.php';
require_once 'Ajaxdemo_controller.php';

use t2\core\Html;
use t2\core\service\Js;
use t2\Start;

$page = Start::init_("PAGEID_DEV_AJAXDEMO");

$page->add(Html::H1("Examples"));

/*
 * Ajax class
 */

$page->add(Html::P("The following examples expect these AJAX responses:"));

$page->add(Html::PRE_code_html("class Ajaxdemo_my extends Ajax {
	public function return_by_cmd(\$cmd, \$keyValues) {
		switch (\$cmd){
			case 'md5_html':
				new Ajax_response(Ajax_response::TYPE_HTML,
					\"md5=\" . Ajaxdemo_controller::calculate_md5(
						Arrays::value_from_array(\$keyValues, 'input_string')
					)
				);
				break;
			case 'md5_json':
				new Ajax_response(Ajax_response::TYPE_JSON,
					array(
						\"md5=\" => Ajaxdemo_controller::calculate_md5(
							Arrays::value_from_array(\$keyValues, 'input_string')
						)
					)
				);
				break;
		}
		return \$this->unknown_command(\$cmd, 1);
	}
}", array("class" => "language-php")));


/*
 * Examples
 */

Ajaxdemo_controller::example_1($page);
Ajaxdemo_controller::example_2($page);
Ajaxdemo_controller::example_3($page);

//TODO: Beispiel mit Spinner

/*
 * Example 3
 */
$page->add(Html::H2("Example 3"));




$page->add(Html::BUTTON("Update4",Js::ajax_post('core_demo','test3',"{foo:'bar'}","$('#target').html(data.html);")));

$page->add(Html::BUTTON("Update5",Js::ajax_post('core_demo','test4',"{foo:$(\"#in\").val()}","$('#target').html('bar='+data.data.bar);")));




$page->send_and_quit();
