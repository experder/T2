<?php

namespace t2\modules\core_template;//(:moduleIdLc)
/**TPLDOCSTART
 *
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 *
 * This template is used to create a new module.
 * @see \t2\dev\Tools::prompt_new_module()
 */
if (true) exit;
/**TPLDOCEND*/

require_once '../../../Start.php';//(:relativePath)
use t2\Start;

$page = Start::init_("PAGEID_(:MODULE_ID_UC)_INDEX");

$page->add("Index of module \"(:MODULE_NAME)\"");

$page->send_and_quit();
