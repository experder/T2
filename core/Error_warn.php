<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/
/*
require_once ROOT_DIR . '/core/Error_warn.php';
 */

namespace core;

require_once ROOT_DIR . '/core/Solution.php';

use service\Config;
use t2\dev\Debug;
use t2\Start;

class Error_warn {

	const TYPE_UNKNOWN = 0;
	const TYPE_DB_NOT_FOUND = "DB_NOT_FOUND";
	const TYPE_HOST_UNKNOWN = "DB_HOST_UNKNOWN";
	const TYPE_TABLE_NOT_FOUND = "SQL_TABLE_NOT_FOUND";
	const TYPE_SQL = "SQL-Error";

	const HR = "\n----------------------\n";
	const HR_outer = "\n===========================\n";

	private $type;
	private $message;
	private $timestamp;

	protected $fatal = false;

	private static $recusion_protection = true;

	public function __construct($message, $type = self::TYPE_UNKNOWN, $backtrace_depth = 0, $debug_info=null) {
		if (!self::$recusion_protection) {
			echo "(ERROR OCCURED IN ERROR HANDLING)<br>";
			echo "Please contact your administrator.";
			if(Config::$DEVMODE){
				echo "<pre>$message</pre><pre>".Debug::backtrace($backtrace_depth+1)."</pre>";
			}
			foreach (Page::$compiler_messages as $msg){
				echo "<hr><pre>".$msg->get_message()."</pre>";
			}
			exit;
		}
		self::$recusion_protection = false;
		$this->type = $type;
		$this->message = $message;
		$this->timestamp = time();
		Page::$compiler_messages[] = $this->report($backtrace_depth+1);
		if($this->fatal){
			$this->quit();
		}
		self::$recusion_protection = true;
	}

	private function report($backtrace_depth = 0){
		$message_body = "An error occured.";

		$solutions = Solution::get_solutions_for_error($this->type);
		$sol_html=array();
		foreach ($solutions as $solution){
			$body = $solution->get_html(array(
				"(:REF)" => ($this->type ? $this->type . '/' : '#') . $this->timestamp,
				"(:ID)" => '#'.$this->timestamp,
				"(:MSG)" => '<pre>'.htmlentities($this->message).'</pre>',
				"(:TRACE)" => '<pre>'.Debug::backtrace($backtrace_depth + 1).'</pre>',
			));
			if($body){
				$sol_html[] = $body;
			}
		}
		if(!$sol_html){
			$sol_html = "";
		}else if (count($sol_html)==1){
			$sol_html = " ".$sol_html[0];
		}else{
			$sol_html = \service\Html::UL($sol_html);
		}
		$message_body .= $sol_html;

		$msg = new Message(Message::TYPE_ERROR, "An error occured: ".($this->type?$this->type.'/':'#'). $this->timestamp . "."
			. (Config::$DEVMODE ? "<pre class='dev_error_info'>" . htmlentities(
					($this->type === self::TYPE_UNKNOWN ? "" : ($this->type . self::HR)) . $this->message . self::HR . Debug::backtrace($backtrace_depth + 1)
				) . "</pre>" : "")
		);
		$msg = new Message(Message::TYPE_ERROR, $message_body);
		return $msg;
	}

	private function quit(){
		if (Start::isStarted() && ($page = Page::get_singleton(false))) {
			$page->send_and_quit();
		} else {
			self::abort("ERROR");
		}
		exit;
	}

	/**
	 * @param string       $title
	 * @param Message[] $messages
	 * @param null|string   $body
	 * @param string $id
	 */
	public static function abort($title, $messages=null, $body=null, $id="PAGEID_CORE_ABORT") {
		require_once ROOT_DIR . '/core/Page_standalone.php';
		if(is_array($messages)){
			foreach ($messages as $message){
				Page::$compiler_messages[] = $message;
			}
		}
		$page = new Page_standalone($id, $title." - T2");
		if($body!==null){
			$page->add($body);
		}
		$page->send_and_quit();
		exit;
	}

	/**
	 * @deprecated Use Debug::backtrace() instead.
	 * @see Debug::backtrace()
	 */
	public static function backtrace($depth = 0, $linebreak = "\n", $multiline = true) {
		Debug::backtrace($depth+1, $linebreak, $multiline);
	}

}