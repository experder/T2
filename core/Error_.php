<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/
/*
require_once ROOT_DIR . '/core/Error_.php';
 */

namespace t2\core;

require_once ROOT_DIR . '/core/service/Config.php';
require_once ROOT_DIR . '/dev/Debug.php';
require_once ROOT_DIR . '/core/Page.php';

use service\Config;
use service\User;
use t2\dev\Debug;
use t2\Start;

#echo "<pre>".Debug::backtrace()."</pre>";

class Error_ {

	private static $dev_error_counter=1;

	const TYPE_UNKNOWN = 0;
	const TYPE_EXCEPTION = "ERROR_EXCEPTION";
	const TYPE_SQL = "ERROR_SQL";

	const TYPE_HOST_UNKNOWN = "ERROR_HOST_UNKNOWN";//2002/*php_network_getaddresses: getaddrinfo failed*/
	const TYPE_TABLE_NOT_FOUND = "ERROR_TABLE_NOT_FOUND";//"42S02"/*Unknown table*/

	const HR = "\n----------------------------------------\n";
	const HR_outer = "\n========================================\n";

	private $type;
	private $message;
	private $timestamp;
	private $debug_info;

	private static $recusion_protection = true;

	public function __construct($message, $ERROR_TYPE=self::TYPE_UNKNOWN, $debug_info=null, $backtrace_depth = 0, $report=true) {
		$this->type = $ERROR_TYPE;
		$this->message = $message?:"(Please enter error message)";
		$this->timestamp = time();
		$this->debug_info = $debug_info;

		if (!self::$recusion_protection) {
			self::report_havarie($backtrace_depth+1);
			exit;
		}

		self::$recusion_protection = false;

		if($report){
			$this->report($backtrace_depth+1);
		}

		self::$recusion_protection = true;
	}

	private function get_ref(){
		return ($this->type ? $this->type . '/' : '#') . $this->timestamp;
	}

	/**
	 * @return string|0
	 */
	public function getType() {
		return $this->type;
	}

	public function isType($type) {
		return $this->type==$type;
	}

	private function get_msg($debug_info=true, $backtrace=true, $htmlentities=false, $backtrace_depth=0, $minimalistic=false){
		//$minimalistic to prevent recusion
		require_once ROOT_DIR . '/dev/Debug.php';
		$msg = $this->message;
		if($debug_info && $this->debug_info){
			$msg.=self::HR.$this->debug_info;
		}
		if($backtrace){
			$msg.=self::HR.Debug::backtrace($backtrace_depth + 1);
		}
		if($htmlentities){
			$msg='<pre class="dev_error_info">'.htmlentities($msg).'</pre>';
		}
		return $msg;
	}

	private function get_msg_body($minimalistic=false, $backtrace_depth=0){
		//$minimalistic to prevent recusion
		if(Config::$DEVMODE/*TODO: OR ADMIN (!$minimalistic)*/){
			//TODO:i18n(!$minimalistic)
			$message_body = #'(' . (self::$dev_error_counter++) . ') ' .
				'An error occured: ' . $this->get_ref() . $this->get_msg(true, true, true, $backtrace_depth + 1, $minimalistic);
		}else if(User::id_(false)){
			$message_body='An error occured. Please report this reference to your administrator: '.$this->get_ref();
		}else{
			$message_body='This site is currently under maintenance. Please try again later.';
		}
		return $message_body;
	}

	public function report($backtrace_depth = 0){
		require_once ROOT_DIR . '/core/Message.php';

		$message_body = $this->get_msg_body(false, $backtrace_depth+1);

		//Write to errorlog-file(TODO):
		$file_body = self::HR_outer
			.self::meta_info_block()
			.self::HR
			.$this->get_msg(true, true, false, $backtrace_depth+1)
			.self::HR_outer
		;
		#Page::$compiler_messages[]=new Message(Message::TYPE_INFO, "<pre>".htmlentities($file_body)."</pre>");

		$msg = new Message(Message::TYPE_ERROR, $message_body);

		Page::$compiler_messages[] = $msg;

		Page::abort("ERROR", null, null, "PAGEID_CORE_ERRORARBORT");
		exit;
	}

	private function report_havarie($backtrace_depth = 0){
		echo "(ERROR OCCURED IN ERROR HANDLING)<br>";
		echo $this->get_msg_body(true, $backtrace_depth+1);
		foreach (Page::$compiler_messages as $msg){
			echo "<hr>".$msg->get_message();
		}
		exit;
	}

	private function meta_info_block() {
		require_once ROOT_DIR . '/core/service/User.php';
		$timestamp = date("Y-m-d H:i:s", $this->timestamp) . " [#" . $this->timestamp . "]";
		$ip = (isset($_SERVER) && isset($_SERVER["REMOTE_ADDR"]) && $_SERVER["REMOTE_ADDR"] ? $_SERVER["REMOTE_ADDR"] : "(IP unknonwn)");
		#$url = (isset($_SERVER["SCRIPT_URI"]) ? ("\n" . $_SERVER["SCRIPT_URI"] . (isset($_SERVER["QUERY_STRING"]) && $_SERVER["QUERY_STRING"] ? ("?" . $_SERVER["QUERY_STRING"]) : "")) : "");
		//TODO: Was ist mit SCRIPT_URI?
		$url = (isset($_SERVER["SCRIPT_NAME"]) ? ("\n" . $_SERVER["SCRIPT_NAME"] . (isset($_SERVER["QUERY_STRING"]) && $_SERVER["QUERY_STRING"] ? ("?" . $_SERVER["QUERY_STRING"]) : "")) : "");
		//TODO: Build full request
		$uid=User::id_()?:'';
		if($uid){
			$uid="[$uid] ";
		}
		return $timestamp
			. " - $uid($ip)"
			. "\n".($this->type?:"ERROR")
			. $url;
	}

	public static function from_exception(\Exception $e){
		return new Error_("[" . $e->getCode() . "] " . $e->getMessage(), self::TYPE_EXCEPTION, null, 1);
	}

	/**
	 * @deprecated TODO
	 */
	public static function quit($message, $backtrace_depth = 0) {
		new Error_($message, Error_::TYPE_UNKNOWN, "", $backtrace_depth+1);
	}

}