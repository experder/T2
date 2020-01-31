<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\dev\demo;

require_once '../../Start.php';

use t2\core\service\Pdf;
use t2\core\service\Templates;
use t2\Start;

$page = Start::init("PAGEID_DEV_PDFDEMO", "PDF demo");

$pdf = new Pdf(Templates::load('pdfdemo_tpl1.html', array()));

#$pdf->send_as_response();

$page->add($pdf->to_html());

$page->send_and_quit();
