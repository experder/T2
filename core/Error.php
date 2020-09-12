<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core;

use t2\core\service\Config;
use t2\core\service\User;
use t2\dev\Debug;
use t2\Start;

class Error {

	const TYPE_EXCEPTION = "ERROR_EXCEPTION";
	const TYPE_SQL = "ERROR_SQL";

	const TYPE_HOST_UNKNOWN = "ERROR_HOST_UNKNOWN";//2002/*php_network_getaddresses: getaddrinfo failed*/
	const TYPE_TABLE_NOT_FOUND = "ERROR_TABLE_NOT_FOUND";//"42S02"/*Unknown table*/
	const TYPE_PDO_1049_UNKNOWN_DATABASE = "1049_Unknown_database";

	const HR = "\n----------------------------------------\n";
	const HR_outer = "\n========================================\n";

	private $type;
	private $message;
	private $timestamp;
	private $debug_info;

	private static $recusion_protection = true;

	protected $warning = false;

	/**
	 * Error constructor.
	 * @param string $ERROR_TYPE
	 * @param string $message
	 * @param string $debug_info
	 * @param int    $backtrace_depth
	 * @param bool   $report
	 */
	public function __construct($ERROR_TYPE, $message = null, $debug_info = null, $backtrace_depth = 0, $report = true) {
		$this->type = $ERROR_TYPE ?: "(Please enter error type)";
		$this->message = $message;
		$this->timestamp = time();
		$this->debug_info = $debug_info;

		if (!self::$recusion_protection) {
			self::report_havarie($backtrace_depth + 1);
			exit;
		}

		self::$recusion_protection = false;

		if ($report) {
			$this->report($backtrace_depth + 1);
		}

		self::$recusion_protection = true;
	}

	private function get_ref() {
		return ($this->type ? $this->type . '/' : '#') . $this->timestamp;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	public function isType($type) {
		return $this->type == $type;
	}

	private function get_msg($debug_info = true, $backtrace = true, $htmlentities = false, $backtrace_depth = 0) {
		$msg = array();
		if ($this->message) {
			$msg[] = $this->message;
		}
		if ($debug_info && $this->debug_info) {
			$msg[] = $this->debug_info;
		}
		if ($backtrace) {
			$msg[] = Debug::backtrace2();
		}
		$msg = implode(self::HR, $msg);
		if ($htmlentities) {
			$msg = '<pre class="dev dev_error_info">' . htmlentities($msg) . '</pre>';
		}
		return $msg;
	}

	private function get_msg_body_($backtrace_depth = 0, $htmlentities = true, $backtrace = true) {
		if (Config::$DEVMODE) {
			$intro = $this->warning ? 'Warning! ' : 'An error occured: ';
			$message_body = $intro . $this->get_ref() . "\n" . $this->get_msg(true, $backtrace, $htmlentities, $backtrace_depth + 1);
		} else if (User::id_(false)) {
			$message_body = 'An error occured. Please report this reference to your administrator: ' . $this->get_ref();
		} else {
			$message_body = 'This site is currently under maintenance. Please try again later.';
		}
		return $message_body;
	}

	public function report($backtrace_depth = 0) {
		if (Start::is_type(Start::TYPE_AJAX)) {
			$msg = $this->get_msg_body_($backtrace_depth + 1, false, true);
			$type = $this->getType();
			new Ajax_response(Ajax_response::TYPE_ERROR, $msg, $type);
			exit;
		}

		//Write to errorlog-/warnings-file (TODO(F))
		/** @noinspection PhpUnusedLocalVariableInspection */
		$file_body = self::HR_outer
			. self::meta_info_block()
			. self::HR
			. $this->get_msg(true, true, false, $backtrace_depth + 1)
			. self::HR_outer;
		#Page::$compiler_messages[]=new Message(Message::TYPE_INFO, "<pre>".htmlentities($file_body)."</pre>");

		$message_body = $this->get_msg_body_($backtrace_depth + 1);

		$msg = new Message(Message::TYPE_ERROR, $message_body);

		if ($this->warning) {

			if (Config::$DEVMODE) {

				Page::add_message($msg);

			}

		} else {

			Page::add_message($msg);

			Page::abort("ERROR", null, "PAGEID_CORE_ERRORABORT");
			exit;

		}
	}

	private function report_havarie(/** @noinspection PhpUnusedParameterInspection */
		$backtrace_depth = 0) {
		echo "(AN ERROR OCCURED IN ERROR HANDLING)";
		#echo "<br>".$this->get_msg_body_($backtrace_depth+1);
		foreach (Page::get_messages() as $msg) {
			echo "<hr>" . $msg->get_message();
		}
		exit;
	}

	private function meta_info_block() {
		$timestamp = date("Y-m-d H:i:s", $this->timestamp) . " [#" . $this->timestamp . "]";
		$ip = (isset($_SERVER) && isset($_SERVER["REMOTE_ADDR"]) && $_SERVER["REMOTE_ADDR"] ? $_SERVER["REMOTE_ADDR"] : "(IP unknonwn)");
		$request_string = self::get_request_string();
		$url = $request_string ? "\n" . $request_string : "";
		$user = self::user_info();
		return $timestamp
			. " - $user ($ip)"
			. "\n" . ($this->type ?: "ERROR")
			. $url;
	}

	private static function user_info() {
		$user = User::info();
		if (!$user) {
			return "anonymous";
		}
		$name = $user['username'];
		if ($user['display_name']) {
			$name = $user['display_name'] . " ($name)";
		}
		$id = '/' . $user['id'];
		if ($user['ref_id']) {
			$id = $user['ref_id'] . $id;
		}
		return $name . " [#$id]";
	}

	public static function get_request_string() {
		if (!isset($_SERVER)
			|| !isset($_SERVER["REQUEST_SCHEME"])
			|| !isset($_SERVER["HTTP_HOST"])
			|| !isset($_SERVER["REQUEST_URI"])
		) {
			return false;
		}
		$string = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
		return $string;
	}

	public static function from_exception(\Exception $e, $report = true, $type = self::TYPE_EXCEPTION) {
		return new Error($type, "[" . $e->getCode() . "] " . $e->getMessage(), null, 1, $report);
	}

	public static function plain_abort_($message, $backtrace_depth = 0) {
		echo $message;
		echo self::HR;
		echo Debug::backtrace($backtrace_depth + 1);
		exit;
	}

	public static function or_Warning($ERROR_TYPE, $message = null, $debug_info = null, $backtrace_depth = 0, $report = true){
		if (Config::$DEVMODE){
			return new Warning($ERROR_TYPE, $message, $debug_info, $backtrace_depth, $report);
		}
		return new Error($ERROR_TYPE, $message, $debug_info, $backtrace_depth, $report);
	}

}