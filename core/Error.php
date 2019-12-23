<?php

namespace core;


class Error {

	private static $recusion_protection = true;

	public static function quit($message, $backtrace_depth=0){

		if(!self::$recusion_protection){
			self::quit_bare($message, 1);
		}
		self::$recusion_protection = false;

		$message .= "<pre>".self::backtrace($backtrace_depth+1)."</pre>";

		$page = Page::get_singleton();
		$page->add_message(Message::TYPE_ERROR, $message);
		$page->send_and_quit();
	}

	public static function quit_bare($message, $backtrace_depth=0){
		echo $message;
		echo "<pre>".self::backtrace($backtrace_depth+1)."</pre>";
		exit;
	}

	public static function backtrace($depth=0,$linebreak="\n"){
		$caller = "";
		$backtrace = debug_backtrace();
		if ($backtrace && is_array($backtrace)) {
			if (isset($backtrace[$depth])){
				$backtrace = array_slice($backtrace, $depth);
			}else{
				$caller.="(given depth not found)".$linebreak;
			}
			foreach ($backtrace as $row) {
				$caller .= //$row["function"] . " (".
					(isset($row["file"])?$row["file"]:"?")
					. ":"
					. (isset($row["line"])?$row["line"]:"?")
					//. ")"
					.$linebreak;
			}
		}
		if (!$caller){
			$caller = "unknown_caller\n";
		}
		return $caller;
	}


}