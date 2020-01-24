<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

/*
require_once ROOT_DIR . '/core/service/Templates.php';
 */

namespace t2\core\service;

require_once ROOT_DIR . '/core/service/Strings.php';
require_once ROOT_DIR . '/core/service/Files.php';

use t2\core\Error_;


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
		//TODO(3): file_exists($filename, $fatal=true)
		if (!file_exists($file)) {
			Error_::quit("Template file \"$file\" not found!");
		}

		//Read template file:
		$content = file_get_contents($file);

		if ($content === false) {
			Error_::quit("Template file \"$file\" could not be loaded.");
		}

		//Replacements:
		$content = Strings::replace_byArray($content, $replacements);

		//Remove TPLDOC:
		/** Explanation of the RegEx: http://gitfabian.github.io/Tethys/php/regex.html */
		$content = preg_replace("/\\/\\*\\*TPLDOCSTART.*?TPLDOCEND\\*\\/\\R?/s", "", $content);

		return $content;
	}

	/**
	 * @param string  $target_file
	 * @param string  $template_file
	 * @param array[] $keyVals
	 * @param bool    $override
	 * @param bool    $report_error
	 * @return int Errornumber
	 */
	public static function create_file($target_file, $template_file, $keyVals, $override=false, $report_error = true) {
		if(!$override && file_exists($target_file)){
			if($report_error){
				new Error_("Couldn't store file \"$target_file\". File already exists!");
			}
			return -1/*File already exists*/;
		}
		$content = self::load($template_file, $keyVals);
		$success = Files::save($target_file, $content, false, false);
		if($success===false){
			new Error_("Couldn't store file \"$target_file\". Please check rights.", 0, "Try this:\nsudo chmod 777 '".dirname($target_file)."' -R", 1);
		}
		return 0;
	}

}