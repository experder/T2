<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/
/**TPLDOCSTART
 * This template is used to create a new module.
 * @see TODO
 */
if(true)exit;
/**TPLDOCEND*/

require_once '../../Start.php';
$page = \core\Page::init("PAGE_ID_MYPAGE", "(:PAGE_TITLE)");

$page->add("Index of module (:MYMODULE)");

$page->send_and_quit();
