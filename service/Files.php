<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


/*
require_once ROOT_DIR . '/service/Files.php';
 */

namespace service;


use core\Error;

class Files {

	/**
	 * Saves a string to a file.
	 * @param string $file
	 * @param string $content
	 * @param bool   $append
	 */
	public static function save($file, $content, $append = false) {
		/** Explanation of the file params: http://gitfabian.github.io/Tethys/php/files.html */
		$file = fopen($file, $append ? "a" : "w");
		$success = false;
		if ($file !== false) {
			$success = fwrite($file, $content);
			fclose($file);
		}
		if ($success === false) {
			Error::quit("Failure on storing file \"$file\"!");
		}
	}


}