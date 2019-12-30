<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


/*
require_once ROOT_DIR . '/core/Error.php';
 */

namespace core;

use service\Config;

class Error {

	const TYPE_UNKNOWN = 0;
	const TYPE_DB_NOT_FOUND = "DB_NOT_FOUND";
	#const TYPE_WRONG_CREDENTIALS = "WRONG_CREDENTIALS";
	const TYPE_TABLE_NOT_FOUND = "SQL_TABLE_NOT_FOUND";
	const TYPE_SQL = "SQL-Error";

	const HR = "\n=========================\n";

	private $type;
	private $message;
	private $timestamp;

	private static $recusion_protection = true;

	public function __construct($message, $type = self::TYPE_UNKNOWN, $report = true, $backtrace_depth = 0, $fatal = false) {
		if (!self::$recusion_protection) {
			self::quit_bare("(RECURSIVE ERROR)", 1);
		}
		self::$recusion_protection = false;
		$this->type = $type;
		$this->message = $message;
		$this->timestamp = time();
		$message_plus_plus = self::HR . self::meta_info_block() . self::HR . self::backtrace($backtrace_depth + 1) . self::HR . $message . self::HR;
		if ($report) {
			Page::$compiler_messages[] = $this->report($backtrace_depth+1);
		}
		if ($fatal) {
			if ($page = Page::get_singleton(false)) {
				$page->send_and_quit();
			} else {
				require_once ROOT_DIR . '/admin/Install_wizard.php';
				$page = new Page("T2_INSTWIZARD", "T2 - Fatal Error");
				$page->send_and_quit();
			}
		}
		self::$recusion_protection = true;
	}

	public function report($backtrace_depth = 0){
		$msg = new Message(Message::TYPE_ERROR, "An Error occured. Please report/see log: #" . $this->timestamp . "."
			. (Config::$DEVMODE ? "<pre class='dev_error_info' "
				. (Config::$PRE_CSS ? " style='color:gray;white-space:pre-wrap;'":"")
				. " >" . htmlentities(
					($this->type === self::TYPE_UNKNOWN ? "" : ($this->type . self::HR)) . $this->message . self::HR . self::backtrace($backtrace_depth + 1)
				#$message_plus_plus
				) . "</pre>" : "")
		);
		return $msg;
	}

	public function meta_info_block() {
		$timestamp = date("Y-m-d H:i:s", $this->timestamp) . " [#" . $this->timestamp . "]";
		$ip = (isset($_SERVER) && isset($_SERVER["REMOTE_ADDR"]) && $_SERVER["REMOTE_ADDR"] ? $_SERVER["REMOTE_ADDR"] : "(IP unknonwn)");
		$url = (isset($_SERVER["SCRIPT_URI"]) ? ("\n" . $_SERVER["SCRIPT_URI"] . (isset($_SERVER["QUERY_STRING"]) && $_SERVER["QUERY_STRING"] ? ("?" . $_SERVER["QUERY_STRING"]) : "")) : "");
		return
			$timestamp
			. " - $ip"
			. ($this->type === self::TYPE_UNKNOWN ? "" : ("\n" . $this->type))
			. $url;
	}

	public static function from_exception(\Exception $e, $report = true, $quit_on_error = false) {
		if ($e instanceof \PDOException) {
			if ($e->getCode() === 1049/*Unknown database*/) {
				return new Error($e->getMessage(), self::TYPE_DB_NOT_FOUND, $report, 0, $quit_on_error);
			}
//			if($e->getCode()===1045/*Access denied */){
//				return new Error($e->getMessage(), self::TYPE_WRONG_CREDENTIALS, $report, 0, $quit_on_error);
//			}
		}
		return new Error("[" . $e->getCode() . "] " . $e->getMessage(), self::TYPE_UNKNOWN, $report, 0, $quit_on_error);
	}

	public function get_type() {
		return $this->type;
	}

	public function get_message() {
		return $this->message;
	}

	public function get_timestamp() {
		return $this->timestamp;
	}

	public static function quit($message, $backtrace_depth = 0) {
		new Error($message, self::TYPE_UNKNOWN, true, $backtrace_depth + 1, true);
	}

	public static function quit_bare($message, $backtrace_depth = 0) {
		echo $message;
		echo "<pre>" . self::backtrace($backtrace_depth + 1) . "</pre>";
		exit;
	}

	public static function backtrace($depth = 0, $linebreak = "\n", $multiline = true) {
		$caller = array();
		$backtrace = debug_backtrace();
		if ($backtrace && is_array($backtrace)) {
			if (isset($backtrace[$depth])) {
				$backtrace = array_slice($backtrace, $depth);
			} else {
				$caller[] = "(given depth not found)";
			}
			foreach ($backtrace as $row) {
				$caller[] =
					(isset($row["file"]) ? $row["file"] : "?")
					. ":"
					. (isset($row["line"]) ? $row["line"] : "?");
				if (!$multiline) {
					return $caller[0];
				}
			}
		}
		if (!$caller) {
			$caller[] = "unknown_caller";
		}
		return implode($linebreak, $caller);
	}

}