<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\service;

use t2\core\Pdf_adapter;

class Pdf {

	/**
	 * @var \TCPDF $TCPDF
	 */
	private $TCPDF;
	private $html_buffer = "";

	public function __construct($content = null) {
		$this->init_pdf();
		if($content!==null){
			$this->add_content($content);
		}
	}

	public function init_pdf(){
		Includes::php_tcpdf632();
		$this->TCPDF = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
		$this->TCPDF->AddPage();
	}

	public function add_content($html){
		$this->html_buffer.=$html;
	}

	public function to_html(){
		return $this->html_buffer;
	}

	private function write_html(){
		$this->TCPDF->writeHTMLCell(0, 0, '', '', $this->html_buffer, 0, 1, 0, true, '', true);
	}

	public function send_as_response(){
		$this->write_html();
		$this->TCPDF->Output();
		exit;
	}

}