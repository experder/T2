<?php

namespace t2\dev\demo;

require_once '../../Start.php';

use t2\core\Html;
use t2\core\service\Js;
use t2\modules\core_template\api\Ajax;
use t2\Start;

$page = Start::init("PAGEID_DEV_AJAXDEMO", "Ajax demo");

$page->add(Html::BUTTON("Update","update();"));
$page->add_inline_js("
	function update(){
		".Js::ajax_post('core_template','test2',"alert('ok'+data.error_msg);console.log(data);")."
	}
");
$page->add(new Html("div","...",array("id"=>"target")));


$page->send_and_quit();
