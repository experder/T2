<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\dev\demo;

require_once '../../Start.php';

use t2\core\Html;
use t2\core\service\Pdf;
use t2\core\service\Templates;
use t2\core\Stylesheet;
use t2\core\table\Table;
use t2\Start;

$page = Start::init_("PAGEID_DEV_PDFDEMO");
Start::getNavigation()->set_invisible();

$table = new Table(array(
	array(
		"Col1" => "Foo",
		"Col2" => "Bar",
	),
	array(
		"Col1" => "FooFoo",
		"Col2" => "FooBar",
	),
));

$pdf = new Pdf(Templates::load('pdfdemo_tpl1.html', array(
	"(:TABLE)" => $table,
)), true, "pdfdemo.css");
#print_r($pdf->get_TCPDF_dev()->getMargins());
$pdf->set_margins(10, 50, 150, 20);

if (isset($_REQUEST['out'])) {
	$pdf->send_as_response();
}

$page->add(Html::A_button("PDF", "?out"));
$page->add("<hr>");
$page->add_stylesheet('CSS_ID_ALL', new Stylesheet(false));
$page->add($pdf->to_html());

$page->send_and_quit();
