<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/
/*
require_once ROOT_DIR . '/dev/Debug.php';
 */

namespace t2\dev;

require_once ROOT_DIR . '/core/service/Arrays.php';

use service\Arrays;
use service\User;
use t2\core\Html;
use t2\core\Page;
use t2\Start;

class Debug {

	public static $core_queries = array(
		"load_values ( :ROOT_DIR/service/Config.php:171 )",
		"check_session ( :ROOT_DIR/service/Login.php:57 )",
		"update_session ( :ROOT_DIR/service/Login.php:86 )",
	);
	private static $core_queries_compiled = null;

	public static $queries = array();
	public static $queries_corequeries_count = 0;
	const TOO_MANY_QUERIES = 10;
	const TOO_LONG_TIME = .5/*seconds*/;

	private static $outputs = array();

	/**
	 * @param mixed $val
	 */
	public static function out($val=null){

		if($val!==null){
			$val = print_r($val, 1);
			$val.="<hr>";
		}
		$val.=self::backtrace(1);

		//Header:
		$caller = self::backtrace(1, "", false);
		$caller=str_replace('\\','/',$caller);//(Windows)
		$header=substr($caller, strrpos($caller, '/')+1);//(Last part)

		self::$outputs[$header] = $val;

	}

	private static function stats_runtime() {
		$end_time = microtime(true);
		$runtime = round($end_time - Start::dev_get_start_time(), 3);
		$confirm_class = ($runtime>=self::TOO_LONG_TIME?"confirm_bad":"confirm_good");
		return new Html("div", "<b>".$runtime . "</b> Seconds", array("class"=>"dev_stats_runtime abutton $confirm_class"));
	}

	public static function get_core_queries() {
		if (self::$core_queries_compiled === null) {
			self::$core_queries_compiled = array();
			foreach (self::$core_queries as $query) {
				self::$core_queries_compiled[] = str_replace(':ROOT_DIR', ROOT_DIR, $query);
			}
		}
		return self::$core_queries_compiled;
	}

	public static function mark_core_query_checked($value) {
		self::$core_queries_compiled = Arrays::remove_from_array_by_value(self::get_core_queries(), $value);
	}

	private static function stats_outputs(Page $page) {
		$page->add_js_core();
		$out = array();
		$c=1;
		foreach (self::$outputs as $key=>$value){
			$id="id_dev_stats_outs_detail".($c++);
			$key=new Html("span", $key, array("onclick"=>"t2_toggle_detail_zoom('$id',this);","class"=>"zoom-in"));
			$detail=new Html("pre", $value, array("style"=>"display:none;", "class"=>"dev_stats_detail", "id"=>$id));
			$out[] = "\n\t".new Html("div", $key, array("class"=>"dev_stats_outputs abutton")).$detail;
		}
		return implode("", $out);
	}

	private static function stats_db(Page $page) {
		$additional_queries_count = count(self::$queries)-self::$queries_corequeries_count;
		$confirm_class="confirm_good";
		if (
			self::$queries_corequeries_count!=count(self::$core_queries)//We missed to update core queries
			||$additional_queries_count>=self::TOO_MANY_QUERIES
		){
			$confirm_class="confirm_bad";
		}
		$querie_count = self::$queries_corequeries_count."+<b>".$additional_queries_count. "</b> Queries";
		$page->add_js_core();
		$queries=\service\Html::UL(self::$queries);
		$queries=new Html("pre", $queries, array("style"=>"display:none;", "class"=>"dev_stats_detail", "id"=>"id_dev_stats_queries_detail"));
		return new Html("div", $querie_count, array(
				"class" => "dev_stats_queries abutton zoom-in $confirm_class",
				"onclick" => "t2_toggle_detail_zoom('id_dev_stats_queries_detail',this);",
			)) . $queries;
	}

	public static function get_stats(Page $page){
		$dev_stats = new Html("div",
			"\n\t" . self::stats_db($page)
			. "\n\t" . self::stats_runtime()
			. self::stats_outputs($page)
			."\n\t".(new Html("div", 'UID:'.(User::id($page->isStandalone())?:'-/-'), array("class"=>"dev_stats_uid abutton")))
			. "\n"
			, array("class" => "dev_stats noprint"));
		return "\n".$dev_stats."\n";
	}

	/**
	 * TODO: Trenner nach der ersten Zeile
	 * TODO: Funktion mit anzeigen
	 */
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