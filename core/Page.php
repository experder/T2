<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


namespace core;

require_once ROOT_DIR . '/core/Html.php';
require_once ROOT_DIR . '/core/Stylesheet.php';
require_once ROOT_DIR . '/core/Echoable.php';
require_once ROOT_DIR . '/service/User.php';

use service\Config;
use service\User;
use tethys_root\Start;

class Page {

	/** @var Page $singleton */
	static private $singleton = null;

	/**
	 * @var string $id Unique string used to address page in navigation and CSS.
	 */
	private $id;

	/**
	 * @var string $title The HTML title tag value.
	 */
	private $title;

	/**
	 * @var Message[] $messages
	 */
	private $messages = array();

	/**
	 * @var Message[] $compiler_messages pre-init messages
	 */
	public static $compiler_messages = array();

	/**
	 * @var Stylesheet[] $stylesheets
	 */
	private $stylesheets = array();

	/**
	 * @var string[]
	 */
	private $javascripts = array();

	/**
	 * @var string|null $focus_field_id
	 */
	private $focus_field_id = null;

	/**
	 * @param string|null $focus_field_id
	 */
	public function set_focusFieldId($focus_field_id) {
		$this->focus_field_id = $focus_field_id;
	}

	private $inline_js = "";

	private $html_nodes = array();

	/**
	 * @param string $id
	 * @param string $title
	 */
	public function __construct($id, $title) {
		$this->reset($id, $title);
	}

	public function reset($pageId, $title) {
		$this->id = $pageId;
		$this->title = $title;
	}

	/**
	 * @return Page|false
	 */
	public static function get_singleton($halt_on_error = true) {
		if (self::$singleton === null) {
			if ($halt_on_error) {
				Error::quit("Please initialize Page singelton first:\n\$page = \\core\\Page::init(\"PAGEID_MYMODULE_MYPAGE\", \"My page\");", 1);
			} else {
				return false;
			}
		}
		return self::$singleton;
	}

	/**
	 * @param string $id
	 * @param string $title
	 * @return Page
	 */
	public static function init($id, $title) {

		if (self::$singleton !== null) {
			Error::quit("Page is already initialized!", 1);
		}

		self::$singleton = new Page($id, $title);
		return self::$singleton;
	}

	public function get_id() {
		return $this->id;
	}

	/**
	 * @param Echoable|string $node
	 */
	public function add($node) {
		$this->html_nodes[] = $node;
	}

	/**
	 * @see Message
	 * @param int    $type [Message::TYPE_ERROR|Message::TYPE_INFO|Message::TYPE_INFO]
	 * @param string $message
	 */
	public function add_message($type, $message) {
		$msg = new Message($type, $message);
		$this->messages[] = $msg;
	}

	public function add_inline_js($js) {
		$this->inline_js.=$js."\n";
	}

	public function get_dev_stats(){
		$db_stats = Database::get_dev_stats();
		$dev_stats = new Html("div", "\n\t".$db_stats ."\n\t". Start::get_dev_stats()."\n"
			, array("class" => "dev_stats noprint"));
		return "\n".$dev_stats."\n";
	}

	/**
	 * Builds and sends the HTML page.
	 */
	public function send_and_quit() {

		$title = $this->get_title();
		$messages = $this->get_message_html();
		$css_html = $this->get_css_html();

		$dev_stats = "";
		if (Config::$DEVMODE) {
			$dev_stats = $this->get_dev_stats();
		}

		$js_html = $this->get_js_html();

		// @formatter:off
		echo "<!DOCTYPE html>\n"
			."<html>\n"
				."<head>\n"
					."\t<meta charset=\"UTF-8\">\n"
					."\t<title>$title</title>\n"
					.$css_html
					.$js_html
				."</head>\n"
				."<body id='$this->id'>\n"
					.$messages
					."<div class='body_inner'>\n";
						$this->get_body(true);
						echo "\n"
					."</div>$dev_stats"
				."</body>\n"
			."</html>";
		// @formatter:on

		exit;

	}

	public function add_js_jquery341(){
		$this->add_javascript("JS_ID_JQUERY", "https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js");
	}

	private function get_js_html(){
		$html="";

		/*
		 * External scriptfiles
		 */
		foreach ($this->javascripts as $javascript){
			$html.="\t<script src=\"$javascript\" type=\"text/javascript\"></script>\n";
		}

		/*
		 * Inline script
		 */
		if($this->inline_js){
			$html.="\t<script language='JavaScript'>\n$this->inline_js\t</script>\n";
		}

		return $html;
	}

	public function add_stylesheet($id, Stylesheet $stylesheet){
		$this->stylesheets[$id] = $stylesheet;
	}

	public function add_javascript($id, $url){
		$this->javascripts[$id] = $url;
	}

	private function get_css_html() {
		$stylesheets = array();
		$style = Config::get_value_core("STYLE");
		if(in_array($style,array("bare")) && defined('HTTP_ROOT') ){

			//Installer:
			if(!defined('HTTP_ROOT')){
				define("HTTP_ROOT", '.');//Geht nur, wenn Installer verwendet wird / relativer Pfad bekannt ist.
			}

			$stylesheets["CSS_ID_ALL"]=new Stylesheet(HTTP_ROOT."/style/$style/all.css");
			$stylesheets["CSS_ID_PRINT"]=new Stylesheet(HTTP_ROOT."/style/$style/print.css", Stylesheet::MEDIA_PRINT);
		}
		foreach ($this->stylesheets as $key => $stylesheet){
			$stylesheets[$key] = $stylesheet;
		}
		$html = "";
		foreach ($stylesheets as $stylesheet){
			$html.="\t<link href=\"".$stylesheet->get_url()."\" rel=\"stylesheet\" type=\"text/css\" media=\"".$stylesheet->get_media()."\"/>\n";;
		}
		return $html;
	}

	private function get_message_html() {
		$html = "";
		/**
		 * @var Message[] $all_messages
		 */
		$all_messages = array_merge(self::$compiler_messages, $this->messages);
		foreach ($all_messages as $message) {
			$css_class = $message->get_type_cssClass();
			$html .= "\n\t<div class='message $css_class'>" . $message->get_message() . "</div>";
		}
		if($html){
			$html = "<div class='messages noprint'>$html\n</div>\n";
		}
		return $html;
	}

	private function get_title() {
		$project = Config::get_value_core("PROJECT_TITLE");
		$title = $this->title." - $project";
		return $title;
	}

	/**
	 * @param bool $echo
	 * @return true|string
	 */
	private function get_body($echo = false) {
		$body = "";
		foreach ($this->html_nodes as $node) {
			if (is_string($node)
			||$node instanceof Echoable
			) {
				if ($echo) {
					echo $node;
				} else {
					$body .= $node;
				}
			}
		}
		if ($echo) {
			return true;
		} else {
			return $body;
		}

	}

}