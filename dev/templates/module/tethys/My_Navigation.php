<?php
namespace t2\modules\core_template\api;//(:moduleIdLc)
/**TPLDOCSTART
GPL
 * TODO
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