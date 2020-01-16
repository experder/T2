<?php

namespace t2\modules\core_template;

require_once '../../Start.php';
require_once ROOT_DIR . '/core/form/Form.php';

use service\Html;
use t2\core\Form;
use t2\core\Formfield_textarea;
use t2\Start;

$page = Start::init("A", "B");

$form = new Form(null, false, false);
$form->add_button(Html::BUTTON("Process","process();"));
$page->add_inline_js("function process(){
	var inp = $('#id_input').val();
	var out = inp;
	//...
	$('#ID_CONSOLE').html(out);
}");
$page->add_js_jquery341();
$form->add_field(new Formfield_textarea("input", "", null, true, array("id"=>"id_input")));

$page->add($form);
$page->add(Html::PRE_console("","ID_CONSOLE"));

$page->send_and_quit();
