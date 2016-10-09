<?php if ( ! defined('andromed')) exit(''); 
//идея - 2012-11-07

$getdate = getdate();

$arrays = array();

//статус пользователя
$arrays['users_status'] = array(
	"0"=>"Не активен",
	"1"=>"Пользователь",
	"2"=>"Менеджер",
	"3"=>"Ст.Менеджер",
	"5"=>"Администратор",
	"9"=>"Ст.Администратор"
);


//язык сайта
$arrays['language'] = array(
	"ru"=>"русский",
	"en"=>"english",
	"de"=>"deutsch"
);


$global['arrays'] = $arrays;	
?>