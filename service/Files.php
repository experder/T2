<?php

/*
require_once ROOT_DIR.'/service/Files.php';
 */
namespace service;



class Files {

	/**
	 * Saves a string to a file.
	 * @param string $file
	 * @param string $content
	 * @param bool $append
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
			\core\Error::quit("Failure on storing file \"$file\"!");
		}
	}


}