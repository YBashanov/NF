<?php if ( ! defined('andromed')) exit(''); 
//идея - 5.09.12
$var = array();

//пути для заголовков
$var['header_main'] 	= $base_url;				//на главную
$var['header_enter'] 	= $base_url . "enter/";		//на страницу входа
$var['header_personal'] = $base_url . "personal/"; 	//в личный кабинет

//общедоступные страницы
$var['ru'] = array(
	'index' => array(
		'title' => "Главная"
	),
	'statyi' => array(
		'title' => ""
	),
	'kontakti' => array(
		'title' => ""
	)
);
$var['en'] = array(
	'index' => array(
		'title' => "General"
	),
	'statyi' => array(
		'title' => ""
	),
	'kontakti' => array(
		'title' => ""
	)
);
$var['de'] = array(
	'index' => array(
		'title' => "Startseite"
	),
	'statyi' => array(
		'title' => ""
	),
	'kontakti' => array(
		'title' => ""
	)
);



//страницы типа enter
$var['admin'] 		= array("title" => "Администраторский раздел");
$var['enter'] 		= array("title" => "Вход");
$var['registration']= array("title" => "Регистрация");
$var['activate'] 	= array("title"	=> "Активация");
$var['personal'] 	= array("title" => "Личный кабинет");	

//страницы закрытой части
$var['personal'] 	= array(
	"title" 	=> "Личный кабинет",
	"news"			=>array("pageTitle"	=>"Новости"),
	"portfolio"		=>array("pageTitle"	=>"Портфолио"),
	"statis"		=>array("pageTitle"	=>"Статистика")
);	
	
//ajax
$var['nextRegisterSec'] = 3600*2; 			//(повторная регистрация - при тех же кукис регистрироваться можно не ранее
$var['timeToActivate'] 	= 3600*6;			//6 часов - время, данное на активацию

$var['width_1']			= 800;				//1я картинка не больше данной ширины
$var['height_1']		= 600;				//1я картинка не больше данной высоты
$var['relation_1']		= 0;				//(1я карт) отношение ширины к высоте. Если =0, то вписывается в размер, иначе режется под размер
$var['width_2'] 		= 150;				//ширина 2й картинки после резки (в случае фотоальбома для продукции)
$var['height_2'] 		= 100;				//высота 2й картинки после резки 
$var['relation_2']		= 150/100;			//(2я карт) отношение ширины к высоте

$var['width_2_Quad']	= 320;
$var['relation_Quad']	= 1;

$var['mb'] 				= 1048576;			//1 мегабайт
$var['maxWeight'] 		= $var['mb']*8;	//максимальный вес заргужаемой картинки не более 1 Мб
$var['maxMediaWeight'] 	= $var['mb']*8;	//максимальный вес заргужаемой картинки не более 1 Мб

$var['classes_description_pre'] = 260;		//число символов в превью классов (раздел каталога) - в разделах personal
$var['rekomendatsii_pre'] = 260;			//число символов в превью описания рекомендаций


$global['var'] = $var;	
?>