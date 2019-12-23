<?php

require_once 'Start.php';
$page = \core\Page::init("PAGE_ID_TETHYSINDEX", "T2");

$page->add("Welcome!");

$page->send_and_quit();