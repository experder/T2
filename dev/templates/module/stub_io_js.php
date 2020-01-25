<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\modules\core_template;

require_once '../../../Start.php';
//equire_once ROOT_DIR . '/core/form/Form.php';

use t2\core\service\Html;
use t2\core\form\Form;
use t2\core\form\Formfield_textarea;
use t2\Start;

$page = Start::init("PAGEID_CORE_TEMPLATE_STUB_IO_JS", "My page");

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
$page->add(Html::TEXTAREA_console("","ID_CONSOLE"));

$page->send_and_quit();
