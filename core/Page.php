<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/
/*
require_once ROOT_DIR . '/core/Page.php';
 */


namespace t2\core;

require_once ROOT_DIR . '/core/Stylesheet.php';
require_once ROOT_DIR . '/core/service/Config.php';
//require_once ROOT_DIR . '/core/Html.php';
//require_once ROOT_DIR . '/core/Echoable.php';
//require_once ROOT_DIR . '/core/service/User.php';
//require_once ROOT_DIR . '/dev/Debug.php';
//require_once ROOT_DIR . '/core/service/Files.php';
//require_once ROOT_DIR . '/core/Error_warn.php';

use admin\Install_wizard;
use service\Config;
use service\Files;
use t2\dev\Debug;
use t2\Start;

/**
 * TODO: $page->set_focusFieldId
 */
class Page {

	/** @var Page $singleton */
	static private $singleton = null;

	protected $use_database;
	private static $recusion_protection_abort = true;
	public static $prompting_http_root = false;

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

	private $HTTP_ROOT = null;

	/**
	 * @param string $id
	 * @param string $title
	 */
	public function __construct($id, $title, $use_database=true) {
		if($use_database && Database::get_singleton(false)===false){
			$use_database=false;
		}
		$this->use_database = $use_database;
		$this->reset($id, $title);
		$this->HTTP_ROOT();
	}

	public function reset($pageId, $title) {
		$this->id = $pageId;
		$this->title = $title;
	}

	public function uses_database(){
		return $this->use_database;
	}

	/**
	 * @return Page|false
	 */
	public static function get_singleton($halt_on_error = true) {
		if (self::$singleton === null) {
			if ($halt_on_error) {
				new Error_("Please initialize Page singelton first","NO_PAGE","\$page = \\t2\\core\\Page::init(\"PAGEID_MYMODULE_MYPAGE\", \"My page\");",1);
			} else {
				return false;
			}
		}
		return self::$singleton;
	}

	/**
	 * @deprecated Use Start::init() instead!
	 * @see Start::init()
	 */
	public static function init($id, $title) {
		return Start::init($id, $title);
	}

	/**
	 * @param string $id
	 * @param string $title
	 * @return Page
	 */
	public static function init2($id, $title) {

		if (self::$singleton !== null) {
			Error_::quit("Page is already initialized!", 1);
		}

		self::$singleton = new Page($id, $title);

		return self::$singleton;
	}

	public function HTTP_ROOT(){
		if($this->HTTP_ROOT===null){
			if(!$this->use_database
				|| self::$prompting_http_root//We're just prompting for it.
			){

				//Guess HTTP_ROOT:

				require_once ROOT_DIR . '/core/service/Files.php';
				$this->HTTP_ROOT=Files::relative_path($_SERVER["SCRIPT_FILENAME"], ROOT_DIR);
			}else{

				//Load HTTP_ROOT:

				$this->HTTP_ROOT = Config::get_value_core("HTTP_ROOT", false);
				if($this->HTTP_ROOT===false){

					//Prompt HTTP_ROOT:

					require_once ROOT_DIR . '/dev/Install_wizard.php';
					$this->HTTP_ROOT=Install_wizard::prompt_http_root();
					if($this->HTTP_ROOT===false){
						new Error_("Could not set HTTP_ROOT.");
					}
					Config::set_value('HTTP_ROOT', $this->HTTP_ROOT);
					Page::$compiler_messages[]=new Message(Message::TYPE_CONFIRM, "HTTP_ROOT set to: \"$this->HTTP_ROOT\"");
				}
			}
		}
		return $this->HTTP_ROOT;
	}
	public static function HTTP_ROOT_($page=null){
		if($page===null){
			$page = self::get_singleton();
		}
		return $page->HTTP_ROOT();
	}

	public function get_id() {
		return $this->id;
	}

	/**
	 * @param mixed $node must be string or have function __toString()
	 */
	public function add($node) {
		if(is_array($node)){
			foreach ($node as $n){
				$this->add($n);
			}
			return;
		}

		if (!is_string($node)
			&& !method_exists($node, '__toString')
			&& !is_numeric($node)
			&& !is_null($node)
		){
			new Error_("Invalid node", "ERROR_INVALID_NODE", print_r($node,1), 1);
		}

		$this->html_nodes[] = $node;
	}

	public function add_p($content, $params=array()) {
		$this->add(\service\Html::P($content, null, $params));
	}

	/**
	 * @see Message
	 * @param int    $type [Message::TYPE_ERROR|Message::TYPE_INFO|Message::TYPE_INFO]
	 * @param string $message
	 * @deprecated
	 */
	public function add_message($type, $message) {
		$msg = new Message($type, $message);
		$this->add_message_($msg);
	}

	/**
	 * @param Message $message
	 * @return Page $this
	 * TODO: Make private and create add_message_error, add_message_info and add_message_confirm (add_message_ok)
	 */
	public function add_message_(Message $message) {
		$this->messages[] = $message;
		return $this;
	}

	public static function add_message_error($message){
		self::get_singleton()->add_message_(new Message(Message::TYPE_ERROR, $message));
	}

	public function add_inline_js($js) {
		$this->inline_js.=$js."\n";
	}

	/**
	 * Builds and sends the HTML page.
	 */
	public function send_and_quit() {
		#require_once ROOT_DIR . '/core/Error_.php';
		#new Error_("!","TYPE", "DEBUG-INFO");

		$title = $this->get_title();
		$messages = $this->get_message_html();
		$css_html = $this->get_css_html();

		$dev_stats = "";
		if (Config::$DEVMODE) {
			$dev_stats = Debug::get_stats($this);
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

	public function add_js_jquery341(){//TODO: Move to includes
		$this->add_javascript("JS_ID_JQUERY", "https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js");
	}

	public function add_js_core(){//TODO: Move to includes
		$this->add_js_jquery341();
//		if($this->standalone && !defined('HTTP_ROOT')){
//			$this->init_http_root();
//		}
		$this->add_javascript("JS_ID_T2CORE", $this->HTTP_ROOT() . "/client/core.js");
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

	public function get_demoskins_stylesheet_print($style){
		return new Stylesheet($this->HTTP_ROOT()."/style/$style/print.css", Stylesheet::MEDIA_PRINT);
	}

	private function get_css_html() {
		$stylesheets = array();
		$style = Config::get_value_core("STYLE");
		if(in_array($style,array("bare"))){
			$stylesheets["CSS_ID_ALL"]=new Stylesheet($this->HTTP_ROOT()."/style/$style/all.css");
			$stylesheets["CSS_ID_PRINT"]=$this->get_demoskins_stylesheet_print($style);
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
			if ($echo) {
				echo $node;
			} else {
				$body .= $node;
			}
		}
		if ($echo) {
			return true;
		} else {
			return $body;
		}

	}

	public static function abort($title, $messages=null, $body=null, $id="PAGEID_CORE_ABORT") {
		if(!self::$recusion_protection_abort){
			new Error_("(ABORTION OCCURED IN ABORTION)");
			exit;
		}
		self::$recusion_protection_abort = false;

		$page=Page::get_singleton(false);
		if($page===false){

			$page = new Page($id, $title);

			if(is_array($messages)){
				foreach ($messages as $message){
					Page::$compiler_messages[] = $message;
				}
			}

			if($body!==null){
				$page->add($body);
			}

		}

		$page->send_and_quit();

		exit;
		#self::$recusion_protection = true;
	}

}