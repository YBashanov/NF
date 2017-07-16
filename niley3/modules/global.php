<?php if ( ! defined('andromed')) exit(''); 
/*
создание глобальной переменной (массива).
Чтобы проверять и не упускать все глобальные переменные, необходимо 
добавлять их в массив
*/
//ini_set('date.timezone', 'Europe/Moscow');


if (! isset($global)){
	global $global;
	$global = array();
}

if (! isset($base_url)){
	global $base_url;
	$base_url = "";
}

include "{$separator}modules/config.php";
?>