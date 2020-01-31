<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\service;

use t2\core\Error_;
use t2\core\Pdf_adapter;

class Pdf {

	/**
	 * @var \TCPDF $TCPDF
	 */
	private $TCPDF = null;
	private $html_buffer = "";

	public function __construct($content = null, $init=true, $stylesheet = null) {
		if($init){
			$this->init_pdf();
		}
		if($stylesheet){
			$this->add_stylesheet($stylesheet, 1);
		}
		if($content!==null){
			$this->add_content($content);
		}
	}

	private function add_stylesheet($stylesheet, $depth=0){
		$css = Files::get_contents($stylesheet, $depth+1);
		$html = "<style>$css</style>";
		$this->add_content($html);
	}

	public function init_pdf(){
		Includes::php_tcpdf632();

		$pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$this->TCPDF = $pdf;

		$this->set_margins(10);
	}

	public function set_margins($top, $right=-1, $bottom=-1, $left=-1){
		if($left===-1){
			$left = $top;
		}
		if($bottom===-1){
			$bottom = $top;
		}
		$this->get_TCPDF()->SetMargins($left, $top, $right, false);
		$this->get_TCPDF()->SetAutoPageBreak($this->get_TCPDF()->getAutoPageBreak(), $bottom);
	}

	/**
	 * @deprecated For development purpose only.
	 */
	public function get_TCPDF_dev(){
		return $this->get_TCPDF();
	}

	private function get_TCPDF($depth=0){
		if($this->TCPDF===null){
			new Error_("PDF not initialized!",0,null,$depth+1);
		}
		return $this->TCPDF;
	}

	public function add_content($html){
		$this->html_buffer.=$html;
	}

	public function to_html(){
		$margins = $this->get_TCPDF()->getMargins();
		#Debug::out($margins);
		$margins_css = $margins['top'] . "mm " . $margins['right'] . "mm " . $margins['bottom'] . "mm " . $margins['left'] . "mm";
		$width = 210/*mm(A4)*/-($margins['left']*1)-($margins['right']*1);
		$html = "
<style>
	div.pdf_preview_outer{
		background: #f0eef9;
		padding:25px;
	}
	div.pdf_preview{
		background: white;
		width:{$width}mm;
		margin:0 auto;
		padding: $margins_css;
	}
</style>";
		$html.= "<div class='pdf_preview_outer'><div class='pdf_preview'>$this->html_buffer</div></div>";
		return $html;
	}

	public function send_as_response($depth=0){
		$pdf = $this->get_TCPDF($depth+1);
		$pdf->AddPage();
		$pdf->writeHTML($this->html_buffer);
		$pdf->Output();
		exit;
	}

}