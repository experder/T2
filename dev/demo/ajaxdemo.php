<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\modules\core_demo;

require_once '../../Start.php';

use t2\core\Html;
use t2\core\service\Includes;
use t2\core\service\Js;
use t2\Start;

$page = Start::init("PAGEID_DEV_AJAXDEMO", "Ajax demo");
Includes::js_highlight9181($page);
Includes::js_highlight9181_START($page);

$page->add(Html::BUTTON("Update1","update();"));
$page->add_inline_js("
	function update(){
		let a = $('#in').val();
		".Js::ajax_post('core_demo','test1',"{foo:a}","$('#target').html(data.html);")."
	}
");

$page->add(Html::BUTTON("Update2",Js::ajax_post('core_demo','test2',"{foo:$(\"#in\").val()}","$('#target').html(data.html);")));

$page->add(Html::BUTTON("Update3",Js::ajax_post('core_demo','test3',"{foo:'bar'}","$('#target').html(data.html);")));

$page->add(Html::BUTTON("Update4",Js::ajax_post('core_demo','test4',"{foo:$(\"#in\").val()}","$('#target').html('bar='+data.data.bar);")));

$page->add(new Html("input","",array("type"=>"text","id"=>"in"),null,true));

$page->add(new Html("div","...",array("id"=>"target")));

$page->add(Html::PRE_code("\$page->add(Html::BUTTON(\"Update2\",Js::ajax_post('core_demo','test2',\"{
foo:$(\\\"#in\\\").val()}\",\"$('#target').html(data.html);\")));", array("class"=>"language-php")));

$page->send_and_quit();
