<?php if ( ! defined('andromed')) exit(''); 

if (
	$_SERVER['REMOTE_ADDR'] == '127.0.0.1'
	//|| $_SERVER['REMOTE_ADDR'] == '10.1.1.119'
	//|| $_SERVER['REMOTE_ADDR'] == '10.1.1.30'
) {
	$global['server']		= "local";
}
else {
	$global['server']		= "internet";
}
// ----------------------------------------

if ($global['server'] == "local") {
	$base_url = 'http://siteboundary/';	//в конце слэш
	$global['pagePrefix'] = "";			//без слэшей
	$pagePrefix = $global['pagePrefix'];
	$global['is_topserver'] = true;
	$global['errorTrue']	= true;
	$global['errorView']	= true;
	$global['user_online_is']=false; //запрет на создание кукис и запись их в базу (облегчает работу ядра, где это необходимо)
	
	$global['createConfigs']= false; //запрет на создание дополнительных папок и файлов в templates/html/... - 
		//false используем, если шаблон всего один, и он находится в html (либо страницы обрабатываем вручную)
		//true - если мы будем обрабатывать каждую страницу отдельно - программа нам будет помогать создавать файлы
	
	$global['dbTrue'] 		= true;		//доступ к БД
	$global['noFilter'] 	= true;
	$global['openboundary'] = true;
	$global['is_java']		= false;	//если true, то закрываются ненужные определения переменных
}
else {
	$base_url = 'http://www.bjorksskitchen.myjino.ru/siteboundary/'; //в конце слэш
	$global['pagePrefix'] = "siteboundary"; //без слэшей
	$pagePrefix = $global['pagePrefix'];
	$global['is_topserver'] = false;
	$global['errorTrue']	= true;
	$global['errorView']	= false;
	$global['user_online_is']=false;
	
	$global['createConfigs']= false; 
	
	$global['dbTrue'] 		= false;	//доступ к БД
	$global['noFilter'] 	= false;
	$global['openboundary'] = false;
	$global['is_java']		= false;
}
$global['separator'] = $separator;
$global['base_url'] = $base_url;


if ($global['is_java'] == false) {
	
	//можно ли менять шаблон из адресной строки с помощью GET (форматом ?templ=default&....)
	$global['change_templates_from_addressstring'] = false;

	//группа шаблонов по умолчанию
	$global['template'] = "default";
	
	if ($global['change_templates_from_addressstring']) {
		if ($_GET['templ']) {
			$global['template'] = $_GET['templ'];
		}
	}

	//предписанный путь
	$base_image 	= $global['base_url'] . "templates/" . $global['template'] . "/image/";
	$path_image 	= "templates/" . $global['template'] . "/image/";
	$base_style 	= $global['base_url'] . "templates/" . $global['template'] . "/style/";
	$base_class_js 	= $global['base_url'] . "js/";
	$base_js 		= $global['base_url'] . "templates/" . $global['template'] . "/js/";
	$base_uploads 	= $global['base_url'] . "uploads/";
	$base_modules	= $global['base_url'] . "templates/" . $global['template'] . "/modules/";
    $path_modules	= "templates/" . $global['template'] . "/modules/";
	$base_tpl		= $global['base_url'] . "templates/" . $global['template'] . "/tpl/";
	$global['base_uploads'] = $base_image;
	$global['base_image'] 	= $base_image;
	$global['path_image'] 	= $path_image;
	$global['base_modules'] = $base_modules;
    $global['path_modules'] = $path_modules;
	$global['base_tpl'] 	= $base_tpl;
	$global['base_template']= $global['base_url'] . "templates/" . $global['template'] . "/";
}
//язык включен/выключен true-false
$global['is_language'] = true;
$is_language 		= $global['is_language'];
//язык по умолчанию
$global['defaultLanguage'] 	= "ru";
$defaultLanguage			= $global['defaultLanguage'];

//подключение баз данных
$dbTrue 			= $global['dbTrue'];

//подключение возможности записывать ошибки в базу
$errorTrue 			= $global['errorTrue'];
//отображение ошибок (через error/)
$errorView 			= $global['errorView'];

//разрешение/запрещение редиректа
$global['seoTrue'] 	= true;
$seoTrue 			= $global['seoTrue'];

//сколько позиций из командной строки (разделенной /) учавствуют в редиректе
//данные параметры можно настраивать персонально для каждой страницы
//не редиректированная строка
$global['site_uri_count'] = 1;		//false OR номер
$site_uri_count		= $global['site_uri_count'];
//строка после редиректа
$global['site_redirect_count'] = 2; //false OR номер
$site_redirect_count= $global['site_redirect_count'];

//нефильтруемые через regular данные - True - доступны для записи (небезопасно), False - не доступны (безопасно)
$noFilter 			= $global['noFilter'];

//2013-10-07 - разрешение на выполнение скриптов "фабрики" (таких, как создание сторонних таблиц)
$openboundary 		= $global['openboundary'];


$merger = array();
?>