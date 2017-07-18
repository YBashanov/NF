<?php if ( ! defined('andromed')) exit(''); 
if (! $thisFile) $thisFile = "data/d_common";
else $thisFile .= " + data/d_common";
	

$menu_sub2 = null;
if ($dbTrue) {
//	//для генерации меню
//	$table = "{$config['prefix']}menu";
//	$where = "NOT(`deleted`) AND `view`='1' ORDER BY `priority` ASC";
//	$what = "id, priority, type, name, href, isget, head, text";
//	$menu = $db->select($table, $where, $what, "id", $thisFile);
//
//	$table = "{$config['prefix']}menu_sub";
//	$where = "NOT(`deleted`) AND `view`='1' ORDER BY `parent_id` ASC, `priority` ASC";
//	$what = "id, parent_id, priority, type, name, href, isget, head, text";
//	$menu_sub = $db->select($table, $where, $what, "id", $thisFile);
//
//	//если выбран внутренний раздел (схема с адресной строкой типа ?sec1=, sec2=...)
//	if ($get['sec2']){
//		$table = "{$config['prefix']}menu_sub2";
//		$where = "NOT(`deleted`) AND `view`='1' AND `id`='{$get['sec2']}'";
//		//изменяемое
//		$what = "id, type, name, date_public, time_public, file, file_ext, text1, text2";
//		$menu_sub2 = $db->select_line($table, $where, $what, $thisFile);
//	}
}


//это формирование меню
//$a_new = array();
//
////тут важно добавлять именно page, т.к. она заменяется на основе seo_lynks
//$page1_active = $page."/";
//if ($array_line[2]) $page2_active = $page."/".$array_line[2]."/";
//else $page2_active = $page1_active;
//
////объединяем два массива в один по закону родительской вложенности
//$a_arrays = array($menu, $menu_sub);
//$a_pages = array($page1_active, $page2_active);
//$merger = merger($a_arrays, $a_pages, array("menu", "redirect"));
////$merger = merger($a_arrays, $a_pages, array("menu", $redirect_type?$redirect_type:"menu"));//это более правильно
//$a_new = $merger['array'];	//массив на выходе
//if ($inDB == false) $inDB = $merger['in_db'];	//факт присутствия в базе на выходе
//
////a($merger);
////$MENU = $merger['array'];
//
//if ($menu_sub2){
//	$merger['now_id'] = $menu_sub2['id'];
//	$merger['page_type'] = $menu_sub2['type'];
//	$merger['page_head'] = $menu_sub2['name'];
//	$merger['page_text'] = $menu_sub2['text1'].$menu_sub2['text2'];
//}
//
//$template->setVars(array(
//	PAGE_ID 	=> $merger['now_id'],
//	PAGE_HEAD 	=> $merger['page_head'],
//	PAGE_TEXT 	=> $merger['page_text']
//));
?>