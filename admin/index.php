<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

require_once '../Start.php';
$page = \core\Page::init("PAGEID_CORE_ADMIN", "Admin");

use core\Html;

$page->add(\service\Html::A_button("Update",\service\Html::href_internal("admin/update")));

if(\service\Config::$DEVMODE){
	$devarea = new Html("div","");
	$devarea->addChild(new Html("h2","dev area"));
	$devarea->addChild(\service\Html::A_button("CSS demo",\service\Html::href_internal("dev/cssdemo")));
	$page->add($devarea);
}

$page->send_and_quit();