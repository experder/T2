<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core;

use t2\api\Header;
use t2\api\Navigation;
use t2\core\service\Config;
use t2\core\service\Config_core;
use t2\core\service\Includes;
use t2\dev\Debug;
use t2\Start;

/**
 * TODO(F): $page->set_focusFieldId
 */
class Page {

	/** @var Page $singleton */
	private static $singleton = null;

	private static $recusion_protection_abort = true;

	private static $global_id_counter = 1;

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
	private static $messages = array();

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

	private $inline_js = "";

	private $html_nodes = array();

	public $internal_css = "";

	/**
	 * @var Header $header
	 */
	private static $header = null;

	private static $debug_singleton_should_exist = false;

	/**
	 * @param string $id
	 * @param string $title
	 */
	private function __construct($id, $title) {
		$this->reset($id, $title);
		if (Database::get_singleton(false) === false) {
			Config::init_http_root(true);
		}
		if(self::$debug_singleton_should_exist && Config::$DEVMODE){
			self::add_message_warn("Page should not be instantiated multiple times!<hr>".Debug::backtrace(1,"<br>"));
		}
		self::$debug_singleton_should_exist = true;
	}

	public static function get_next_global_id($prefix="") {
		return $prefix.(self::$global_id_counter++);
	}

	/**
	 * @param string|null $focus_field_id
	 */
	public function set_focusFieldId($focus_field_id) {
		$this->focus_field_id = $focus_field_id;
	}

	public function reset($pageId, $title) {
		$this->id = $pageId;
		$this->title = $title;
	}

	/**
	 * @param Header $header
	 */
	public static function setHeader($header) {
		self::$header = $header;
	}

	/**
	 * @param bool $halt_on_error
	 * @return Page|false
	 *
	 * @deprecated TODO
	 */
	public static function get_singleton($halt_on_error = true) {
		if (self::$singleton === null) {
			if ($halt_on_error) {
				new Error("NO_PAGE", "Please initialize Page singelton first", "\$page = \\t2\\core\\Page::init(\"PAGEID_MYMODULE_MYPAGE\", \"My page\");", 1);
			} else {
				return false;
			}
		}
		return self::$singleton;
	}

	public static function getSingleton($id = null) {
		if (self::$singleton === null) {
			if($id===null){
				new Error("NO_ID_GIVEN", "", "Please provide page ID");
			}
			$title = Start::getNavigation(false);
			if($title instanceof Navigation){
				$title = $title->getTitle($id);
			}else{
				$title = "!??????!";//TODO: Default/Login Title
			}
			self::$singleton = new Page($id, $title);
		}
		return self::$singleton;
	}

	/**
	 * @param string $id
	 * @return Page
	 */
	public static function init2($id) {

		if (self::$singleton !== null) {
			new Error("DOUBLE_INIT", "Page is already initialized!", null, 1);
		}

		$title = Start::getNavigation()->getTitle($id);

		self::$singleton = new Page($id, $title);

		return self::$singleton;
	}

	/**
	 * @deprecated TODO: \t2\core\service\Config::get_default_value
	 * @see \t2\core\service\Config::get_default_value
	 */
	public function HTTP_ROOT() {
		return Config::get_value_core('HTTP_ROOT');
	}

	public function get_id() {
		return $this->id;
	}

	/**
	 * @param mixed $node must be of a type described in \t2\core\Node::check_type
	 * @see \t2\core\Node::check_type
	 */
	public function add($node) {

		Node::check_type($node);

		if (is_array($node)) {
			foreach ($node as $n) {
				$this->add($n);
			}
			return;
		}

		$this->html_nodes[] = $node;
	}

	public static function add_message(Message $message) {
		self::$messages[] = $message;
	}

	/**
	 * @return Message[]
	 */
	public static function get_messages() {
		return self::$messages;
	}

	/**
	 * @param string $message
	 */
	public static function add_message_error_($message) {
		self::add_message(new Message(Message::TYPE_ERROR, $message));
	}

	/**
	 * @param string $message
	 */
	public static function add_message_info_($message) {
		self::add_message(new Message(Message::TYPE_INFO, $message));
	}

	/**
	 * @param string $message
	 */
	public static function add_message_confirm_($message) {
		self::add_message(new Message(Message::TYPE_CONFIRM, $message));
	}

	/**
	 * @param string $message
	 */
	public static function add_message_ok($message) {
		self::add_message_confirm_($message);
	}

	/**
	 * @param string $message
	 */
	public static function add_message_warn($message) {
		self::add_message_error_($message);
	}

	public function add_inline_js($js) {
		$this->inline_js .= $js . "\n";
	}

	/**
	 * Builds and sends the HTML page.
	 */
	public function send_and_quit() {
		Start::check_type(Start::TYPE_PAGE);

		$title = $this->get_title();
		$css_html = $this->get_css_html();
		$navigation = Start::getNavigation_html($this->id);
		$header = self::$header ? self::$header->get_header($this) : "";
		$footer = self::$header ? self::$header->get_footer($this) : "";

		if (Config::$DEVMODE) {
			$this->add_js_core();
		}

		$js_html = $this->get_js_html();

		$css_internal = $this->internal_css ? "\t<style>$this->internal_css</style>\n" : "";

		$messages = $this->get_message_html();

		// @formatter:off
		echo "<!DOCTYPE html>\n"
			."<html>\n"
				."<head>\n"
					."\t<meta charset=\"UTF-8\">\n"
					."\t<title>$title</title>\n"
					.$css_html
					.$js_html
					.$css_internal
				."</head>\n"
				."<body id='$this->id'>\n"
					."<nav>$navigation</nav>"
					.$header
					.$messages
					."<div class='body_inner'>\n";
						$this->get_body(true);
						echo "\n"
					."</div>"
					.$footer
					.(Config::$DEVMODE?Debug::get_stats($this):"")
					.$this->waitSpinner()
				."</body>\n"
			."</html>";
		// @formatter:on

		exit;
	}

	private function waitSpinner() {
		$waitSpinner = "<div id=\"uploadSpinner\" style='display:none;'><div class=\"spinnerContent\"><div>Bitte warten...</div></div></div>";
		return $waitSpinner;
	}

	public function add_js_core() {
		Includes::js_jquery341($this);
		$this->add_javascript("JS_ID_T2CORE", Config::get_value_core('HTTP_ROOT') . "/core/core.js");
	}

	private function get_js_html() {
		$html = "";

		/*
		 * External scriptfiles
		 */
		foreach ($this->javascripts as $javascript) {
			$html .= "\t<script src=\"$javascript\" type=\"text/javascript\"></script>\n";
		}

		/*
		 * Inline script
		 */
		if ($this->inline_js) {
			$html .= "\t<script language='JavaScript'>\n" . $this->inline_js . "\t</script>\n";
		}

		return $html;
	}

	public function add_stylesheet($id, Stylesheet $stylesheet) {
		$this->stylesheets[$id] = $stylesheet;
	}

	public function add_javascript($id, $url, $css = false) {
		if ($css) {
			self::add_stylesheet($id, new Stylesheet($url));
		} else {
			$this->javascripts[$id] = $url;
		}
	}

	public function is_js_set($id) {
		return isset($this->javascripts[$id]);
	}

	public function is_css_set($id) {
		return isset($this->stylesheets[$id]);
	}

	public static function get_stylesheet($css, $media = "all") {
		$skindir = Config_core::skin_dir();
		return new Stylesheet("$skindir/$css", $media);
	}

	private function get_css_html() {
		$stylesheets = array();
		$skin_dir = Config_core::skin_dir();
		$stylesheets["CSS_ID_ALL"] = new Stylesheet($skin_dir . "/all.css");
		#$stylesheets["CSS_ID_DEV"] = new Stylesheet(Config::get_value_core('HTTP_ROOT') . "/skins/$style/dev_forms.css");
		$stylesheets["CSS_ID_PRINT"] = new Stylesheet($skin_dir . "/print.css", Stylesheet::MEDIA_PRINT);
		foreach ($this->stylesheets as $key => $stylesheet) {
			$stylesheets[$key] = $stylesheet;
		}
		$html = "";
		foreach ($stylesheets as $stylesheet) {
			$url = $stylesheet->get_url();
			if ($url) {
				$html .= "\t<link href=\"" . $url . "\" rel=\"stylesheet\" type=\"text/css\" media=\"" . $stylesheet->get_media() . "\"/>\n";;
			}
		}
		return $html;
	}

	private function get_message_html() {
		$html = "";
		foreach (self::$messages as $message) {
			$html .= "\n\t".$message->toHTML();
		}
		if ($html) {
			$html .= "\n";
		}
		$html = "<div class='messages noprint' id='t2_messages'>$html</div>\n";
		return $html;
	}

	private function get_title() {
		$project = Config::get_value_core("PROJECT_TITLE");

		if(!$this->title && $this->id=="PAGEID_CORE_LOGIN"){
			return $project;
		}

		$title = $this->title . " - $project";
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

	public static function abort($title, $messages = null, $id = "PAGEID_CORE_ABORT") {
		if (!self::$recusion_protection_abort) {
			new Error("(ABORTION OCCURED IN ABORTION)");
			exit;
		}
		self::$recusion_protection_abort = false;

		if (Start::is_type(Start::TYPE_AJAX)) {
			new Error("AJAX_ABORT", "Can't abort when ajaxing!", null, 1);
		}

		if (is_array($messages)) {
			foreach ($messages as $message) {
				self::add_message($message);
			}
		}

		$page = new Page($id, $title);

		$page->send_and_quit();

		exit;
		#self::$recusion_protection = true;
	}

	public function get_messages_plain() {
		$text = "";
		foreach (self::$messages as $msg) {
			$char = '?';
			$type = $msg->getType();
			if ($type == Message::TYPE_ERROR) {
				$char = 'X';
			} else if ($type == Message::TYPE_INFO) {
				$char = '#';
			} else if ($type == Message::TYPE_CONFIRM) {
				$char = '=';
			}
			$line = str_repeat($char, 40);
			$text .= $line . "\n" . $msg->get_message() . "\n" . $line . "\n";
		}
		return $text;
	}

}