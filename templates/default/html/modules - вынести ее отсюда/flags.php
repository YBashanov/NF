<?php if ( ! defined('andromed')) exit('');

//1. Подключить в _content_management
//include "{$separator}templates/{$global['template']}/html/modules/flags.php";


$new_noindex_line = "";
$this_noindex_line = $global['noindex_line'];
$this_noindex_line = substr($this_noindex_line, 1, strlen($this_noindex_line)-4);
if (strlen($this_noindex_line) > 0) {
	$new_noindex_line = $this_noindex_line;
}


$in = "";
$in .= "<div class='flags'>";
	$in .= "<a href='{$base_url}{$new_noindex_line}ru/'><img src='{$base_image}files/ru.jpg' width='30' title=''/></a>";
	$in .= "<a href='{$base_url}{$new_noindex_line}en/'><img src='{$base_image}files/en.jpg' width='30' title=''/></a>";
$in .= "</div>";


$template->setVars(array("FLAGS"=>$in), "a");


//2. Вставить в шаблон переменную {FLAGS} (например, в шапку)
//3. Подключить и отформатировать стили
/*
div.flags{
	position:...;
	text-align:right;
}
div.flags img{
	border:1px solid #D4A764;
}
div.flags img:hover {
	border:1px solid #222;
	cursor:pointer;
}
*/
?>