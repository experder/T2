<?php

namespace t2\api;

use t2\core\Page;

interface Header {

	public function get_header(Page $page);

	public function get_footer(Page $page);

}