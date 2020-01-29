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
use t2\core\service\Config;
use t2\core\service\Request;
use t2\Start;

/**
 * Class Includes
 * @package t2\core\service\includes
 * TODO: https://github.com/blueimp/jQuery-File-Upload
 */
class Includes {

	private static $host_includes = true;

	/**
	 * https://parsedown.org/
	 * https://github.com/erusev/parsedown
	 * https://github.com/erusev/parsedown/releases/tag/1.7.4
	 * https://github.com/erusev/parsedown/archive/1.7.4.zip
	 */
	public static function php_parsedown174(){
		self::do_include_php("id_parsedown174",
			'parsedown-1.7.4/Parsedown.php',
			'https://github.com/erusev/parsedown/archive/1.7.4.zip'
		);
	}

	/**
	 * https://jquery.com/
	 * https://jquery.com/download/
	 * https://code.jquery.com/jquery-3.4.1.min.js
	 * https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js
	 * @param Page $page
	 */
	public static function js_jquery341(Page $page){
		self::do_add_js($page, "JS_ID_JQUERY",
			'jquery.min.js',
			'https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'
			, 'jquery341'
		);
	}

	private static $working = false;

	protected static function do_add_js(Page $page, $id, $file0, $download, $subdir = null){
		if($page->is_js_set($id)){
			return;
		}
		if (!self::$host_includes || self::$working || !defined('PROJECT_ROOT')) {
			$page->add_javascript($id, $download);
			return;
		}
		$include_dir = PROJECT_ROOT . '/includes';
		if($subdir){
			$file0 = $subdir.'/'.$file0;
		}
		$file = $include_dir.'/'.$file0;
		if (file_exists($file)){
			$page->add_javascript($id, Config::cfg_http_project()."/includes/".$file0);
			return;
		}
		self::$working = true;
		self::do_download($id, $download, $page, $subdir);
		if (file_exists($file)){
			Page::$compiler_messages[] = new Message(Message::TYPE_CONFIRM, "Installed include \"$file0\".");
			$page->add_javascript($id, Config::cfg_http_project()."/includes/".$file0);
			return;
		}
		Page::abort("Error", array(
			new Message(Message::TYPE_ERROR, "Couldn't install include \"$file0\" :-("),
		));
	}

	protected static function do_include_php($id, $file0, $download=null){
		$include_dir = PROJECT_ROOT . '/includes';
		$file = $include_dir.'/'.$file0;
		if (file_exists($file)){
			/** @noinspection PhpIncludeInspection */
			require_once $file;
			return true;
		}
		if($download!==null){
			self::do_download($id, $download);
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

	protected static function do_download($id, $download, $page=null, $subdir = null){

		//Show message while downloading:
		if (!Request::cmd("doload_$id") && !Start::is_type(Start::TYPE_AJAX)) {
			if($page===null){
				$page=Page::get_singleton();
			}
			self::js_jquery341($page);
			$page->add_inline_js("$(function(){
				document.getElementById(\"id_hiddenformredirect\").submit();
			})");
			$form = new Form("doload_$id","",false,"post",array('id'=>'id_hiddenformredirect'));
			Page::abort("Downloading...", array(
				new Message(Message::TYPE_INFO, "Downloading \"$download\"...".$form),
			));
		}

		$filename = basename($download);
		$extension = pathinfo($download, PATHINFO_EXTENSION);
		$target_dir = PROJECT_ROOT . '/includes';
		if($subdir){
			$target_dir .= '/' . $subdir;
		}
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
				$ok = $zip->extractTo(PROJECT_ROOT . '/includes');
				$zip->close();
				if ($ok){
					unlink($target);
				}
			}
		}

	}

}