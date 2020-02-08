<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2;

require_once 'Start.php';

use t2\core\Html;

$page = Start::init_("PAGEID_CORE_INDEX");

$page->add("Welcome!");

$page->add(Html::A_button("Admin", Html::href_internal_root("admin/index")));

$page->send_and_quit();