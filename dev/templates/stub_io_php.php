<?php

namespace t2\modules\core_template;

require_once '../../Start.php';
require_once ROOT_DIR . '/core/form/Form.php';

use service\Html;
use service\Request;
use t2\core\Form;
use t2\core\Formfield_textarea;
use t2\Start;

$page = Start::init("A", "B");

$form = new Form("do_process", "", "Process");
$form->add_field(new Formfield_textarea("input", ""));

$page->add($form);
if(Request::cmd("do_process")){
	$out = process();
	$page->add(Html::TEXTAREA_console($out));
}

$page->send_and_quit();
//==================================================================================
function process(){
	$input = Request::value("input");
	$output = $input;
	//...
	return $output;
}