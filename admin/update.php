<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\admin;

require_once '../Start.php';

use t2\core\Error_;
use t2\core\mod\Core_database;
use t2\core\service\Config;
use t2\core\Html;
use t2\dev\Install_wizard;
use t2\Start;

$page = Start::init("PAGEID_CORE_UPDATER", "Updater");

$page->add(Html::H1("Updater"));

$platform = Config::get_check_platform();
$project_root = PROJECT_ROOT;

if ($platform == Config::PLATFORM_WINDOWS) {
	if (!file_exists(PROJECT_ROOT . '/update.cmd')) {
		Install_wizard::init_updater($platform);
	}
	$result = `cd "$project_root" && update.cmd 2>&1`;
	$result = mb_convert_encoding($result, "utf-8", "cp850");
} else if ($platform == Config::PLATFORM_LINUX) {
	if (!file_exists(PROJECT_ROOT . '/update.sh')) {
		Install_wizard::init_updater($platform);
	}
	$result = `cd '$project_root' && ./update.sh 2>&1`;
} else {
	//Should not happen because $platform should be checked already
	new Error_("Unknown platform.");
}
$result = htmlentities($result);
$result .= "\n";

$updater = new Core_database();
$result .= "========= Update_database =========\n";
$result .= $updater->update() ?: "Already up to date.";

//TODO(3):Updater: Get the (two) outputs by ajax
$page->add(Html::PRE_console($result, "ID_RESULTS"));


$page->send_and_quit();
