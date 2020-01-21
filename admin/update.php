<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\admin;

require_once '../Start.php';
require_once ROOT_DIR . "/core/mod/Core_database.php";

use t2\core\mod\Core_database;
use service\Config;
use service\Html;
use t2\Start;

$page = Start::init("PAGEID_CORE_UPDATER", "Updater");

$page->add(Html::H1("Updater"));

$platform = Config::PLATFORM();

if($platform==Config::PLATFORM_WINDOWS){
	$result = `cd..&&update.cmd 2>&1`;
	$result = mb_convert_encoding($result, "utf-8", "cp850");
}else if($platform==Config::PLATFORM_LINUX){
	//TODO
	#$result="";
}
$result=htmlentities($result);
$result.="\n";

$updater = new Core_database();
$result.="========= Update_database =========\n";
$result.=$updater->update()?:"Already up to date.";

//TODO:Get the two outputs by ajax
$page->add(Html::PRE_console($result, "ID_RESULTS"));


$page->send_and_quit();
