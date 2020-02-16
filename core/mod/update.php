<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\mod;

require_once '../../Start.php';

use t2\core\Html;
use t2\core\Page;
use t2\core\service\Config;
use t2\core\service\Js;
use t2\Start;

$page = Start::init_("PAGEID_CORE_UPDATER");

$page->add_stylesheet("CSS_ID_CORE_UPDATER", Page::get_stylesheet("core_admin.css"));

$page->add(Html::H1("Updater"));

$page->add($div=new Html("div",null,array("class"=>"updater_buttons")));

$page->add(Html::PRE_console("", "ID_RESULTS", "ID_RESULTS_outer"));

/*
 * Shell
 */
$shellname = Admin::get_update_script_name();
$div->addChild(Html::BUTTON($shellname, "update_shell();"));
$page->add_inline_js(
	"function update_shell(){"
	. "t2_spinner_start();"
	. Js::ajax_post_to_id(
		'core', 'update_shell', "ID_RESULTS",
		Js::scroll_to_bottom('ID_RESULTS_outer')
		. "t2_spinner_stop();"
		, null, true
	)
	. "}"
);

/*
 * Database
 */
$div->addChild(Html::BUTTON("Database", "update_db();"));
$page->add_inline_js(
	"function update_db(){"
	. "t2_spinner_start();"
	. Js::ajax_post_to_id(
		'core', 'update_db', "ID_RESULTS",
		Js::scroll_to_bottom('ID_RESULTS_outer')
		. "t2_spinner_stop();"
		, null, true
	)
	. "}"
);

/*
 * Includes
 */
$div->addChild(Html::BUTTON("Includes", "update_includes();"));
$page->add_inline_js(
	"function update_includes(){"
	. "t2_spinner_start();"
	. Js::ajax_post_to_id(
		'core', 'update_includes', "ID_RESULTS",
		Js::scroll_to_bottom('ID_RESULTS_outer')
		. "t2_spinner_stop();"
		, null, true
	)
	. "}"
);
//$page->add_inline_js("function update_includes(){
//	t2_spinner_start();
//	".Js::ajax_to_id("core", "update_includes", array(), "ID_RESULTS", true, Strings::escape_value_inline_js("
//		if(!response){\$(\"#ID_RESULTS\").append(\"<br>(possibly timed out)<br>\");}
//		$(\"#ID_RESULTS_outer\").stop().animate({
//			scrollTop: $(\"#ID_RESULTS_outer\")[0].scrollHeight
//		}, 800);
//		t2_spinner_stop();
//	"))."
//}");


if(Config::$DEVMODE){
	$div->addChild(Html::BUTTON("Clear", "document.getElementById(\"ID_RESULTS\").innerHTML='';", array("id"=>"dev_btn_clear")));
}

$page->send_and_quit();
