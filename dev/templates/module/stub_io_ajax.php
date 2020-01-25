<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\modules\core_template;

require_once '../../../Start.php';
//equire_once ROOT_DIR . "/core/mod/Core_database.php";
//equire_once ROOT_DIR . "/core/service/Js.php";
//equire_once ROOT_DIR . '/dev/templates/module/Controller.php';

use t2\core\service\Html;
use t2\service\Js;
use t2\Start;

$page = Start::init("PAGEID_TEMPLATES_IOAJAX", "My page");

$page->add(Html::H1("My page"));
$page->add(Html::BUTTON("Add",Js::ajax_to_id("core_template", "test2", array("foo"=>"add"), "ID_RESULTS", true))."\n");
$page->add(Html::BUTTON("Set",Js::ajax_to_id("core_template", "test2", array("foo"=>"set"), "ID_RESULTS", false))."<br>\n");
$page->add(Html::TEXTAREA_console(
	Controller::calculate_test2("init"),
	"ID_RESULTS"
));

$page->add_inline_js(Js::jquery_onload(Js::ajax_to_id("core_template", "test2", array("foo"=>"bar"), "ID_RESULTS", true)));


$page->send_and_quit();
