<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/


namespace t2\core;


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