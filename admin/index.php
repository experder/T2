<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

//TODO(3):namespace

require_once '../Start.php';

$page = \t2\Start::init("PAGEID_CORE_ADMIN", "Admin");

$page->add(\t2\core\service\Html::A_button("Update",\t2\core\service\Html::href_internal("admin/update")));

if(\t2\core\service\Config::$DEVMODE){
	$page->add(\t2\core\service\Html::A_button("Dev area",\t2\core\service\Html::href_internal("dev/index")));
}

$page->send_and_quit();
