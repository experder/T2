<?php

namespace t2\dev\tools;

require_once '../../Start.php';
require_once ROOT_DIR . '/core/form/Form.php';

use service\Config;
use service\Html;
use service\Request;
use t2\core\Form;
use t2\core\Formfield_textarea;
use t2\Start;

$page = Start::init("A", "B");
if(!Config::$DEVMODE){
	$page->add_message_error("Not available.");
	$page->send_and_quit();
}
$page->add(Html::H1("Format TODOs"));

$form = new Form("do_process", "", "Process");
$form->add_field(new Formfield_textarea("input", ""));

$page->add($form);
if(Request::cmd("do_process")){
	$out = process();
	$page->add(Html::PRE_console($out));
}

$page->send_and_quit();
//==================================================================================
function process(){
	$input = Request::value("input");
	$output = array();
	$output_deprecated = array();

	$current_file = false;
	$count_per_file = array();

	foreach (explode("\n",$input) as $in){
		$in = trim($in);//Von Windows-Zeilenumbrüchen bleibt ein \r.
		if($in){//Leere Zeilen rausfiltern
			if(!preg_match("/^Found [0-9]+ TODO items in [0-9]+ files\$/",$in)){//Titelzeile
				if(preg_match("/^[\\w\\.]+$/", $in)){//Überschrift
					if ($current_file!==false && $count_per_file[$current_file]==0){//Letzte Überschrift war leer
						$output[] = $current_file;
					}
					$current_file = $in;
					$count_per_file[$current_file]=0;
				}else{
					$in0 = $in;
					if($current_file!==false){
						if (preg_match("/^\\(([0-9]{1,4}), [0-9]{1,3}\\) (.*)/", $in, $matches)){//ZEILE
							$zeile = $matches[1];
							$todo = $matches[2];
							//TODO:Parse TODO(1) (default)(high), TODO(2)(medium), TODO(3)(low)
							if(preg_match("/\\(TODO\\:(.*)\\)/i",$todo,$matches_i)
								||preg_match("/\\/\\*TODO\\:(.*?)\\*\\//i",$todo,$matches_i)
								||preg_match("/\\/\\/TODO\\:(.*)/i",$todo,$matches_i)
								||preg_match("/ TODO\\:(.*)/i",$todo,$matches_i)
							){
								$todo_inner = trim($matches_i[1]);
								if($todo_inner){
									$todo = $todo_inner;
								}
							}
							$in = "$todo ($current_file:$zeile)";
							$count_per_file[$current_file]++;
						}
					}
					if(preg_match("/\\@deprecated /", $in0)){
						$output_deprecated[] = $in;
					}else{
						$output[] = $in;
					}
				}
			}
		}
	}
	if ($current_file!==false && $count_per_file[$current_file]==0){//Letzte Zeile war Überschrift
		$output[] = $current_file;
	}

	$out_html="### High\n";
	$out_html.= "* ".implode("\n* ", $output);
	if($output_deprecated){
		$out_html.="\n\n### Deprecated\n";
		$out_html.="* ".implode("\n* ", $output_deprecated);
	}

	return $out_html;
}
