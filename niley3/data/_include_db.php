<?php if ( ! defined('andromed')) exit(''); 
$thisFile = "data/_include_db";
$getdate = getdate();


//inDB
if (! $inDB) $inDB = false;
if (
	$page != "enter"
	&& $page != "admin"
	&& $page != "registration"
	&& $page != "remember"
	&& $page != "activate"
	&& $page != "personal"
) {
	include "{$separator}data/d_common.php";
}


if (
	$inDB 
	|| $page == "index"
) {
	if ($global['server'] == "internet") {
		if (@file_exists("{$separator}modules/counter_yandexmetrika.php")) {
			include "{$separator}modules/counter_yandexmetrika.php";
		}
	}
	
	
	if ($merger['page_type'] == 0) {
		if (@file_exists("{$separator}data/d_{$page}.php")) {
			include "{$separator}data/d_{$page}.php";
		}
		else {
			if ($global['createConfigs']) {
				if ($errorTrue) $wrap->add(2, "Для данной страницы нет файла выдачи данных d_{$page}/", "_include_db");
				if ($file->createFileFromTemplate("{$separator}data/d_{$page}.php", $page, "d_page")) {
					if ($errorTrue) $wrap->add(2, "Файл d_{$page} создан", "_include_db");
				}
			}
			else {
				//if ($errorTrue) $wrap->add(2, "Автосоздание отключено. Для данной страницы нет файла выдачи данных d_{$page}/", "_include_db");
			}
		}
	}
	//тип страницы не определен (либо определен в админке)
	else {
		//автоопределение типа страниц
		$table = "{$config['prefix']}templates";
		$where = "NOT(`deleted`) AND `id`='{$merger['page_type']}'";
		$what = "id, type, path";
		$_include_db_templates = $db->select_line($table, $where, $what, "id", $thisFile);

		if (! $_include_db_templates) {
			if ($errorTrue) $wrap->add(2, "Тип массива страниц не определен, =".$merger['page_type'], "_include_db");
		}
		else {
			if (@file_exists("{$separator}templates/{$global['template']}/tpl/{$_include_db_templates['path']}/db.php")) {
				include "{$separator}templates/{$global['template']}/tpl/{$_include_db_templates['path']}/db.php";
			}
			else {
				if ($errorTrue) $wrap->add(2, "В данном шаблоне (={$_include_db_templates['type']}) нет файла db.php", "_include_db");
			}
		}
	}
}

elseif (
	$page == "printers"
){
	if (@file_exists("{$separator}data/d_auxil.php")) {
		include "{$separator}data/d_auxil.php";
	}
}

elseif (
	$page == "enter" ||
	$page == "admin" ||
	$page == "registration" ||
	$page == "remember"
) {}
elseif (
	$page == "activate"
){
	include "{$separator}data/modules/enter/d_activate.php";
}

elseif (
	$page == "personal"
) {
	if (isset ($get['p']) ){
		include "{$separator}data/personal/d_{$get['p']}.php";
	}
}
else {
	if ($errorTrue) {
		if ($page == "uploads") die();
		
		$wrap->add(2, "Для данной страницы нет настроек в data/ ({$page})", "_include_db");
		$wrap->write();
		header ('Location: /', '', 301);
		exit();
	}
}
?>