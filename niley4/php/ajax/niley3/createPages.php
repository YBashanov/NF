<?php
//2013-05-30
//2013-07-07
//помогает создать сразу все страницы с одинаковым содержимым
//создает из перечня меню

//комментарии
//при копировании htaccess - возможно одна или две первые строки будут непригодными
//при создании файлов в data и pages, возможно первые файлы будут непригодными из-за некорректного названия (без имени или php)

//необходимо:
//иметь файл data/!example_forAutoCreate.php, в котором будет содержимое для других файлов
//иметь файл pages/!example_forAutoCreate.php, в котором будет содержимое для других файлов

//последовательность:
//1. подключить файл с меню (массив с именами файлов) a_menu - одномерный массив

//блокировка
if (true) {
//---------------------------------------------------------------------------------------------------------------------------
define('andromed', '1');
$separator = "../";

//подключаем необязательные "вещи" для преобразования меню
include "{$separator}modules/includesToAjax.php";

$content1 = @file_get_contents("{$separator}pages/!example_forAutoCreate.php"); 
$content2 = @file_get_contents("{$separator}data/!example_forAutoCreate.php"); 
global $content1;
global $content2;
global $separator;

$forHtaccess = "";
$forManagement = "";
$forVariables = "";
global $forHtaccess;
global $forManagement;
global $forVariables;
//general - для файла htaccess и data/management (0, 1 - лишний пробел)
function createFile ($filename, $general=0, $value="") {
	global $content1;
	global $content2;
	global $forHtaccess;
	global $forManagement;
	global $forVariables;
	global $separator;
	$fullname1 = "{$separator}pages/" . $filename . ".php";
	$fullname2 = "{$separator}data/d_" . $filename . ".php";
	
	if ($filename !== "") {
	
//собрание текста для копирования в файл htaccess
if ($general==1) $forHtaccess .= "
";
$forHtaccess .= "RewriteRule ^{$filename}/$ pages/{$filename}.php [L]
";

//собрание текста для копирования в _content_management
if ($general==1) $forManagement .= "
";
$forManagement .= "$" . "page" . " == " . '"'.$filename.'"' . " ||
";

//собрание текста для копирования в variables
if ($general==1) $forVariables .= "
";
$forVariables .= "$" . "var['{$filename}']" . " = array(\"title\"=>\"{$value}\");
";

	
		if ($content1 != false) {
			//первый файл - в pages
			if (! file_exists($fullname1)){
				touch($fullname1);
				file_put_contents($fullname1, $content1);
				echo $fullname1 . " - записан<br>";
			}
			else {
				echo $fullname1 . " - <b>уже существует!</b><br>";
			}
		}
		else echo "Файл <b>не создан,</b> т.к. нет первого файла для заполнения (content1)<br>";
		
		if ($content2 != false) {
			//второй файл - в data
			if (! file_exists($fullname2)){
				touch($fullname2);
				file_put_contents($fullname2, $content2);
				echo $fullname2 . " - записан<br>";
			}
			else {
				echo $fullname2 . " - <b>уже существует!</b><br>";
			}
		}
		else echo "Файл <b>не создан,</b> т.к. нет второго файла для заполнения (content2)<br>";
	}
	else echo $filename . " - <b>не создан,</b> т.к. его название - пустое<br>";
}

//запись текста для копирования в файл htaccess и data/management
function createFiles(){
	global $forHtaccess;
	global $forManagement;
	global $forVariables;
	global $separator;
	$fullname1 = "{$separator}data/!for_htaccess.php";
	$fullname2 = "{$separator}data/!for_Management.php";
	$fullname3 = "{$separator}data/!for_Variables.php";
	
	if ($forHtaccess !== "") {
		if (! file_exists($fullname1)){
			touch($fullname1);
			file_put_contents($fullname1, $forHtaccess);
			echo "!for_htaccess - записан<br>";
		}
		else {
			echo "!for_htaccess - <b>уже существует!</b><br>";
		}
	}
	else echo "!for_htaccess - <b>не создан,</b> т.к. нет текста для его заполнения<br>";
	
	if ($forManagement !== "") {
		if (! file_exists($fullname2)){
			touch($fullname2);
			file_put_contents($fullname2, $forManagement);
			echo "!for_Management - записан<br>";
		}
		else {
			echo "!for_Management - <b>уже существует!</b><br>";
		}
	}
	else echo "!for_Management - <b>не создан,</b> т.к. нет текста для его заполнения<br>";
	
	if ($forVariables !== "") {
		if (! file_exists($fullname3)){
			touch($fullname3);
			file_put_contents($fullname3, $forVariables);
			echo "!for_Variables - записан<br>";
		}
		else {
			echo "!for_Variables - <b>уже существует!</b><br>";
		}
	}
	else echo "!for_Variables - <b>не создан,</b> т.к. нет текста для его заполнения<br>";
}
//---------------------------------------------------------------------------------------------------------------------------



//подключаем массив с именами файла
include "{$separator}data/variables_menu.php";
	
//тот же обработчик, что и при обработке самого меню (можно скопировать)
if ($a_menu) {
	for($i=0; $i<count($a_menu); $i++){
		$in .= "<div class='element'>";
	
		foreach($a_menu[$i] as $key=>$val) {
			if ($key !== "sub") {
				$in .= "<a href='{$base_url}{$key}/'>{$val}</a>";
				
				createFile($key, 1, $val);
			}
			else {
				$in .= "<div class='invisible'><div class='menu_2'>";
				
				foreach ($val as $key2=>$val2) {
					$in .= "<div class='element_2'><a href='{$base_url}{$key2}/'>{$val2}</a></div>";
					
					createFile($key2, 0, $val2);
				}
				
				$in .= "</div><div class='blockWhite'></div></div>";
			}
		}
		
		$in .= "</div>";
	}
}



//---------------------------------------------------------------------------------------------------------------------------
//после полного сканирования меню, можно вызвать данную функцию
createFiles();
}
?>