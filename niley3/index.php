<?php 
session_start();
define('andromed', '1');
$separator = './';
include "{$separator}modules/includes.php";

//--- _page.php
include "{$separator}data/_include_db.php";
include "{$separator}data/_include_languages.php";

if (! $data['title']) 		$data['title'] 		= $var[$language][$page]['title'];
if (! $data['keywords']) 	$data['keywords'] 	= $var[$language][$page]['keywords'];
if (! $data['description']) $data['description']= $var[$language][$page]['description'];

include "{$separator}data/variables_array_template_vars.php";
include "{$separator}data/_content_management.php";
include "{$separator}error/index.php";
?>