<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\modules\core_demo;

use t2\api\Navigation;

class My_Navigation extends Navigation {

	public function __construct() {
		parent::__construct('NAVI_DEMO');
	}

	public function getChildren() {
		return array(
			new Navigation('PAGEID_DEV_AJAXDEMO'),
			new Navigation('PAGEID_DEV_CSSDEMO'),
			new Navigation('PAGEID_DEV_PDFDEMO'),
		);
	}

}