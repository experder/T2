<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\table;


use t2\core\Html;

class Row {

	/**
	 * @var Cell[] $cells
	 */
	private $cells = array();

	/**
	 * Row constructor.
	 * @param array $cells
	 */
	public function __construct($cells=array()) {
		$this->add_cells($cells);
	}

	/**
	 * @return array
	 */
	public function getCells() {
		return $this->cells;
	}

	public function add_cell($id, $cell){
		if($cell instanceof Cell){
			$this->cells[$id] = $cell;
			return true;
		}
		$this->cells[$id] = new Cell($cell);
		return true;
	}

	public function add_cells($cells){
		if(!$cells){
			return false;
		}
		if(is_array($cells)){
			$ok=true;
			foreach ($cells as $col => $cell){
				if(!$this->add_cell($col, $cell)){
					$ok=false;
				}
			}
			return $ok;
		}
		return false;
	}

	public function toHtml($header=null) {
		$cells = array();
		if($header){
			foreach ($header as $col => $dummy){
				if(isset($this->cells[$col])){
					$cell = $this->cells[$col];
				}else{
					$cell = new Html('td',null);
				}
				$cells[] = $cell;
			}
		}else{
			foreach ($this->cells as $cell){
				$cells[] = $cell;
			}
		}
		$tr = new Html('tr',null,null,$cells);
		return $tr;
	}

}