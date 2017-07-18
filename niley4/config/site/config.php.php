<?php if ( ! defined('andromed')) exit('');
//2017-07-10
if (! isset($global)){
    global $global;
    $global = array();
}
if (! isset($base_url)){
    global $base_url;
    $base_url = "";
}


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
    //в конце слэш
    $global['base_url'] = 'http://we-not/';
    //без слэшей
	$global['pagePrefix'] = "";
    //устаревшее
	$global['is_topserver'] = true;
    //подключение возможности записывать ошибки в базу
	$global['errorTrue']	= true;
    //отображение ошибок (через error/)
	$global['errorView']	= true;
    //запрет на создание кукис и запись их в базу (облегчает работу ядра, где это необходимо)
	$global['user_online_is']=false;
    //запрет на создание дополнительных папок и файлов в templates/html/... -
    //false используем, если шаблон всего один, и он находится в html (либо страницы обрабатываем вручную)
    //true - если мы будем обрабатывать каждую страницу отдельно - программа нам будет помогать создавать файлы
	$global['createConfigs']= false;

    //подключение баз данных
	$global['dbTrue'] 		= true;
    //создать таблицы баз данных
	$global['dbCreateTables'] = false;
    //нефильтруемые через regular данные - True - доступны для записи (небезопасно), False - не доступны (безопасно)
	$global['noFilter'] 	= true;
    //2013-10-07 - разрешение на выполнение скриптов "фабрики" (таких, как создание сторонних таблиц)
	$global['openboundary'] = true;
	$global['is_java']		= false;	//если true, то закрываются ненужные определения переменных
}
else {
    //в конце слэш
    $global['base_url'] = 'http://www.bjorksskitchen.myjino.ru/siteboundary/';
    //без слэшей
	$global['pagePrefix'] = "siteboundary";
    //устаревшее
	$global['is_topserver'] = false;
    //подключение возможности записывать ошибки в базу
	$global['errorTrue']	= true;
    //отображение ошибок (через error/)
	$global['errorView']	= false;
    //запрет на создание кукис и запись их в базу (облегчает работу ядра, где это необходимо)
	$global['user_online_is']=false;
    //запрет на создание дополнительных папок и файлов в templates/html/... -
    //false используем, если шаблон всего один, и он находится в html (либо страницы обрабатываем вручную)
    //true - если мы будем обрабатывать каждую страницу отдельно - программа нам будет помогать создавать файлы
	$global['createConfigs']= false;

    //подключение баз данных
	$global['dbTrue'] 		= false;
    //создать таблицы баз данных
    $global['dbCreateTables'] = false;
    //нефильтруемые через regular данные - True - доступны для записи (небезопасно), False - не доступны (безопасно)
	$global['noFilter'] 	= false;
    //2013-10-07 - разрешение на выполнение скриптов "фабрики" (таких, как создание сторонних таблиц)
	$global['openboundary'] = false;
	$global['is_java']		= false;
}
//смысл is_java потерян
if ($global['is_java'] == false) {}

//группа шаблонов по умолчанию
$global['template'] = "ng";
//можно ли менять шаблон из адресной строки с помощью GET (форматом ?templ=default&....)
$global['change_templates_from_addressstring'] = false;
//разрешение/запрещение редиректа
$global['seoTrue'] = true;
//язык включен/выключен true-false
$global['is_language'] = false;
//язык по умолчанию
$global['defaultLanguage'] 	= "ru";

//пути до файлов разных версий фреймворка
//php/ajax
$global['style_ajax'] = "niley3";
//php/data
$global['style_data'] = "niley3";
//php/libraries
$global['style_libraries'] = "niley3";
//php/modules
$global['style_modules'] = "niley3";
//config/management - устаревшее
$global['path_management'] = "config/management/niley4/";

//предписанный абсолютный путь
$global['base_tpl'] = $global['base_url'] . "view/templates/" . $global['template'] . "/";
//относительный путь
$global['path_tpl'] = $separator . "view/templates/" . $global['template'] . "/";

//сколько позиций из командной строки (разделенной /) учавствуют в редиректе
//данные параметры можно настраивать персонально для каждой страницы
//не редиректированная строка
$global['site_uri_count'] = 1;		//false OR номер
//строка после редиректа
$global['site_redirect_count'] = 2; //false OR номер




$global['separator'] = $separator;

$base_url           = $global['base_url'];
$base_tpl           = $global['base_tpl'];
$path_tpl           = $global['path_tpl'];
$pagePrefix         = $global['pagePrefix'];
$dbTrue             = $global['dbTrue'];
$errorTrue          = $global['errorTrue'];
$errorView 	        = $global['errorView'];
$seoTrue            = $global['seoTrue'];
$is_language        = $global['is_language'];
$defaultLanguage    = $global['defaultLanguage'];
$noFilter 			= $global['noFilter'];
$openboundary 		= $global['openboundary'];
$site_uri_count		= $global['site_uri_count'];
$site_redirect_count= $global['site_redirect_count'];

if ($global['change_templates_from_addressstring']) {
    if ($_GET['templ']) {
        $global['template'] = $_GET['templ'];
    }
}

$merger = array();
?>