<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

//TODO:Namespace

require_once '../Start.php';
$page = \t2\Start::init("PAGEID_CORE_UPDATER", "Updater");

require_once ROOT_DIR . "/core/api/Core_database.php";
$page->add(\service\Html::H1("Updater"));
$page->add(\service\Html::PRE_console("", "ID_RESULTS"));

//TODO: git pull
#$result="";

#$updater = new \admin\Core_database();
#$page->add($updater->update()?:"(-/-)");

$page->send_and_quit();
