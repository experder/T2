<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\table;

use t2\core\Html;

class Table {

	public $no_data_info = "<div class='t2-no-table-data'>No data.</div>";

	/**
	 * @var Row[] $rows
	 */
	private $rows = array();
	private $headers = null;

	public function __construct($data = array()) {
		$this->add_rows($data);
	}

	/**
	 * @param Row|array $row
	 * @return bool
	 */
	public function add_row($row) {
		if (!$row) {
			return false;
		}
		if ($row instanceof Row) {
			$this->rows[] = $row;
			return true;
		}
		if (is_array($row)) {
			$this->rows[] = new Row($row);
			return true;
		}
		return false;
	}

	public function add_rows($rows) {
		if (!$rows) {
			return false;
		}
		if (is_array($rows)) {
			$ok = true;
			foreach ($rows as $row) {
				if (!$this->add_row($row)) {
					$ok = false;
				}
			}
			return $ok;
		}
		return $this->add_row($rows);
	}

	public function __toString() {

		//TABLE
		$html_table = new Html('table', null);
		$html_table->addChild(new Html('thead', null, null, array(
			$thead = new Html('tr', null)
		)));
		$html_table->addChild($tbody = new Html('tbody', null));

		//HEAD
		$headers = $this->get_headers();
		if ($headers === false) {
			return $this->no_data_info;
		}
		foreach ($headers as $col => $cell) {
			if ($cell instanceof Cell) {
				$cell->tag = 'th';
				$thead->addChild($cell);
			} else {
				if ($cell === true) {
					$cell = $col;
				}
				$thead->addChild(new Html('th', $cell));
			}
		}

		//BODY
		foreach ($this->rows as $row) {
			$tbody->addChild($row->toHtml($this->headers));
		}

		return $html_table->__toString();
	}

	public function set_headers($headers) {
		$this->headers = $headers;
	}

	private function get_headers() {
		if ($this->headers === null) {
			if (($row_one = reset($this->rows)) === false) {
				return false;
			}
			$headers = array();
			foreach ($row_one->getCells() as $col => $cell) {
				$headers[$col] = $col;
			}
			return $headers;
		}
		return $this->headers;
	}

}