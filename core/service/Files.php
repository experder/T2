<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/
/*
require_once ROOT_DIR . '/core/service/Files.php';
 */

namespace service;


use t2\core\Error;

class Files {

	/**
	 * Saves a string to a file.
	 * @param string $filename
	 * @param string $content
	 * @param bool $append
	 * @param bool $halt_on_error
	 * @return bool|int the number of bytes written, or <b>FALSE</b> on error.
	 */
	public static function save($filename, $content, $append = false, $halt_on_error=true) {
		/** Explanation of the file params: http://gitfabian.github.io/Tethys/php/files.html */
		$file = @fopen($filename, $append ? "a" : "w");
		$success = false;
		if ($file !== false) {
			$success = fwrite($file, $content);
			fclose($file);
		}
		if ($success === false && $halt_on_error) {
			Error::quit("Failure on storing file \"$filename\"!", 1);
		}
		return $success;
	}

	public static function relative_path($from, $to){

		//https://www.php.net/manual/de/function.realpath.php#105876

		//https://stackoverflow.com/a/2638272
		// some compatibility fixes for Windows paths
		$from = str_replace('\\', '/', $from);
		$to   = str_replace('\\', '/', $to);
		$from = is_dir($from) ? rtrim($from, '\/') . '/' : $from;
		$to   = is_dir($to)   ? rtrim($to, '\/') . '/'   : $to;

		$from     = explode('/', $from);
		$to       = explode('/', $to);
		$relPath  = $to;

		foreach($from as $depth => $dir) {
			// find first non-matching dir
			if($dir === $to[$depth]) {
				// ignore this directory
				array_shift($relPath);
			} else {
				// get number of remaining dirs to $from
				$remaining = count($from) - $depth;
				if($remaining > 1) {
					// add traversals up to first matching dir
					$padLength = (count($relPath) + $remaining - 1) * -1;
					$relPath = array_pad($relPath, $padLength, '..');
					break;
				} else {
					$relPath[0] = './' . $relPath[0];
				}
			}
		}
		$rel = implode('/', $relPath);
		return rtrim($rel, '\/');

//		//Remove everything to the root (Windows: Drive ("C:/"), Linux: root ("/")):
//		/** Explanation of the RegEx: http://gitfabian.github.io/Tethys/php/regex.html */
//		$from = preg_replace("/^.*?\\//", "", $from);
//		$to = preg_replace("/^.*?\\//", "", $to);
//		echo $from."<br>";
//		echo $to."<br>";
	}


}