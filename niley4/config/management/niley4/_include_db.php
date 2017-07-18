<?php if ( ! defined('andromed')) exit(''); 
//$getdate = getdate(); //это надо прописывать в global


//inDB
if (! $inDB) $inDB = false;


if (
	$inDB 
	|| $page == "index"
) {

}
elseif (
	$page == "printers"
){
	if (@file_exists("{$separator}php/data/{$global['style_data']}/d_auxil.php")) {
		include "{$separator}php/data/{$global['style_data']}/d_auxil.php";
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
	//include "{$separator}data/modules/enter/d_activate.php";
}

elseif (
	$page == "personal"
) {
	if (isset ($get['p']) ){
		//include "{$separator}data/personal/d_{$get['p']}.php";
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