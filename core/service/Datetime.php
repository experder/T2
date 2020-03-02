<?php

namespace t2\core\service;


class Datetime {

	/**
	 * @param int    $seconds
	 * @param string $minus_sign
	 * @return string
	 */
	public static function format_delta($seconds, $minus_sign="-&nbsp;") {
		if($seconds<0){
			$sign=$minus_sign;
			$seconds=-$seconds;
		}else{
			$sign="";
		}

		if(($days=floor($seconds/86400))>0){
			$hours = round(($seconds-($days*86400))/3600);
			$hours = $hours?" and $hours ".($hours==1?"hour":"hours"):"";
			return "$sign$days ".($days==1?"day":"days").$hours;
		}

		if(($hours=floor($seconds/3600))>0){
			$minute = round(($seconds-($hours*3600))/60);
			$minutes = $minute?':'.Strings::leading_zeroes($minute):"";
			return "$sign$hours$minutes ".($hours==1&&$minute<30?"hour":"hours");
		}

		if(($minutes=floor($seconds/60))>0){
			$second = $seconds-($minutes*60);
			$seconds = $second?':'.Strings::leading_zeroes($second):"";
			return "$sign$minutes$seconds ".($minutes==1&&$second<30?"minute":"minutes");
		}

		return "$sign$seconds seconds";
	}

}