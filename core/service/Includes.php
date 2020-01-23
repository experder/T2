<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

/**
require_once ROOT_DIR . '/core/service/Includes.php';
 */

namespace t2\core\service;

require_once ROOT_DIR . '/core/Message.php';

use t2\core\Error_;
use t2\core\Message;
use t2\core\Page;

class Includes {

	/**
	 * https://parsedown.org/
	 * https://github.com/erusev/parsedown
	 * https://github.com/erusev/parsedown/releases/tag/1.7.4
	 * https://github.com/erusev/parsedown/archive/1.7.4.zip
	 */
	public static function php_parsedown174(){
		self::do_include(
			'parsedown-1.7.4/Parsedown.php',
			'https://github.com/erusev/parsedown/archive/1.7.4.zip'
		);
	}

	protected static function do_include($file0, $download=null){
		$include_dir = PROJECT_ROOT . '/includes';
		$file = $include_dir.'/'.$file0;
		if (file_exists($file)){
			/** @noinspection PhpIncludeInspection */
			require_once $file;
			return true;
		}
		if($download!==null){
			$filename = basename($download);
			$extension = pathinfo($download, PATHINFO_EXTENSION);
			$target = PROJECT_ROOT . '/includes/' . $filename;

			//Download:
			file_put_contents($target, fopen($download, 'r'));

			//Unzip:
			if(strtolower($extension)=='zip'){
				$zip = new \ZipArchive();
				$res = $zip->open($target);
				if($res===true){
					$zip->extractTo(PROJECT_ROOT . '/includes');
					$zip->close();
					if (file_exists($file)){
						unlink($target);
					}
				}
			}
		}
		if (file_exists($file)){
			Page::$compiler_messages[] = new Message(Message::TYPE_CONFIRM, "Installed include \"$file0\".");
			/** @noinspection PhpIncludeInspection */
			require_once $file;
			return true;
		}
		new Error_("Couldn't install include \"$file0\" :-(");
		return false;
	}

}