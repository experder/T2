<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

/*
require_once ROOT_DIR . '/core/Stylesheet.php';
 */

namespace core;


class Stylesheet {

	const MEDIA_PRINT = "print";

	private $media;
	private $url;

	/**
	 * Stylesheet constructor.
	 * @param string $media https://www.w3schools.com/tags/att_link_media.asp
	 * @param string $url
	 * @see https://www.w3schools.com/tags/att_link_media.asp
	 */
	public function __construct($url, $media="all") {
		$this->media = $media;
		$this->url = $url;
	}

	/**
	 * @param string $media
	 */
	public function setMedia($media) {
		$this->media = $media;
	}

	/**
	 * @return string
	 */
	public function get_media() {
		return $this->media;
	}

	/**
	 * @return string
	 */
	public function get_url() {
		return $this->url;
	}

}