<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\dev;

require_once '../Start.php';

use t2\core\Html;
use t2\Start;

$page = Start::init_("PAGEID_CORE_DEVZONE");

$page->add(Html::H1("Dev zone"));

$page->add(Html::A_button("New module", Html::href_internal_root("dev/new_module")));
$page->add(Html::A_button("Notes", Html::href_internal_root("dev/notes")));
$page->add(Html::A_button("TODOs","https://github.com/experder/T2/blob/dev/dev/notes.md#current-todos",null,array("target"=>"_blank")));
$page->add(Html::A_button("CSS demo", Html::href_internal_root("dev/demo/cssdemo")));
$page->add(Html::A_button("PDF demo", Html::href_internal_root("dev/demo/pdfdemo")));
$page->add(Html::A_button("Ajax demo", Html::href_internal_root("dev/demo/ajaxdemo")));

//TODO(1): Register modules! (dev_tools)
#$page->add(\t2\core\Html::A_button("Convert TODOs",\t2\core\Html::href_internal("dev/mod_tools/convert_todos")));

$page->send_and_quit();
