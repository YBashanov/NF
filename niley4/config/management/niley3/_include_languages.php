<?php if ( ! defined('andromed')) exit(''); 
$thisFile = "data/_include_languages";
if ($global['is_language']) {
	include "{$separator}php/data/{$global['style_data']}/languages/_l_arrays.php";
}

/*
include "{$separator}data/languages/l_head.php";
include "{$separator}data/languages/l_menu.php";
include "{$separator}data/languages/l_allmenu.php";
include "{$separator}data/languages/l_rightcontent.php";
include "{$separator}data/languages/l_footer.php";
*/
if (
	$inDB 
	|| $page == "index"
) {
	if ($global['is_language']) {
		if (@file_exists("{$separator}data/languages/pages/l_{$page}.php")) {
			include "{$separator}data/languages/pages/l_{$page}.php";
		}
		else {
			if ($global['createConfigs']) {
				if ($errorTrue) $wrap->add(2, "Для данной страницы нет языкового файла l_{$page}/", "_include_languages");
				if ($file->createFileFromTemplate("{$separator}data/languages/pages/l_{$page}.php", $page, "l_page")) {
					if ($errorTrue) $wrap->add(2, "Языковой файл l_{$page} создан", "_include_languages");
				}
			}
			else {
				//if ($errorTrue) $wrap->add(2, "Автосоздание отключено. Для данной страницы нет языкового файла l_{$page}/", "_include_languages");
			}
		}
	}
}

elseif (
	$page == "printers"
){
	if ($global['is_language']) {
		if (@file_exists("{$separator}data/languages/pages/l_auxil.php")) {
			include "{$separator}data/languages/pages/l_auxil.php";
		}
	}
}

elseif (
	$page == "enter" ||
	$page == "admin" ||
	$page == "registration" ||
	$page == "activate" ||
	$page == "remember"
) {}

elseif (
	$page == "personal"
) {}
else {
	if ($errorTrue) {
		$wrap->add(2, "Для данной страницы нет настроек в data/ ({$page})", "_include_languages");
		$wrap->write();
		header ('Location: /', '', 301);
		exit();
	}
}
?>