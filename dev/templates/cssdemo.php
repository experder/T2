<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

//TODO:check/reorg namespaces

require_once '../../Start.php';
require_once ROOT_DIR . '/core/form/Form.php';
require_once ROOT_DIR . '/core/Message.php';

use t2\core\Message;

$page = \t2\Start::init("PAGEID_DEV_CSSDEMO", "CSS demo");

if(!\service\Config::$DEVMODE){
	$page->add_message_e(new Message(Message::TYPE_ERROR, "Not available."));
	$page->send_and_quit();
}

$print_view = isset($_REQUEST['print']);

$page->add(\service\Html::H1("CSS demo"));

/*
======================== Print ===========================
 */

if($print_view){
	$page->add(\service\Html::A("screen.css", $_SERVER['SCRIPT_NAME']));
	$style = \service\Config::get_value_core("STYLE");
	$stylesheet = $page->get_demoskins_stylesheet_print($style);
	$stylesheet->setMedia('all');
	$page->add_stylesheet("CSS_ID_PRINT",$stylesheet);
}else{
	$page->add(\service\Html::A_button("print.css", "?print"));
}

/*
======================== Text ===========================
 */

$page->add(\service\Html::H2("Text", "text"));
$page->add(schlauer_spruch());
$page->add(\service\Html::PRE("pre {\n\twhite-space: pre-wrap;\n}"));

$page->add(\service\Html::H3("Lists"));
$page->add($ul=\service\Html::UL(array(
	schlauer_spruch(),
	schlauer_spruch(),
)));

$page->add(\service\Html::H4("&lt;h4>"));

/*
======================== Links ===========================
 */

$page->add(\service\Html::H2("Links", "links")."\n");
$page->add(\service\Html::A('Index',\service\Html::href_internal('index'))."\n");
$page->add(\service\Html::A_external('\service\Html::A_external',"https://github.com/experder/T2/blob/99b7c6cfd9173b5150c840a3553ae5c03061ace9/service/Html.php#L82:L87")."\n");
$page->add(\service\Html::A_external('External, button','http://tethys-framework.de',array("class"=>"abutton"))."\n");
$page->add(\service\Html::A_button('Button, external','http://tethys-framework.de',array(),array("target"=>"_blank"))."\n");

/*
======================== Messages ===========================
 */

$page->add_message_info('<b>NOTE!</b> The following error is not real!');

$page->add_message_(new Message(Message::TYPE_ERROR,
'An error occured: ERROR_SQL/'.time().'<pre class="dev_error_info">
[42S02] Table \''.\t2\core\Database::get_singleton()->get_dbname().'.SPACE\' doesn\'t exist
----------------------------------------
SELECT * FROM SPACE
----------------------------------------
'.__FILE__.':'.__LINE__.'</pre>'
));
#\t2\core\Database::select_("SELECT * FROM SPACE");

$page->add_message_(new Message(Message::TYPE_CONFIRM,
	\service\Html::H1('div.messages div.message h1')
	.\service\Html::PRE(
		'$page->add_message_(new Message(Message::TYPE_CONFIRM, \'...\');'
	)
	."<a href=\"https://raw.githubusercontent.com/experder/T2/master/core/Message.php\" target='_blank'>Link</a>"
));

/*
======================== Forms ===========================
 */
$page->add(\service\Html::H2("Forms", "forms"));

$page->add($form=new \t2\core\Form());
$form->add_field(new \t2\core\Formfield_text("text"));
$form->add_field(new \t2\core\Formfield_password("password"));
$form->add_field(new \t2\core\Formfield_textarea("textarea",null,schlauer_spruch()));
$form->add_field(new \t2\core\Formfield_textarea("textarea2","this is a very long title with many chars in a lot of rows"));



$page->send_and_quit();
//==================================================================================================
function schlauer_spruch(){
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
	return $sprueche[rand(0,count($sprueche)-1)];
}
