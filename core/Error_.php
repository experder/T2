<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core;


/**
 * @deprecated
 */
class Error_ extends Error {

	/**
	 * @deprecated
	 */
	public function __construct($message, $ERROR_TYPE=0, $debug_info=null, $backtrace_depth = 0, $report=true) {
		parent::__construct($ERROR_TYPE, $message, $debug_info, $backtrace_depth+1, $report);
	}

}