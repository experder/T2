<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


/*
require_once ROOT_DIR . '/core/service/Strings.php';
 */

namespace service;


class Strings {

	/**
	 * Escapes quotes with htmlentities.
	 * Escapes single quotes, double quotes and the ampersand.
	 * Examples:
	 *      "<tag value = '".escape_value_html($value)."' />"
	 *      "<tag value = \"".escape_value_html($value)."\" />"
	 *      '<tag value = "'.escape_value_html($value).'" />'
	 * @param string $value
	 * @return string
	 */
	public static function escape_value_html($value) {
		return self::replace_byArray($value, array(
			"&" => "&amp;",
			"\"" => "&quot;",
			"'" => "&apos;",
		));
	}

	/**
	 * Other syntax for the str_replace function.
	 * @param array  $substitutions An associative array containing the substitutions.
	 * @param string $string
	 * @return mixed
	 */
	public static function replace_byArray($string, $substitutions) {
		return str_replace(array_keys($substitutions), array_values($substitutions), $string);
	}

	public static function escape_sql($string) {
		$string = str_replace("\\", "\\\\", $string);
		$string = str_replace("'", "\\'", $string);
		$string = str_replace("`", "\\`", $string);
		$string = str_replace("\"", "\\\"", $string);
		return $string;
	}

	public static function build_sql_collection($values) {
		$sql_prepare = array();
		foreach ($values as $val) {
			$sql_prepare[] = "'" . Strings::escape_sql($val) . "'";
		}
		$sql = implode(",", $sql_prepare);
		return $sql;
	}

	/**
	 * (returns bigger values than format_memory_binary)
	 * @param number $bytes
	 * @return string
	 */
	public static function format_memory($bytes) {
		$string = self::magnitude($bytes, array(' Byte',' kB',' MB',' GB',' TB',' PB',' EB'));
		#$string = $bytes." &rarr; ".$string;
		return $string;
	}

	/**
	 * @param number $bytes
	 * @return string
	 */
	public static function format_memory_binary($bytes) {
		$string = self::magnitude($bytes, array(' Byte',' KB',' MiB',' GiB',' TiB',' PiB',' EiB'), 1024);
		#$string = $bytes." &rarr; ".$string;
		return $string;
	}

	private static function magnitude($number, $units, $steps=1000, $precision=2, $cycles=0) {
		$quotient = $number/$steps;
		if($quotient<1){
			return round($number, $precision).(isset($units[$cycles])?$units[$cycles]:"*$steps^$cycles".$units[0]);
		}
		return self::magnitude($quotient, $units, $steps, $precision, $cycles+1);
	}

}
