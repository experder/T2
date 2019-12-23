<?php

/*
 * <code>
require_once '../../tethys/Start.php';
$page = \core\Page::init("PAGE_ID_MYPAGE", "My page");
$page->add("Hello World!");
$page->send_and_quit();
 * </code>
 */

namespace tethys_root;

/*
 * Basic configuration
 */

//Root directory is where the Start.php is:
if (!defined("ROOT_DIR")) {
	define("ROOT_DIR", __DIR__);
}

//Dependencies:
require_once ROOT_DIR.'/core/Page.php';
require_once ROOT_DIR.'/core/Error.php';
require_once ROOT_DIR.'/core/Message.php';

use core\Page;


class Start {


}
