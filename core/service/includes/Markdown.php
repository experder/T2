<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

/*
//equire_once ROOT_DIR . '/core/service/includes/Markdown.php';
 */

namespace t2\core\service\includes;

use t2\core\service\Html;

//equire_once ROOT_DIR . '/core/service/includes/Includes.php';

class Markdown {

	/**
	 * @param string $file
	 * @return string
	 */
	public static function file_to_string($file){
		Includes::php_parsedown174();
		$content = file_get_contents($file);

		$Parsedown = new \Parsedown();
		$content = $Parsedown->text($content);

		return self::div_wrapper($content);
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public static function string_to_string($string){
		Includes::php_parsedown174();

		$Parsedown = new \Parsedown();
		$string = $Parsedown->text($string);

		return self::div_wrapper($string);
	}

	private static function div_wrapper($content){
		$string = Html::DIV($content, 't2markdown');
		return $string;
	}

}