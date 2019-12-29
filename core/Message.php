<?php

namespace core;


class Message {

	const TYPE_ERROR = 1;
	const TYPE_INFO = 2;
	const TYPE_CONFIRM = 3;

	private $type;

	private $message;

	/**
	 * @param int    $type [Message::TYPE_ERROR|Message::TYPE_INFO|Message::TYPE_INFO]
	 * @param string $message
	 */
	public function __construct($type, $message) {
		$this->type = $type;
		$this->message = $message;
	}

	public function get_message() {
		return $this->message;
	}

	public function get_type_cssClass() {
		switch ($this->type) {
			case self::TYPE_ERROR:
				return "msg_type_error";
				break;
			case self::TYPE_INFO:
				return "msg_type_info";
				break;
			case self::TYPE_CONFIRM:
				return "msg_type_confirm";
				break;
		}
		return "msg_type_unknown";
	}

}