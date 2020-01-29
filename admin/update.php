<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\admin;

require_once '../Start.php';
require_once ROOT_DIR . '/core/service/Js.php';//TODO(3)

use t2\core\Html;
use t2\core\Page;
use t2\core\service\Config;
use t2\core\service\Strings;
use t2\service\Js;
use t2\Start;

$page = Start::init("PAGEID_CORE_UPDATER", "Updater");

$page->add_stylesheet("CSS_ID_CORE_UPDATER", Page::get_stylesheet("core_admin.css"));

$page->add(Html::H1("Updater"));

$page->add($div=new Html("div",null,array("class"=>"updater_buttons")));

$page->add(Html::PRE_console("", "ID_RESULTS", "ID_RESULTS_outer"));

/*
 * Shell
 */
$div->addChild(Html::BUTTON("Shell", "update_shell();"));
//TODO(2):Service Function for ScrollToBottom
//TODO(2):Correction of escaping for inner ajax functions!
//TODO(2):Use of "//" ends all functions in inner functions
$page->add_inline_js("function update_shell(){
	".Js::ajax_to_id("core", "update_shell", array(), "ID_RESULTS", true, Strings::escape_value_inline_js("
		$(\"#ID_RESULTS_outer\").stop().animate({
			scrollTop: $(\"#ID_RESULTS_outer\")[0].scrollHeight
		}, 800);
//		var objDiv = document.getElementById(\"ID_RESULTS_outer\");
//		objDiv.scrollTop = objDiv.scrollHeight;
//		alert(\"!\");
	"))."
}");

/*
 * Database
 */
$div->addChild(Html::BUTTON("Database", "update_db();"));
$page->add_inline_js("function update_db(){
	".Js::ajax_to_id("core", "update_db", array(), "ID_RESULTS", true, Strings::escape_value_inline_js("
		$(\"#ID_RESULTS_outer\").stop().animate({
			scrollTop: $(\"#ID_RESULTS_outer\")[0].scrollHeight
		}, 800);
	"))."
}");

/*
 * Includes
 */
$div->addChild(Html::BUTTON("Includes", "update_includes();"));
$page->add_inline_js("function update_includes(){
	".Js::ajax_to_id("core", "update_includes", array(), "ID_RESULTS", true, Strings::escape_value_inline_js("
		$(\"#ID_RESULTS_outer\").stop().animate({
			scrollTop: $(\"#ID_RESULTS_outer\")[0].scrollHeight
		}, 800);
	"))."
}");


if(Config::$DEVMODE){
	$div->addChild(Html::BUTTON("Clear", "document.getElementById(\"ID_RESULTS\").innerHTML='';", array("id"=>"dev_btn_clear")));
}

$page->send_and_quit();
