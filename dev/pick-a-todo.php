<?php
/*GPL
 * This file is part of the T2 toolbox;
 * Copyright (C) 2014-2020 Fabian Perder (t2@qnote.de) and contributors
 * T2 comes with ABSOLUTELY NO WARRANTY. This is free software, and you are welcome to redistribute it under
 * certain conditions. See the GNU General Public License (file 'LICENSE' in the root directory) for more details.
 GPL*/

namespace t2\dev;

require_once '../Start.php';

use t2\Start;

$page = Start::init("PAGEID_CORE_DEV_PICKATODO", "Pick-A-Todo");

//Get all TODOs:
$file = file("notes.md", FILE_IGNORE_NEW_LINES);

$todos = array();
$cat = null;
$bucket = array();
$on = false;

foreach ($file as $line){
	if($line=="Current TODOs"){
		$on = true;
	}
	if($on){
		if(preg_match("/^\\#\\#\\# (.*)\$/", $line, $matches)){
			if($cat){
				$todos[$cat] = $bucket;
			}
			$cat = $matches[1];
			$bucket = array();
		}
		if(preg_match("/^\\* (.*)\$/", $line, $matches)){
			$todo = $matches[1];
			//Unescape markdown:
			$todo = preg_replace("/\\\\(.)/","$1",$todo);
			$bucket[] = $todo;
		}
	}
}
if($cat){
	$todos[$cat] = $bucket;
}

//Values:
$values = array();
foreach ($todos as $cat=>$vals){
	$c = count($vals);
	if ($cat == 'High') {
		$c /= 2;
	}
	if ($cat == 'Medium') {
		$c /= 3;
	}
	if ($cat == 'Low') {
		$c /= 4;
	}
	if ($cat == 'Deprecated') {
		$c = 0;
	}
	$values[$cat] = $c;
}

//Find max:
$max_cat = null;
$max_val = -1;
foreach ($values as $cat=>$c){
	if($c>$max_val){
		$max_cat=$cat;
		$max_val=$c;
	}
}
$winner_array = $todos[$max_cat];
$random_number = rand(0, count($winner_array)-1);
$todo = $winner_array[$random_number];

$page->add_message_info("<b>".htmlentities($todo)."</b><br><i style='display:block;text-align: right'>from the category \"$max_cat\"</i>");

$page->send_and_quit();
