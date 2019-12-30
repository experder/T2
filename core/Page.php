<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


namespace core;

require_once ROOT_DIR . '/core/Html.php';

use service\Config;
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

	private $stylesheets = array();

	private $javascripts = array();

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
				Error::quit_bare("Please initialize Page singelton first: <code>\$page = \\core\\Page::init(\"PAGE_ID_MYPAGE\", \"My page\");</code>", 1);
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

	/**
	 * Builds and sends the HTML page.
	 */
	public function send_and_quit() {

		$title = $this->get_title();
		$messages = $this->get_message_html();
		$css_html = "";
		$js_html = "";

		$dev_stats = "";
		if (Config::$DEVMODE) {
			$dev_stats = new Html("div", Database::get_dev_stats() . " / " . Start::get_dev_stats(), array("class" => "dev_stats"
			, "style" => (Config::$PRE_CSS ? "color:blue;":"")
			));
		}

		// @formatter:off
		echo "<!DOCTYPE html>"
			."<html>\n"
				."<head>\n"
					."<meta charset=\"UTF-8\">\n"
					."<title>$title</title>\n"
					.$css_html
					.$js_html
				."</head>\n"
				."<body id='$this->id'>\n"
					.$messages
					."<div class='body_inner'>";
						$this->get_body(true);
		echo
					"</div>$dev_stats"
				."</body>"
			."</html>";
		// @formatter:on

		exit;

	}

	private function get_message_html() {
		$html = "";
		/**
		 * @var Message[] $all_messages
		 */
		$all_messages = array_merge(self::$compiler_messages, $this->messages);
		foreach ($all_messages as $message) {
			$css_class = $message->get_type_cssClass();
			$html .= "<div class='message $css_class' "
				. (Config::$PRE_CSS ? " style='border:1px solid black;border-radius:5px;'":"")
				. " >" . $message->get_message() . "</div>";
		}
		$html = "<div class='messages'>$html</div>";
		return $html;
	}

	private function get_title() {
		$project = Config::get_value_core("PROJEKT_TITLE", 'T2');
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
			if (is_string($node)) {
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