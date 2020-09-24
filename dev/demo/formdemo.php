<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\modules\core_demo;

require_once __DIR__.'/../../Start.php';
require_once ROOT_DIR . '/dev/demo/Loremipsum.php';

use t2\core\form\Fieldset;
use t2\core\form\Form;
use t2\core\form\Formfield_checkbox;
use t2\core\form\Formfield_password;
use t2\core\form\Formfield_radio;
use t2\core\form\Formfield_select;
use t2\core\form\Formfield_text;
use t2\core\form\Formfield_textarea;
use t2\core\Html;
use t2\core\service\Js;
use t2\core\service\Strings;
use t2\Start;

$page = Start::init_("PAGEID_DEV_FORMDEMO");

$page->add(Html::H1("Forms demo"));

$page->add(Html::H2("Forms", "forms"));

$page->add($form = new Form("demo"));
$form->add_field(new Formfield_text("text", "text / this is a very long title with many chars in a lot of rows"));
$form->addClientAccordion(new Formfield_text("more_a_{{c}}", "Mehr #{{c}}")."\n");
$form->addClientAccordion(new Formfield_text("more_b[]", "Noch mehr #{{c}}")."\n");
$form->add_field($fieldset=new Fieldset("Fieldset"));
$fieldset->addField(new Formfield_password("password"));
$fieldset->addField(new Formfield_textarea("textarea", null, Loremipsum::schlauer_spruch()));
$form->add_field(new Formfield_select("select", "select", array("a"=>"Aaa","b"=>"Bbb"), 'b' ));
$form->add_field(new Formfield_checkbox("checkbox", null, null, "Label"));
//TODO(1): submit unchecked checkboxes!
//TODO(1): submit unchecked radios!
$form->add_field(new Formfield_radio("radio", array(
	"val1" => "title1",
	"val2" => "title2",
)));

$form->add_button(Html::BUTTON("BUTTON", "alert('" . Strings::escape_value_html2(Loremipsum::schlauer_spruch()) . "');"));

$form->add_button(Html::BUTTON("Spin", "t2_spinner_start();" . Js::run_later("t2_spinner_stop();", 3)));


$page->send_and_quit();
