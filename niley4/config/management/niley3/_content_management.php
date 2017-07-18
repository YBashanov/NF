<?php if ( ! defined('andromed')) exit('');


if (
	$inDB 
	|| $page == "index"
) {
    includes ("{$path_tpl}_modules_cont_management.php");
    include "{$separator}view/js/modules/highslide/include.php";

	include "{$path_tpl}html/config.php";
	include "{$path_tpl}css/config.php";
	
	//system type - тип страницы определен системой
	if ($merger['page_type'] == 0) {
		if (@file_exists("{$path_tpl}html/t_{$page}/config.php")) {
			include "{$path_tpl}html/t_{$page}/config.php";
		}
		else {
			if ($global['createConfigs']) {
				if ($errorTrue) $wrap->add(2, "Для данной страницы нет управляющего файла {$page}/config", "_content_management");
				
				if ($file->catalogCreate("{$path_tpl}html/t_{$page}")){
					if ($errorTrue) $wrap->add(2, "Каталог template/../{$page}/ создан", "_content_management");
				}
				if ($file->createFileFromTemplate("{$path_tpl}html/t_{$page}/config.php", $page, "config")) {
					if ($errorTrue) $wrap->add(2, "Файл {$page}/config создан", "_content_management");
				}
				if ($file->createFileFromTemplate("{$path_tpl}html/t_{$page}/t_content.html", $page, "t_content")) {
					if ($errorTrue) $wrap->add(2, "Файл {$page}/t_content создан", "_content_management");
				}
			}
			else {
				//if ($errorTrue) $wrap->add(2, "Автосоздание отключено. Для данной страницы нет управляющего файла {$page}/config", "_content_management");
			}
		}
	}
	//тип страницы не определен (либо определен в админке)
	else {
		if (! $_include_db_templates) {
			if ($errorTrue) $wrap->add(2, "Тип массива страниц не определен, =".$merger['page_type'], "_content_management");
		}
		else {
			if (@file_exists("{$separator}templates/{$global['template']}/tpl/{$_include_db_templates['path']}/config.php")) {
				include "{$separator}templates/{$global['template']}/tpl/{$_include_db_templates['path']}/config.php";
			}
			else {
				if ($errorTrue) $wrap->add(2, "В данном шаблоне (={$_include_db_templates['type']}) нет файла config", "_content_management");
			}
		}
	}
	
	
	$template->setPath("v_index.html");
	$template->parse(HTML);
	$template->prints(HTML);
}


elseif (
	$page == "printers"
){
	if (@file_exists("{$path_tpl}html/t_auxil/config.php")) {
		include "{$path_tpl}html/t_auxil/config.php";
	}

	$template->setPath("v_auxil.html");
	$template->parse(HTML);
	$template->prints(HTML);
}
//раздел входа и активации
elseif (
	$page == "enter" ||
	$page == "admin" ||
	$page == "registration" ||
	$page == "activate" 
	//$page == "remember"
){
	if ($global['authorization']) {
		header ("Location: {$var['header_personal']}", '', 301);
		die();
	}
	include "{$separator}templates/{$global['template']}/html/config.php";
	include "{$separator}templates/{$global['template']}/css/config.php";
	
	if (
		$page == "enter" ||
		$page == "admin"
	) {
		include "{$separator}templates/{$global['template']}/html/modules/enter/e_conf_enter.php";
	}
	elseif ($page == "registration") {
		include "{$separator}templates/{$global['template']}/html/modules/enter/e_conf_registration.php";
	}
	
	$template->setPath("v_enter.html");
	$template->parse(HTML);
	$template->prints(HTML);
}
elseif (
	$page == "personal"
) {
	if (! $global['authorization']) {
		header ("Location: {$var['header_main']}", '', 301);
		die();
	}
    //include "{$separator}view/js/modules/highslide/include.php";
	include "{$separator}data/_content_management_v2.php";
	
	include "{$separator}templates/personal/html/t_exit.php";
	include "{$separator}templates/personal/html/panel_head.php";
	include "{$separator}templates/personal/html/panel_menuPersonal.php";
	include "{$separator}data/personal/panel_d_menuPersonal.php";
	
	if (isset ($get['p']) ){
		$data['pageTitle'] = $var[$get['p']]['pageTitle'];
		include "{$separator}templates/personal/html/t_{$get['p']}.php";
		
		if (@file_exists("{$separator}templates/personal/html/t_{$get['p']}_Sort.php")) {
			include "{$separator}templates/personal/html/t_{$get['p']}_Sort.php";
		}
	}
	
	include "{$separator}htaccess.php";
	include "{$separator}templates/personal/v_personal.php";
	echo template($data, $separator);
}
else {
	if ($errorTrue) {
		$wrap->add(2, "Для данной страницы нет настроек в data/ ({$page})", "_content_management");
		$wrap->write();
		header ('Location: /', '', 301);
		exit();
	}
}
?>