<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\modules\core_demo;

require_once '../../Start.php';

use t2\core\Database;
use t2\core\form\Form;
use t2\core\form\Formfield_checkbox;
use t2\core\form\Formfield_password;
use t2\core\form\Formfield_radio;
use t2\core\form\Formfield_text;
use t2\core\form\Formfield_textarea;
use t2\core\Html;
use t2\core\Page;
use t2\core\service\Config;
use t2\core\service\Js;
use t2\core\service\Markdown;
use t2\core\service\Request;
use t2\core\service\Strings;
use t2\core\table\Cell;
use t2\core\table\Table;
use t2\dev\Debug;
use t2\Start;

$page = Start::init_("PAGEID_DEV_CSSDEMO");

if (!Config::$DEVMODE) {
	$page->add_message_error("Not available.");
	$page->send_and_quit();
}

$page->add_js_core();

$print_view = isset($_REQUEST['print']);

$page->add(Html::H1("CSS demo"));

/*
======================== Print ===========================
 */

if ($print_view) {
	$page->add(Html::A("screen.css", $_SERVER['SCRIPT_NAME']));
	$style = Config::get_value_core("SKIN");
	$stylesheet = Page::get_demoskins_stylesheet_print($style);
	$stylesheet->setMedia('all');
	$page->add_stylesheet("CSS_ID_PRINT", $stylesheet);

	//A4: b=21cm minus 2x15mm padding = 18cm
	$page->internal_css .= "@media screen {
		div.body_inner{width:18cm;margin:0 auto;background:white;padding:15mm;}
		body{margin-bottom:0;background:#D6D8EC;}
	}";

} else {
	$page->add(Html::A_button("print.css", "?print"));
}

/*
======================== Text ===========================
 */

$page->add(Html::H2("Text", "text"));
$page->add(schlauer_spruch());
$page->add(Html::PRE("pre {\n\twhite-space: pre-wrap;\n}"));

$page->add(Html::H3("Lists"));
$page->add($ul = Html::UL(array(
	schlauer_spruch(),
	schlauer_spruch(),
)));

$page->add(Html::H3("Console"));
$page->add(Html::H4("TEXTAREA"));
$page->add(Html::TEXTAREA_console(schlauer_spruch()));
$page->add(Html::H4("PRE"));
$page->add(Html::PRE_console(schlauer_spruch()));

$page->add(Html::H2("Markdown", "markdown"));
$page->add($s = Markdown::string_to_string("
### \\#\\#\\# Header 3
[Link](" . Html::href_internal_root('index') . ")
No new line
`inline code` Linebreak:  
__\\_\\_bold\\_\\___ _\\_italic\\__
**\\*\\*bold\\*\\*** *\\*italic\\**
* UL > LI

Paragraph

    code block
"));
#$page->add(Html::PRE(htmlentities($s),array('dev')));

/*
======================== Links ===========================
 */

$page->add(Html::H2("Links", "links") . "\n");
$page->add(Html::A('Index', Html::href_internal_root('index')) . "\n");
$page->add(Html::A_external('Html::A_external', "https://github.com/experder/T2/blob/99b7c6cfd9173b5150c840a3553ae5c03061ace9/service/Html.php#L82:L87") . "\n");
$page->add(Html::A_external('External, button', 'http://tethys-framework.de', array("class" => "abutton")) . "\n");
$page->add(Html::A_button('Button, external', 'http://tethys-framework.de', array(), array("target" => "_blank")) . "\n");

/*
======================== Tables ===========================
 */
$page->add(Html::H2("Tables", "tables") . "\n");
$table = new Table(array(
	array(
		"Col.1" => "Foo",
		"Col.2" => "Bar",
		"Col.3" => "Barbar",
	),
	array(
		"Col.1" => new Cell("fooFoo!"),
		"Col.2" => null,
		"Col.3" => "fooBarbar",
		"Col.4" => "Nanu?",
	),
	array(
		"",
		"barBar",
	),
));
$page->add($table->__toString());
$table->set_headers(array(
	"Col.1" => true,
	"Col.3" => "Three",
	"Col.2" => "Two",
));
$page->add($table);

/*
======================== Messages ===========================
 */

$page->add_message_info('<b>NOTE!</b> The following error is not real!');

$page->add_message_error(
	'An error occured: ERROR_TABLE_NOT_FOUND/' . time() . '<pre class="dev dev_error_info">
[42S02] Table \'' . Database::get_singleton()->get_dbname() . '.SPACE\' doesn\'t exist
----------------------------------------
SELECT * FROM SPACE
----------------------------------------
' . __FILE__ . ':' . __LINE__ . '</pre>'
);
#\t2\core\Database::select_("SELECT * FROM SPACE");

$page->add_message_confirm(
	Html::H1('div.messages div.message h1')
	. Html::PRE(
		'$page->add_message_(new Message(Message::TYPE_CONFIRM, \'...\');'
	)
	. "<a href=\"https://raw.githubusercontent.com/experder/T2/master/core/Message.php\" target='_blank'>Link</a>"
);

/*
======================== Forms ===========================
 */
$page->add(Html::H2("Forms", "forms"));

if (Request::cmd("demo")) {
	Debug::out(print_r($_REQUEST, 1), "POST");
}

$page->add($form = new Form("demo"));
$form->add_field(new Formfield_text("text", "text / this is a very long title with many chars in a lot of rows"));
$form->add_field(new Formfield_password("password"));
$form->add_field(new Formfield_textarea("textarea", null, schlauer_spruch()));
$form->add_field(new Formfield_checkbox("checkbox", null, null, "Label"));
//TODO(1): submit unchecked checkboxes!
//TODO(1): submit unchecked radios!
$form->add_field(new Formfield_radio("radio", array(
	"val1" => "title1",
	"val2" => "title2",
)));

$form->add_button(Html::BUTTON("BUTTON", "alert('" . Strings::escape_value_html2(schlauer_spruch()) . "');"));

$form->add_button(Html::BUTTON("Spin", "t2_spinner_start();" . Js::run_later("t2_spinner_stop();", 3)));

$page->send_and_quit();
//==================================================================================================
function schlauer_spruch() {
	$sprueche = array(

		"Es muss Dienstag gewesen sein. Er hatte die kornblumenblaue Krawatte um.",
		"Dir Federn in den Arsch zu stecken macht dich noch lang‘ nicht zum Huhn.",
		"Du bist nicht dein Job. Du bist nicht das Geld auf deinem Konto. Nicht das Auto, das du fährst! Nicht der Inhalt deiner Brieftasche! Und nicht deine blöde Cargo-Hose.",
		"Alles was du besitzt, besitzt irgendwann dich.",
		"Die Leute, die ich auf jedem Flug kennen lerne sind portionierte Freunde. Zwischen Start und Landung verbringen wir unsere gemeinsame Zeit und das war’s.",
		"Ich sage: sei nie vollständig, ich sage: hör auf perfekt zu sein, ich sage: entwickeln wir uns, lass die Dinge einfach laufen!",
		"Zuerst musst du wissen, nicht fürchten, sondern wissen, dass du einmal sterben wirst.",
		"Zeit, für das, an was du glaubst, aufzustehen.",
		//Quelle: https://mymonk.de/10-lektionen-aus-dem-fight-club-und-die-regeln-des-mymonk-tempels/

		"Mein Name ist Guybrush Threepwood und ich will Pirat werden!",
		"Also du willst Pirat werden, wie? Siehst aber eher wie ein Buchhalter aus.",
		"Unser Grog ist ein Geheimrezept, das einige der folgenden Zutaten enthält: Kerosin, Propylen-Glykol, künstliche Süßstoffe, Schwefelsäure, Rum, Aceton, Rote Farbe, Scumm, Schmierfett, Batteriesäure und/oder Pepperonis. Wie man sich denken kann, ist es eine der ätzendsten Substanzen der Menschheit. Dieses Zeug frißt sich sogar durch diese Krüge, und der Koch verliert ein Vermögen. HAR! HAR! HAR! HAR! HAR! HAR!",
		"Hey, jedem fällt es schwer, seinen Atem frisch zu halten, wenn es außer Ratten nichts zu essen gibt.",
		"Ich hab den Schatz von Mêlée Island gefunden, aber alles, was mir blieb, ist dieses T-Shirt.",
		"Barkeeper: \"Ehrlich, ich mag keine Geschichten, die Leute vom Trinken abhalten sollen.\"",
		//Quelle: http://www.monkeyislandinside.de/sprueche.php

	);
	$spruch = $sprueche[rand(0, count($sprueche) - 1)];
	#$spruch="\"ä\"<hr>'ö'\\n\n".$spruch;
	#$spruch=htmlentities($spruch);
	return $spruch;
}
