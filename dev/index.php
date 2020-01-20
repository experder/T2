<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

require_once '../Start.php';

$page = \t2\Start::init("PAGEID_CORE_DEVAREA", "Dev area");

$page->add(\service\Html::H1("Dev zone"));

$page->add(\service\Html::A_button("New module",\service\Html::href_internal("dev/new_module")));
$page->add(\service\Html::A_button("TODOs","https://github.com/experder/T2/blob/dev/dev/notes.md#current-todos",null,array("target"=>"_blank")));
$page->add(\service\Html::A_button("CSS demo",\service\Html::href_internal("dev/mod_tools/cssdemo")));

$page->add(\service\Html::H2("Tools"));
//TODO:href_internal_mod
$page->add(\service\Html::A_button("Convert TODOs",\service\Html::href_internal("dev/mod_tools/convert_todos")));

$page->send_and_quit();
