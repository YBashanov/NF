<?php if ( ! defined('andromed')) exit(''); 
if (
	$_COOKIE['gen_6'] !== NULL &&
	$_COOKIE['gen_6'] !== "" &&
	$_COOKIE['gen_6'] != "0" &&
	$_COOKIE['gen_6'] === $global['user_online']['secret_number'] &&
	$_COOKIE['PHPSESSID'] !== NULL &&
	$_COOKIE['PHPSESSID'] !== "" &&
	$_COOKIE['PHPSESSID'] != "0" &&
	$_COOKIE['PHPSESSID'] === $global['user_online']['sid']
){}
else {
	$wrap->add(1, "Доступ к серверу с другого хоста или вне сайта", $thisFile);
	include "{$separator}error/index.php";
	exit();
}
?>