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
//TODO: Require weg!
//TODO: Page should not be instantiated multiple times!
//TODO: Warum findet der Autoloader das nicht?

use t2\core\Database;
use t2\core\Html;
use t2\core\Page;
use t2\core\service\Config;
use t2\core\service\Config_core;
use t2\core\service\Markdown;
use t2\core\Stylesheet;
use t2\core\table\Cell;
use t2\core\table\Table;
use t2\Start;

$page = Start::init_("PAGEID_DEV_CSSDEMO");

if (!Config::$DEVMODE) {
	Page::add_message_error_("Not available.");
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
	$skin_dir = Config_core::skin_dir();
	$page->add_stylesheet("CSS_ID_PRINT", new Stylesheet($skin_dir . "/print.css", 'all'));

	//A4: b=21cm minus 2x15mm padding = 18cm
	$page->internal_css .= "@media screen {
		div.body_inner{width:18cm;margin:0 auto;background:white;padding:15mm;}
		body{margin-bottom:0;background:#D6D8EC;}
	}";

} else {
	$page->add(Html::A_button("print.css", "?print"));
}

/*
======================== Tables ===========================
 */
$page->add(Html::H2("Tables", "tables") . "\n");
$table_data = array(
	array(
		"Col.1" => "Foo",
		"Col.2" => "Bar",
		"Col.3" => "Barbar",
	),
	array(
		"Col.1" => new Cell("fooFoo!"),
		"Col.2" => null,
		"Col.3" => "fooBarbar",
	),
	array(
		"row3",
		"barBar",
		"barFoo",
	),
);
$table = new Table($table_data);
$page->add($table);
$table2 = new Table($table_data);
$table2->set_headers(array(
	"Col.1" => true,
	"Col.3" => "Three",
	"Col.2" => "Two",
));
$page->add($table2);

/*
======================== Text ===========================
 */

$page->add(Html::H2("Text", "text"));
$page->add(Loremipsum::schlauer_spruch());
$page->add(Html::PRE("pre {\n\twhite-space: pre-wrap;\n}"));

$page->add(Html::H3("Lists"));
$page->add($ul = Html::UL(array(
	Loremipsum::schlauer_spruch(),
	Loremipsum::schlauer_spruch(),
)));

$page->add(Html::H3("Console"));
$page->add(Html::H4("TEXTAREA"));
$page->add(Html::TEXTAREA_console(Loremipsum::schlauer_spruch()));
$page->add(Html::H4("PRE"));
$page->add(Html::PRE_console(Loremipsum::schlauer_spruch()));

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
======================== Messages ===========================
 */

Page::add_message_info_('<b>NOTE!</b> The following error is not real!');

Page::add_message_error_(
	'An error occured: ERROR_TABLE_NOT_FOUND/' . time() . '<pre class="dev dev_error_info">
[42S02] Table \'' . Database::get_singleton()->get_dbname() . '.SPACE\' doesn\'t exist
----------------------------------------
SELECT * FROM SPACE
----------------------------------------
' . __FILE__ . ':' . __LINE__ . '</pre>'
);
#\t2\core\Database_Service::select("SELECT * FROM SPACE");

Page::add_message_confirm_(
	Html::H1('div.messages div.message h1')
	. Html::PRE(
		'$page->add_message_(new Message(Message::TYPE_CONFIRM, \'...\');'
	)
	. "<a href=\"https://raw.githubusercontent.com/experder/T2/master/core/Message.php\" target='_blank'>Link</a>"
);


$page->send_and_quit();
