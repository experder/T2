<?php

/*
require_once ROOT_DIR.'/service/Strings.php';
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
		$string=str_replace("\\","\\\\", $string);
		$string=str_replace("'","\\'", $string);
		$string=str_replace("`","\\`", $string);
		$string=str_replace("\"","\\\"", $string);
		return $string;
	}

	public static function build_sql_collection($values){
		$sql_prepare = array();
		foreach ($values as $val){
			$sql_prepare[]="'".Strings::escape_sql($val)."'";
		}
		$sql = implode(",",$sql_prepare);
		return $sql;
	}

}