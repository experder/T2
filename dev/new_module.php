<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\dev;

require_once __DIR__.'/../Start.php';

use t2\Start;

$page = Start::init_("PAGEID_ADMIN_NEWMOD");

Tools::prompt_new_module($page);

$page->send_and_quit();