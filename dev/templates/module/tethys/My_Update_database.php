<?php

namespace t2\modules\core_template\api;//(:moduleIdLc)
/**TPLDOCSTART
 *
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 *
 * This template is used to create a new module.
 * @see \t2\dev\Tools::prompt_new_module()
TPLDOCEND*/

use t2\api\Update_database;

class My_Update_database extends Update_database {

	protected $module = "(:MODULE_ID_LC)";
	protected $start_ver = 1;

	/**
	 * @inheritdoc
	 */
	protected function do_update() {

		#$this->q(1, "");

		#$this->q(, "");

	}

}
