<?php  if ( ! defined('andromed')) exit('');
/*
//2013-...
//2014-06-10 - html
//2014-06-20 - merger
//2014-09-19 - добавлена дополнительная анализирующая строка (именно для проекта dashome)
//2014-10-08 - разделение templateF... и merger()
//2015-03-26 - tpl
//2015-08-23 - content_for_js()


памятка для ajax: ---------------------

1-й вариант
$template->setPage($page);
content(A_T_ORDERS_FOREACH);
$in = $template->getParse(A_T_ORDERS_FOREACH);

2-й вариант
$template->setPath("html/t_dostavka/a_t_orders_foreach.html");
$in = $template->parse();

3-й вариант: сложные подключения:
content(T_FOTOBLOCK_DYNAMIC);
content(T_FOTOBLOCK_DYNAMIC_SUB); //! SUB - подключается после!
content_dynamic(T_FOTOBLOCK_DYNAMIC, $RESHENIYA, PARSE, "", true);

    Внимание! Чтобы данные передавались через json, надо выровнять их в одну строку
*/



//--------------------------------------------------------------
//внутри tpl
//tpl_name - имя шаблона, $variable - куда (в какую переменную запишется), $filename - истинное имя файла
function tpl($tpl_name, $variable = "", $filename = "", $array = array(), $parse = false){
	global $global;
	$template = $global['libraries']['template'];
	if ($filename == "") $filename = "tpl_index";
	$filename = $tpl_name . "/" . $filename;
	$template->includeConfigTemlpate($variable, "tpl", $array, $parse, $filename);
}
function tpl_dynamic($tpl_name, $variable = "", $filename = "", $array = array(), $parse = false){
	global $global;
	$template = $global['libraries']['template'];
	if ($filename == "") $filename = "tpl_index";
	$filename = $tpl_name . "/" . $filename;
	$template->includeConfigTemlpate($variable, "tpl_dynamic", $array, $parse, $filename);
}

//внутри html/content/ - подключение стороннего модуля (обычно это siteboundary)
//TrueFileName - истинное имя, необходимо для подмены зеркального имени file (file - переменная вставки, но файл может быть подменён)
function module($file, $array = array(), $TrueFileName = "", $parse = false){
	global $global;
	$template = $global['libraries']['template'];
	$template->includeConfigTemlpate($file, "module", $array, $parse, $TrueFileName);
}
//при использовании этого метода, необходимо все теги script, link - переместить из нашего файла в файл-родитель, куда наш файл подключается
// иначе возникнет ошибка
function module_for_js($file, $array = array(), $TrueFileName = "", $parse = false){
	global $global;
	$template = $global['libraries']['template'];
	$template->includeConfigTemlpate($file, "module", $array, $parse, $TrueFileName, false, "_SUB", true);
}


//внутри modules/ - подключение стороннего модуля
//TrueFileName - истинное имя, необходимо для подмены зеркального имени file (file - переменная вставки, но файл может быть подменён)
//Функционал Andrew 3.00.071
function module_($file, $array = array(), $TrueFileName = "", $parse = false){
	global $global;
	$template = $global['libraries']['template'];
	$template->includeConfigTemlpate($file, "module_", $array, $parse, $TrueFileName);
}
//внутри html/t_index/- динамично заполняемый файл (внутри цикл foreach)
//fullFileName - здесь название файла и название модуля одновременно через слэш: модуль/файл
function module__dynamic($file, $fullFileName, $arrayData = array(), $parse = false, $add_iterator = false, $suffix_example = "_SUB"){
	global $global;
	$template = $global['libraries']['template'];
	$template->includeConfigTemlpate($file, "module__dynamic", $arrayData, $parse, $fullFileName, $add_iterator, $suffix_example);
}

//внутри html/t_index/
function content($file, $array = array(), $TrueFileName = "", $parse = false){
	global $global;
	$template = $global['libraries']['template'];
	$template->includeConfigTemlpate($file, "content", $array, $parse, $TrueFileName);
}
function content_for_js($file, $array = array(), $TrueFileName = "", $parse = false){
	global $global;
	$template = $global['libraries']['template'];
	$template->includeConfigTemlpate($file, "content", $array, $parse, $TrueFileName, false, "_SUB", true);
}
//внутри html/t_index/- динамично заполняемый файл (внутри цикл foreach)
function content_dynamic($file, $arrayData = array(), $parse = false, $TrueFileName = "", $add_iterator = false, $suffix_example = "_SUB"){
	global $global;
	$template = $global['libraries']['template'];
	$template->includeConfigTemlpate($file, "content_dynamic", $arrayData, $parse, $TrueFileName, $add_iterator, $suffix_example);
}

//внутри html/
function html($file, $array = array(), $TrueFileName = "", $parse = false){
	global $global;
	$template = $global['libraries']['template'];
	$template->includeConfigTemlpate($file, "html", $array, $parse, $TrueFileName);
}
//внутри html/
function html_dynamic($file, $arrayData = array(), $parse = false, $TrueFileName = "", $add_iterator = false, $suffix_example = "_SUB"){
	global $global;
	$template = $global['libraries']['template'];
	$template->includeConfigTemlpate($file, "html_dynamic", $arrayData, $parse, $TrueFileName, $add_iterator, $suffix_example);
}

//внутри style/
function style($file, $array = array()){
	global $global;
	$template = $global['libraries']['template'];
	$template->includeConfigTemlpate($file, "css", $array);
}

//внутри default/ - 
function templdirectory($file, $array = array(), $TrueFileName = "", $parse = false){
	global $global;
	$template = $global['libraries']['template'];
	$template->includeConfigTemlpate($file, "templdirectory", $array, $parse, $TrueFileName);
}
//внутри default/
function templdirectory_dynamic($file, $arrayData = array(), $parse = false, $TrueFileName = "", $add_iterator = false, $suffix_example = "_SUB"){
	global $global;
	$template = $global['libraries']['template'];
	$template->includeConfigTemlpate($file, "templdirectory_dynamic", $arrayData, $parse, $TrueFileName, $add_iterator, $suffix_example);
}
//--------------------------------------------------------------
?>