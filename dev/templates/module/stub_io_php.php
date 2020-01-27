<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\modules\core_template;

require_once '../../../Start.php';

use t2\core\Html;
use t2\core\service\Request;
use t2\core\form\Form;
use t2\core\form\Formfield_textarea;
use t2\Start;

$page = Start::init("PAGEID_CORE_TEMPLATE_STUB_IO_PHP", "My page");

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