<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

/**
require_once ROOT_DIR . '/external/Includes.php';
 */

namespace t2\external;

//TODO(1):download third party packages (\t2\core\Includes, includes/Includes.php)
class Includes {

	/**
	 * https://parsedown.org/
	 * https://github.com/erusev/parsedown
	 * https://github.com/erusev/parsedown/releases/tag/1.7.4
	 * https://github.com/erusev/parsedown/archive/1.7.4.zip
	 */
	public static function php_parsedown174(){
		require_once ROOT_DIR . '/external/exclude/parsedown-1.7.4/Parsedown.php';
	}

	private static function do_include($id, $file, $download){

	}

}