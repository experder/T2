<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


namespace t2\core\service\includes;


use t2\core\Error_;
use t2\core\form\Form;
use t2\core\Message;
use t2\core\Page;
use t2\core\service\Request;

class Includes {

	private static $host_includes = true;

	public static function js_jquery341(Page $page){
		if(self::$host_includes){
			//TODO(1):include jquery
		}else{
			$page->add_javascript("JS_ID_JQUERY", "https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js");
		}
	}

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

			//Show message while downloading:
			if (!Request::cmd('doload')) {
				Page::get_singleton()->add_inline_js("$(function(){
					document.getElementById(\"id_hiddenformredirect\").submit();
				})");
				$form = new Form("doload","",false,"post",array('id'=>'id_hiddenformredirect'));
				Page::abort("Downloading", array(
					new Message(Message::TYPE_INFO, "Downloading \"$download\"...".$form),
				));
			}

			$filename = basename($download);
			$extension = pathinfo($download, PATHINFO_EXTENSION);
			$target_dir = PROJECT_ROOT . '/includes';
			$target = $target_dir . '/' . $filename;

			//Download:
			if (!file_exists($target_dir)){
				$ok = @mkdir($target_dir, 0777, true);
				if(!$ok){
					$target_dir_parent = dirname($target_dir);
					new Error_("Couldn't create includes directory \"$target_dir\"!",0,"Try this: sudo chmod 777 '$target_dir_parent' -R");
				}
			}
			if(!is_dir($target_dir)){
				new Error_("Includes target is not a directory.");
			}
			$ok = @file_put_contents($target, fopen($download, 'r'));
			if(!$ok){
				new Error_("Couldn't store \"$target\"!",0,"Try this:\nsudo chmod 777 '$target_dir' -R");
			}

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