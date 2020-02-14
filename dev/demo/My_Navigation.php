<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\modules\core_demo\api;

use t2\api\Navigation;
use t2\core\Html;

class My_Navigation extends Navigation {

	public function __construct() {
		parent::__construct('NAVI_DEMO',"Demo",null,null);
	}

	public function getChildren() {
		if($this->children===null){
			$this->children=array(
				new Navigation('PAGEID_DEV_AJAXDEMO',"Ajax demo",Html::href_internal_root("dev/demo/ajaxdemo")),
				new Navigation('PAGEID_DEV_CSSDEMO',"CSS demo",Html::href_internal_root("dev/demo/cssdemo")),
				new Navigation('PAGEID_DEV_PDFDEMO',"PDF demo",Html::href_internal_root("dev/demo/pdfdemo")),
			);
		}
		return $this->children;
	}

}