<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\dev;

require_once '../Start.php';
require_once ROOT_DIR . '/core/service/Markdown.php';

use t2\core\service\Markdown;
use t2\Start;

$page = Start::init("PAGEID_CORE_DEV_NOTES", "Dev notes");

$text = Markdown::file_to_string('notes.md');

$page->add($text);

$page->send_and_quit();