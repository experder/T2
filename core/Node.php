<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core;

/**
 * Class Node
 * @package t2\core
 *
 * Represents a node of the HTML DOM tree.
 */
class Node {

	private $node;

	/**
	 * @param mixed $node must be of a type described in \t2\core\Node::check_type
	 * @see \t2\core\Node::check_type
	 */
	public function __construct($node) {
		self::check_type($node, 1);
		$this->node = $node;
	}

	public function __toString() {
		if (is_array($this->node)) {
			$strval = "";
			foreach ($this->node as $n) {
				$strval.=$n;
			}
			return $strval;
		}
		return strval($this->node);
	}

	/**
	 * @param mixed $node must be string or have function __toString(),
	 *                    numbers are also allowed, NULL is also allowed,
	 *                    arrays of $nodes are also allowed.
	 * @param int   $depth
	 */
	public static function check_type($node, $depth=0){
		if (is_array($node)) {
			foreach ($node as $n) {
				self::check_type($n, $depth+1);
			}
		}

		if (!is_string($node)
			&& !method_exists($node, '__toString')
			&& !is_numeric($node)
			&& !is_null($node)
		) {
			$hint = "";
			if (is_bool($node)) {
				$hint = "Booleans need to be converted to strings.\n\$page->add(\$ok?'Yes':'No');";
			}
			new Error("ERROR_INVALID_NODE", "Invalid node!", $hint, $depth+1);
		}
	}

}
