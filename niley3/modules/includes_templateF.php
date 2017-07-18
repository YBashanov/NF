<?php  if ( ! defined('andromed')) exit('');

//--------------------------------------------------------------
//внутри html/content/ - подключение стороннего модуля (обычно это siteboundary)
//TrueFileName - истинное имя, необходимо для подмены зеркального имени file (file - переменная вставки, но файл может быть подменён)
function module($file, $array = array(), $TrueFileName = ""){
	global $global;
	$template = $global['libraries']['template'];
	$template->includeConfigTemlpate($file, "module", $array, false, $TrueFileName);
}
//внутри html/t_index/
function content($file, $array = array()){
	global $global;
	$template = $global['libraries']['template'];
	$template->includeConfigTemlpate($file, "content", $array);
}
//внутри html/
function html($file, $array = array()){
	global $global;
	$template = $global['libraries']['template'];
	$template->includeConfigTemlpate($file, "html", $array);
}
//внутри html/- динамично заполняемый файл (внутри цикл foreach)
function content_dynamic($file, $arrayData = array(), $parse){
	global $global;
	$template = $global['libraries']['template'];
	$template->includeConfigTemlpate($file, "content_dynamic", $arrayData, $parse);
}
//внутри style/
function style($file, $array = array()){
	global $global;
	$template = $global['libraries']['template'];
	$template->includeConfigTemlpate($file, "style", $array);
}
//--------------------------------------------------------------
?>