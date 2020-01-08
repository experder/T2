<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

/*
require_once ROOT_DIR . '/service/Templates.php';
 */

namespace service;

require_once ROOT_DIR . '/service/Strings.php';
require_once ROOT_DIR . '/service/Files.php';

use core\Error;


class Templates {

	/**
	 * Loads a template file, fills in the values and returns the content as a string.
	 * Comments marked as follows will be removed:
	 * &#47;&#42;&#42;TPLDOCSTART This comment will be removed TPLDOCEND&#42;&#47;
	 * @param string $file
	 * @param array  $replacements
	 * @return string
	 */
	public static function load($file, $replacements) {
		if (!file_exists($file)) {
			Error::quit("Template file \"$file\" not found!");
		}

		//Read template file:
		$content = file_get_contents($file);

		if ($content === false) {
			Error::quit("Template file \"$file\" could not be loaded.");
		}

		//Replacements:
		$content = Strings::replace_byArray($content, $replacements);

		//Remove TPLDOC:
		/** Explanation of the RegEx: http://gitfabian.github.io/Tethys/php/regex.html */
		$content = preg_replace("/\\/\\*\\*TPLDOCSTART.*?TPLDOCEND\\*\\/\\R?/s", "", $content);

		return $content;
	}

	public static function create_file($target_file, $template_file, $keyVals) {
		$content = self::load($template_file, $keyVals);
		$success = Files::save($target_file, $content, false, false);
		if($success===false){
			new Error("Couldn't store file \"$target_file\". Please check rights.", Error::TYPE_UNKNOWN, true, 1, true);
		}
	}

}