<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\mod;

require_once __DIR__.'/../../Start.php';

use t2\core\Html;
use t2\core\Page;
use t2\Start;

$page = Start::init_("PAGEID_CORE_CONFIGGUI");

$page->add_stylesheet("CSS_ID_CORE_ADMIN", Page::get_stylesheet("core_admin.css"));

$page->add(Html::H1("Config"));

$page->add(Admin::get_config_form());

$page->send_and_quit();
