<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\service;

require_once("../../Start.php");

use t2\core\form\Form;
use t2\Start;

$page = Start::init_("PAGEID_LOGOUT");

$form = new Form("t2_dologout", "", "Logout");
$page->add($form);

$page->send_and_quit();