<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/



namespace t2\core;


use t2\core\service\Config;
use t2\core\service\Html;
use t2\core\service\Includes;
use t2\dev\Debug;
use t2\Start;

/**
 * TODO(2): $page->set_focusFieldId
 */
class Page {

	/** @var Page $singleton */
	static private $singleton = null;

	/**
	 * @deprecated
	 */
	protected $use_database;
	private static $recusion_protection_abort = true;

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
	 * @deprecated TODO: Use only $compiler_messages
	 */
	private $messages = array();

	/**
	 * @var Message[] $compiler_messages pre-init messages
	 * TODO(3): Make private (\t2\core\Page::$compiler_messages)
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

	public $internal_css = "";

	/**
	 * @param string $id
	 * @param string $title
	 */
	public function __construct($id, $title, $use_database = true) {
		if ($use_database && Database::get_singleton(false) === false) {
			$use_database = false;
		}
		$this->use_database = $use_database;
		$this->reset($id, $title);
		if (!$this->use_database) {
			Config::init_http_root(true);
		}
	}

	public function reset($pageId, $title) {
		$this->id = $pageId;
		$this->title = $title;
	}

	public function uses_database() {
		return $this->use_database;
	}

	/**
	 * @param bool $halt_on_error
	 * @return Page|false
	 */
	public static function get_singleton($halt_on_error = true) {
		if (self::$singleton === null) {
			if ($halt_on_error) {
				new Error_("Please initialize Page singelton first", "NO_PAGE", "\$page = \\t2\\core\\Page::init(\"PAGEID_MYMODULE_MYPAGE\", \"My page\");", 1);
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
	public static function init2($id, $title) {

		if (self::$singleton !== null) {
			Error_::quit("Page is already initialized!", 1);
		}

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
	 * @param mixed $node must be string or have function __toString()
	 */
	public function add($node) {
		if (is_array($node)) {
			foreach ($node as $n) {
				$this->add($n);
			}
			return;
		}

		if (!is_string($node)
			&& !method_exists($node, '__toString')
			&& !is_numeric($node)
			&& !is_null($node)
		) {
			$hint = "";
			if (is_bool($node)) {
				$hint = "Booleans need to be converted to strings.\n\$page->add(\$ok?'Yes':'No');";
			}
			new Error_("Invalid node!", "ERROR_INVALID_NODE", $hint, 1);
		}

		$this->html_nodes[] = $node;
	}

	public function add_p($content, $params = array()) {
		$this->add(Html::P($content, null, $params));
	}

	/**
	 * @param Message $message
	 * @return Page $this
	 */
	private function add_message_(Message $message) {
		$this->messages[] = $message;
		return $this;
	}

	public function add_message_error($message) {
		$this->add_message_(new Message(Message::TYPE_ERROR, $message));
	}

	public function add_message_info($message) {
		$this->add_message_(new Message(Message::TYPE_INFO, $message));
	}

	public function add_message_confirm($message) {
		$this->add_message_(new Message(Message::TYPE_CONFIRM, $message));
	}

	public function add_message_ok($message) {
		$this->add_message_confirm($message);
	}

	public static function add_message_error_($message) {
		self::get_singleton()->add_message_error_($message);
	}

	public static function add_message_info_($message) {
		self::get_singleton()->add_message_info($message);
	}

	public static function add_message_confirm_($message) {
		self::get_singleton()->add_message_confirm($message);
	}

	public static function add_message_ok_($message) {
		self::add_message_confirm_($message);
	}

	public function add_inline_js($js) {
		$this->inline_js .= $js . "\n";
	}

	/**
	 * Builds and sends the HTML page.
	 */
	public function send_and_quit() {
		#new Error_("!","TYPE", "DEBUG-INFO");
//TODO(2): Der Typ muss PAGE heissen (AJAX kann auch HTML sein)
		Start::check_type(Start::TYPE_HTML);

		$title = $this->get_title();
		$messages = $this->get_message_html();
		$css_html = $this->get_css_html();

		$dev_stats = "";
		if (Config::$DEVMODE) {
			$dev_stats = Debug::get_stats($this);
		}

		$js_html = $this->get_js_html();

		$css_internal = $this->internal_css?"\t<style>$this->internal_css</style>\n":"";

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
					.$messages
					."<div class='body_inner'>\n";
						$this->get_body(true);
						echo "\n"
					."</div>$dev_stats"
				."</body>\n"
			."</html>";
		// @formatter:on

		#echo "<pre>";print_r(get_included_files());

		exit;

	}

	/**
	 * @deprecated TODO Stattdessen: Includes::js_jquery341($page);
	 */
	public function add_js_jquery341() {
		Includes::js_jquery341($this);
		#$this->add_javascript("JS_ID_JQUERY", "https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js");
	}

	public function add_js_core() {
		$this->add_js_jquery341();
		$this->add_javascript("JS_ID_T2CORE", Config::get_value_core('HTTP_ROOT') . "/js/core.js");
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
			$html .= "\t<script language='JavaScript'>\n".$this->inline_js."\t</script>\n";
		}

		return $html;
	}

	public function add_stylesheet($id, Stylesheet $stylesheet) {
		$this->stylesheets[$id] = $stylesheet;
	}

	public function add_javascript($id, $url) {
		$this->javascripts[$id] = $url;
	}

	public function is_js_set($id) {
		return isset($this->javascripts[$id]);
	}

	public static function get_demoskins_stylesheet_print($style) {
		return new Stylesheet(Config::get_value_core('HTTP_ROOT') . "/skins/$style/print.css", Stylesheet::MEDIA_PRINT);
	}
	public static function get_stylesheet($css, $media="all") {
		$style = Config::get_value_core("SKIN");
		$root = Config::get_value_core('HTTP_ROOT');
		return new Stylesheet("$root/skins/$style/$css", $media);
	}

	private function get_css_html() {
		$stylesheets = array();
		$style = Config::get_value_core("SKIN");
		if (in_array($style, array("bare"))) {
			$stylesheets["CSS_ID_ALL"] = new Stylesheet(Config::get_value_core('HTTP_ROOT') . "/skins/$style/all.css");
			#$stylesheets["CSS_ID_DEV"] = new Stylesheet(Config::get_value_core('HTTP_ROOT') . "/skins/$style/dev.css");
			$stylesheets["CSS_ID_PRINT"] = self::get_demoskins_stylesheet_print($style);
		}
		foreach ($this->stylesheets as $key => $stylesheet) {
			$stylesheets[$key] = $stylesheet;
		}
		$html = "";
		foreach ($stylesheets as $stylesheet) {
			$url = $stylesheet->get_url();
			if($url){
				$html .= "\t<link href=\"" . $url . "\" rel=\"stylesheet\" type=\"text/css\" media=\"" . $stylesheet->get_media() . "\"/>\n";;
			}
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
		if ($html) {
			$html = "<div class='messages noprint'>$html\n</div>\n";
		}
		return $html;
	}

	private function get_title() {
		$project = Config::get_value_core("PROJECT_TITLE");
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

	public static function abort($title, $messages = null, $body = null, $id = "PAGEID_CORE_ABORT") {
		if (!self::$recusion_protection_abort) {
			new Error_("(ABORTION OCCURED IN ABORTION)");
			exit;
		}
		self::$recusion_protection_abort = false;

		if(Start::is_type(Start::TYPE_AJAX)){
			new Error_("Can't abort when ajaxing!", 0, null, 1);
		}

		if (is_array($messages)) {
			foreach ($messages as $message) {
				Page::$compiler_messages[] = $message;
			}
		}

		$page = Page::get_singleton(false);
		if ($page === false) {

			$page = new Page($id, $title);

			if ($body !== null) {
				$page->add($body);
			}

		}else{
			$page->reset($id, $title);
		}

		$page->send_and_quit();

		exit;
		#self::$recusion_protection = true;
	}

	public function get_messages_plain(){
		$text = "";
		foreach (self::$compiler_messages as $msg){
			$char = '?';
			$type = $msg->getType();
			if($type==Message::TYPE_ERROR){
				$char = 'X';
			}else if($type==Message::TYPE_INFO){
				$char = '#';
			}else if($type==Message::TYPE_CONFIRM){
				$char = '=';
			}
			$line = str_repeat($char, 40);
			$text .= $line . "\n" . $msg->get_message() . "\n" . $line . "\n";
		}
		return $text;
	}

}