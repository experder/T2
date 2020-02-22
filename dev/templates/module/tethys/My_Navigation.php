<?php
namespace t2\modules\core_template\api;//(:moduleIdLc)
/**TPLDOCSTART
GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
GPL
 * This template is used to create a new module.
 * @see \t2\dev\Tools::prompt_new_module()
TPLDOCEND*/

use t2\api\Navigation;
use t2\core\Html;

class My_Navigation extends Navigation {

	public function __construct() {
		parent::__construct("NAVI_:moduleIdUc",":moduleIdLc","",null);
	}

	public function getChildren() {
		if($this->children===null){
			$this->children=array(
				#new Navigation('PAGEID_',"",Html::href_internal_module(":moduleIdLc","index")),
			);
		}
		return $this->children;
	}

}