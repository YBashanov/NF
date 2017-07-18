<?php 
session_start();
define('andromed', '1');
$separator = './';
include "{$separator}config/site/includes.php";

include "{$path_tpl}index.php";