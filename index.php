<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2;

require_once 'Start.php';
use service\Html;
use t2\core\Database;
use t2\core\Error_;
use t2\dev\Debug;

$page = Start::init("PAGEID_CORE_INDEX", "Start");

$page->add("Welcome!");

$page->add(Html::A_button("Admin", Html::href_internal("admin/index") ));

#\t2\dev\Debug::out($_SERVER);

#require_once ROOT_DIR . '/core/Error_.php';
#new Error_("");

#Database::select_("SELECT * FROM core_config;");

$page->send_and_quit();