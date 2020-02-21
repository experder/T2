<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\core\mod;

require_once '../../Start.php';

use t2\core\Database;
use t2\core\table\Table;
use t2\Start;

$page = Start::init_("PAGEID_CORE_USER_CFG");

$user = new D_User(array(
	"id"=>rand(2,999),
	"ref_id"=>"2",
	"username"=>"a".rand(2,999),
	"pass_hash"=>"b",
	"display_name"=>"ö",
));

$user->q_insert_all();

$data = Database::select_("SELECT * FROM mycore_user;");
$table = new Table($data);
$page->add($table);

$page->add_message_ok("Datensatz hinzugefügt!");

$page->send_and_quit();