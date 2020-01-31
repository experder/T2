<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

//TODO(3):Namespace of dev index

require_once '../Start.php';

$page = \t2\Start::init("PAGEID_CORE_DEVZONE", "Dev zone");

$page->add(\t2\core\Html::H1("Dev zone"));

$page->add(\t2\core\Html::A_button("New module",\t2\core\Html::href_internal("dev/new_module")));
$page->add(\t2\core\Html::A_button("Notes",\t2\core\Html::href_internal("dev/notes")));
$page->add(\t2\core\Html::A_button("TODOs","https://github.com/experder/T2/blob/dev/dev/notes.md#current-todos",null,array("target"=>"_blank")));
$page->add(\t2\core\Html::A_button("CSS demo",\t2\core\Html::href_internal("dev/demo/cssdemo")));
$page->add(\t2\core\Html::A_button("PDF demo",\t2\core\Html::href_internal("dev/demo/pdfdemo")));

//TODO(1): Register modules! (dev_tools)
#$page->add(\t2\core\Html::A_button("Convert TODOs",\t2\core\Html::href_internal("dev/mod_tools/convert_todos")));

$page->send_and_quit();
