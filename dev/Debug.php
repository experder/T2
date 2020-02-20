<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\dev;

use t2\core\Html;
use t2\core\Page;
use t2\core\service\Arrays;
use t2\core\service\Config;
use t2\core\service\Strings;
use t2\Start;

class Debug {

	private static $core_queries = array(
		"load_values ( :ROOT_DIR/core/service/Config.php::DEV_LVLINE )",
		"check_session ( :ROOT_DIR/core/service/Login.php:53 )",
		"update_session ( :ROOT_DIR/core/service/Login.php:84 )",
	);
	private static $core_queries_compiled = null;
	public static $queries = array();
	public static $queries_corequeries_count = 0;

	public static $core_includes = array(
		":ROOT_DIR/Start.php",
		":ROOT_DIR/core/service/Autoloader.php",
		":ROOT_DIR/config.php",
		":ROOT_DIR/core/Database.php",
		":ROOT_DIR/core/service/Config.php",
		":ROOT_DIR/core/service/Strings.php",
		":ROOT_DIR/core/service/User.php",
		":ROOT_DIR/core/service/Login.php",
		":ROOT_DIR/core/Page.php",
		":ROOT_DIR/core/Stylesheet.php",
		":ROOT_DIR/api/Navigation.php",
		":ROOT_DIR/core/mod/Core_navigation.php",
		//Debug mode:
		":ROOT_DIR/dev/Debug.php",
		":ROOT_DIR/core/service/Arrays.php",
		":ROOT_DIR/core/Html.php",
		":ROOT_DIR/core/service/Includes.php",
		":ROOT_DIR/core/service/Files.php",
		//TODO: $core_includes: Plus navigation of each module
	);
	private static $core_includes_compiled = null;
	public static $includes = array();

	const TOO_MANY_QUERIES = 10;
	const TOO_MANY_INCLUDES = 21;
	const TOO_LONG_TIME = .5/*seconds*/
	;
	const TOO_MUCH_MEMORY = .25/*percent*/
	;

	private static $outputs = array();

	/**
	 * @param mixed       $val
	 * @param string|null $header
	 */
	public static function out($val = null, $header=null) {

		if ($val !== null) {
			$val = print_r($val, 1);
			$val .= "<hr>";
		}
		$val .= self::backtrace(1);

		//Header:
		$caller = self::backtrace(1, "", false);
		$caller = str_replace('\\', '/', $caller);//(Windows)
		$header0 = $header;
		if($header===null){
			$header0 = substr($caller, strrpos($caller, '/') + 1);//(Last part)
		}
		$header = $header0;

		$counter = 2;
		while (isset(self::$outputs[$header])) {
			$header = $header0.'/'.($counter++);
		}
		self::$outputs[$header] = $val;

	}

	public static function get_runtime() {
		$end_time = microtime(true);
		$runtime = $end_time - Start::dev_get_start_time();
		return $runtime;
	}

	private static function stats_runtime() {
		$runtime = round(Debug::get_runtime(), 3);
		$confirm_class = ($runtime >= self::TOO_LONG_TIME ? "confirm_bad" : "confirm_good");
		return new Html("div", "<b>" . $runtime . "</b> Seconds", array("class" => "dev_stats_runtime abutton $confirm_class"));
	}

	public static function get_core_queries() {
		if (self::$core_queries_compiled === null) {
			self::$core_queries_compiled = array();
			foreach (self::$core_queries as $query) {
				$query = str_replace(':ROOT_DIR', ROOT_DIR, $query);
				$query = str_replace(':DEV_LVLINE', Config::$dev_lv_line, $query);
				self::$core_queries_compiled[] = $query;
			}
		}
		return self::$core_queries_compiled;
	}

	public static function mark_core_query_checked($value) {
		self::$core_queries_compiled = Arrays::remove_from_array_by_value(self::get_core_queries(), $value);
	}

	public static function get_core_includes() {
		if (self::$core_includes_compiled === null) {
			self::$core_includes_compiled = array();
			foreach (self::$core_includes as $d) {
				self::$core_includes_compiled[] = str_replace(':ROOT_DIR', ROOT_DIR, $d);
			}

			//The script itself:
			self::$core_includes_compiled[] = $_SERVER['SCRIPT_FILENAME'];

			//Config redirection:
			global $t2_config_file;
			if(isset($t2_config_file)){
				self::$core_includes_compiled[] = $t2_config_file;
			}

		}
		return self::$core_includes_compiled;
	}

	public static function mark_core_include_checked($value) {
		self::$core_includes_compiled = Arrays::remove_from_array_by_value(self::$core_includes_compiled, $value);
	}

	private static function stats_outputs(Page $page) {
		$page->add_js_core();
		$out = array();
		$c = 1;
		foreach (self::$outputs as $key => $value) {
			$id = "id_dev_stats_outs_detail" . ($c++);
			$key = new Html("span", $key, array("onclick" => "t2_toggle_detail_zoom('$id',this);", "class" => "zoom-in"));
			$detail = new Html("pre", $value, array("style" => "display:none;", "class" => "dev_stats_detail", "id" => $id));
			$out[] = "\n\t" . new Html("div", $key, array("class" => "dev_stats_outputs abutton")) . $detail;
		}
		return implode("", $out);
	}

	private static function stats_db(Page $page) {
		$confirm_class = "confirm_good";
		if (!$page->uses_database()) {
			return new Html("div", "<del>DB</del>", array(
				"class" => "dev_stats_queries abutton $confirm_class",
			));
		}
		$additional_queries_count = count(self::$queries) - self::$queries_corequeries_count;
		if (
			self::$queries_corequeries_count != count(self::$core_queries)//We missed to update core queries
			|| $additional_queries_count >= self::TOO_MANY_QUERIES
		) {
			$confirm_class = "confirm_bad";
		}
		$querie_count = self::$queries_corequeries_count . "+<b>" . $additional_queries_count . "</b> Queries";
		$page->add_js_core();
		$queries = Html::UL(self::$queries);
		$queries = new Html("pre", $queries, array("style" => "display:none;", "class" => "dev_stats_detail", "id" => "id_dev_stats_queries_detail"));
		return new Html("div", $querie_count, array(
				"class" => "dev_stats_queries abutton zoom-in $confirm_class",
				"onclick" => "t2_toggle_detail_zoom('id_dev_stats_queries_detail',this);",
			)) . $queries;
	}

	private static function stats_mem(Page $page) {
		$confirm_class = "confirm_good";
		$page->add_js_core();

		$mem = memory_get_peak_usage(true);
		$mem_available = ini_get('memory_limit');
		$mem_available_int = self::int_from_bytestring($mem_available);
		$percentage_used = $mem / $mem_available_int;
		if ($percentage_used > self::TOO_MUCH_MEMORY) {
			$confirm_class = "confirm_bad";
		}

		$mem_string = Strings::format_memory_binary($mem);

		$title = $mem_string;
		$detail = "Memory available: " . $mem_available;
		$detail .= "\nPeak memory usage: <b>" . Strings::format_memory(memory_get_peak_usage(false)) . "</b>";
		$detail .= "\nReal memory usage: " . $mem_string . " (" . round($percentage_used * 100, 1) . "%)";

		$detail = new Html("pre", $detail, array("style" => "display:none;", "class" => "dev_stats_detail", "id" => "id_dev_stats_mem_detail"));
		return new Html("div", $title, array(
				"class" => "dev_stats_mem abutton zoom-in $confirm_class",
				"onclick" => "t2_toggle_detail_zoom('id_dev_stats_mem_detail',this);",
			)) . $detail;
	}

	/**
	 * @param string $byteString
	 * @return int
	 *
	 * https://stackoverflow.com/questions/1336581/is-there-an-easy-way-in-php-to-convert-from-strings-like-256m-180k-4g-to
	 */
	private static function int_from_bytestring($byteString) {
		preg_match('/^\s*([0-9.]+)\s*([KMGTPE])B?\s*$/i', $byteString, $matches);
		$num = (float)$matches[1];
		switch (strtoupper($matches[2])) {
			/** @noinspection PhpMissingBreakStatementInspection */
			case 'E':
				$num = $num * 1024;
			/** @noinspection PhpMissingBreakStatementInspection */
			case 'P':
				$num = $num * 1024;
			/** @noinspection PhpMissingBreakStatementInspection */
			case 'T':
				$num = $num * 1024;
			/** @noinspection PhpMissingBreakStatementInspection */
			case 'G':
				$num = $num * 1024;
			/** @noinspection PhpMissingBreakStatementInspection */
			case 'M':
				$num = $num * 1024;
			case 'K':
				$num = $num * 1024;
		}

		return intval($num);
	}

	private static function stats_inc(Page $page) {
		$confirm_class = "confirm_good";
		$page->add_js_core();

		$includes = get_included_files();
		$includes_count = count($includes);
		$includes_count_core = count(Debug::get_core_includes());

		//Core includes:
		$includes_formatted = array();
		$corecount = 0;
		foreach ($includes as $include) {
			$include = str_replace('\\', '/', $include);
			if (in_array($include, Debug::get_core_includes())) {
				$corecount++;
				Debug::mark_core_include_checked($include);
				$include = "<i class='core_include_class'>$include</i>";
			}
			$includes_formatted[] = $include;
		}
		$additional_includes = $includes_count - $corecount;

		$unused = "";
		if ($corecount != $includes_count_core) {//We missed to update core includes
			$unused = "Unused:" . (self::$core_includes_compiled ? Html::UL(self::$core_includes_compiled) : " -");
			$confirm_class = "confirm_bad";
		}
		if ($additional_includes >= self::TOO_MANY_INCLUDES) {
			$confirm_class = "confirm_bad";
		}

		$title = $corecount . "+<b>" . $additional_includes . "</b> Includes";
		$detail = Html::UL($includes_formatted);

		$detail .= $unused;

		$detail = new Html("pre", $detail, array("style" => "display:none;", "class" => "dev_stats_detail", "id" => "id_dev_stats_inc_detail"));
		return new Html("div", $title, array(
				"class" => "dev_stats_inc abutton zoom-in $confirm_class",
				"onclick" => "t2_toggle_detail_zoom('id_dev_stats_inc_detail',this);",
			)) . $detail;
	}

	public static function get_stats(Page $page) {
		$dev_stats = new Html("div",
			"\n\t" . self::stats_db($page)
			. "\n\t" . self::stats_runtime()
			. "\n\t" . self::stats_mem($page)
			. "\n\t" . self::stats_inc($page)
			#."\n\t".(new Html("div", 'UID:'.(User::id($page->isStandalone())?:'-/-'), array("class"=>"dev_stats_uid abutton")))
			. self::stats_outputs($page)
			. "\n"
			, array("class" => "dev_stats noprint"));
		return "\n" . $dev_stats . "\n";
	}

	/**
	 * TODO(3): backtrace: Trenner nach der ersten Zeile
	 * TODO(3): backtrace: Funktion mit anzeigen
	 * @param int    $depth
	 * @param string $linebreak
	 * @param bool   $multiline
	 * @return string
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