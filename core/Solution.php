<?php
/*
require_once ROOT_DIR . '/core/Solution.php';
 */
namespace core;


use service\Config;
use service\Strings;

class Solution {

	private $anonymous_html;
	private $user_html;
	private $admin_html;
	private $develper_html;

	/**
	 * @param string $anonymous_html
	 * @param string|null $user_html
	 * @param string|null $admin_html
	 * @param string|null $develper_html
	 */
	public function __construct($anonymous_html, $user_html=null, $admin_html=null, $develper_html=null) {
		$this->anonymous_html = $anonymous_html;
		$this->user_html = $user_html;
		$this->admin_html = $admin_html;
		$this->develper_html = $develper_html;
	}

	/**
	 * @return Solution[]
	 */
	public static function get_solutions_for_error($error_type){
		$solutions = array();

		//...

		if(count($solutions)==0){
			$solutions=self::get_default_solutions();
		}
		return $solutions;
	}

	/**
	 * @return Solution[]
	 */
	private static function get_default_solutions() {
		return array(
			new Solution(
				"",
				"Please report the following reference to your administrator: (:ID).",
				"Please report the following reference to your developer: (:REF).(:MSG)",
				"(:REF)(:MSG)----------------------------------------(:TRACE)"
			)
//		, new Solution(
//				""/*ANONYMOUS*/,
//				""/*USER*/,
//				""/*ADMIN*/,
//				""/*DEVELOPER*/
//			)
		);
	}

	public function get_html($replacements){
		if(Config::$DEVMODE){
			$solution = $this->develper_html?:$this->admin_html?:$this->user_html?:$this->anonymous_html;
		}else{
			$solution = $this->anonymous_html;
		}
		if($solution){
			$solution = Strings::replace_byArray($solution, $replacements);
		}
		return $solution;
	}

}