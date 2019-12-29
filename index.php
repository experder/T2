<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


require_once 'Start.php';
$page = \core\Page::init("PAGE_ID_TETHYSINDEX", "T2");

$page->add("Welcome!");

$page->send_and_quit();