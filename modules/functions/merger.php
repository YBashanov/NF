<?php  if ( ! defined('andromed')) exit('');
//2013-...
//2014-06-10 - html
//2014-06-20 - merger
//2014-09-19 - добавлена дополнительная анализирующая строка (именно для проекта dashome)
//2014-10-08 - разделение templateF... и merger()
//2014-11-23 - добавлен тип взаимодействия - get, добавлен тип-массив
//2015-03-18 - возвращает теперь ассоциативный массив
//2015-03-25. Правило организации меню. 
/*	1) Если требуется работа с active - необходимо в меню (админке) прописывать УРЛы каждому подразделу
	2) Если не требуется работа с active - можно подразделам просто прописывать УРЛ родительского раздела
*/	


//формирует массив-"меню" из нескольких массивов,
//объединяет массивы по принципу родительской вложенности. Критерий объединения - равенство id и parent_id

//arrays - выборка из нескольких таблиц базы данных, причем следующая ячейка - внутренняя вложенность
	//$arrays[0] - $menu
	//$arrays[1] - $menu_sub
	//$arrays[2] - $menu_sub2
//pages - массив страниц (те страницы, по которым ориентируется меню как по активным) 
		//для проверки присутствия в базе данной страницы
	//$pages[0] - $menu
	//$pages[1] - $menu_sub
	//$pages[2] - $menu_sub2
//type - тип работы. Самый распространенный - меню, где появляются такие переменные, как active и inDB
	//$type = "menu" - вычисление активной позиции происходит через page (array_line)
	//$type = false - отключает проверку в базе данных страниц и свойство active
	//$type = "get" - вычисление активной позиции происходит через get, сравнение - через id
	//также может быть массивом, каждая ячейка которого - свойство глубины вложенности (как pages)

//triggerStart - показывает, был ли вызов из самой себя
//------------------------------ саморегулирующиеся переменные ----------------------------------------
//i - показывает глубину проверки массива
//inDB - переходящая переменная. Если где-то будет возврат true, то больше не будет обработки данного места
//id - для прогона вложенного массива 

//switch - массив переменных, закрывающих часть кода для совершенно определенных "вещей", 
	//скрытый код определенно раздный в разных проектах, поэтому merger не перетекает из проекта в проект как upgrade
global $a_merger_numeral;
$a_merger_numeral = array();
function merger($arrays, $pages = array(), $type = false, $i = 0, $inDB = false, $id = 0, $switch = array()) {
	$a_new = array();
	$now_id 	= 0;
	$page_type 	= 0;
	$page_head 	= "";
	$page_text 	= "";
	global $a_merger_numeral;
	global $get;
		
	if ($arrays[$i]) {
		$a_merger_numeral[$i] = 1; //простой несквозной итератор
		foreach ($arrays[$i] as $key=>$val) {
			if ($id == $val['parent_id'] || $id == 0) {
				$a_new[$key] = $val;
				
				if (! is_array($type) ) {
					if ($type == "menu") {
						//здеcь href в меню определенного типа - либо /news/mynews/, либо /news/?id=555
						if ($pages[$i] == $val['href']) {
							$now_id = $val['id'];
							$page_type = $val['type'];
							$page_head = $val['head'];
							$page_text = $val['text'];
							$a_new[$key]['active'] = "active";
							$inDB = true;
						}
					}
					else if ($type == "get") {
						//здесь в pages просто номера. Это урл типа /33/543/11/
						if ($pages[$i] == $val['id']) {
							$now_id = $val['id'];
							$page_type = $val['type'];
							$page_head = $val['head'];
							$page_text = $val['text'];
							$a_new[$key]['active'] = "active";
							$inDB = true;
						}
					}
					else if($type == "redirect") {
						//ситуация, когда страница возвращается после редиректа с измененным page, тогда приходится ориентироваться не по page,
						// не по общему get, а именно по конкретному номеру get - 'sec3'=id
						//точнее - это не совсем редирект, это замена некрасивого УРЛ на красивый
						if ($get['sec'.$i] == $val['id']) {
							$now_id = $val['id'];
							$page_type = $val['type'];
							$page_head = $val['head'];
							$page_text = $val['text'];
							$a_new[$key]['active'] = "active";
							$inDB = true;
						}
					}
				}
				//type - тоже array
				else {
					if ($type[$i] == "menu"){
						if ($pages[$i] == $val['href']) {
							$now_id = $val['id'];
							$page_type = $val['type'];
							$page_head = $val['head'];
							$page_text = $val['text'];
							$a_new[$key]['active'] = "active";
							$inDB = true;
						}
					}
					elseif ($type[$i] == "get") {
						if ($pages[$i] == $val['id']) {
							$now_id = $val['id'];
							$page_type = $val['type'];
							$page_head = $val['head'];
							$page_text = $val['text'];
							$a_new[$key]['active'] = "active";
							$inDB = true;
						}
					}
					else if($type[$i] == "redirect") {
						if ($get['sec'.$i] == $val['id']) {
							$now_id = $val['id'];
							$page_type = $val['type'];
							$page_head = $val['head'];
							$page_text = $val['text'];
							$a_new[$key]['active'] = "active";
							$inDB = true;
						}
					}
				}

				$j = $i+1;
				
				if ($a_merger_numeral[$i-1]) $a_new[$key]['numeral_'.($j-1)] = $a_merger_numeral[$i-1];
				
				$a_new[$key]['numeral_'.$j] = $a_merger_numeral[$i];
				$a_new[$key]['get'] = "";
				$a_new[$key]['sub'] = array();

				
				$res = merger($arrays, $pages, $type, $j, $inDB, $val['id']);

				$a_new[$key]['sub'] 				= $res['array'];
				if ($val['isget']) $a_new[$key]['get'] = "?sec{$i}=".$val['id'];
				$inDB 								= $res['in_db'];
				if ($res['now_id']) $now_id 		= $res['now_id'];
				if ($res['page_type']) $page_type = $res['page_type'];
				if ($res['page_head']) $page_head = $res['page_head'];
				if ($res['page_text']) $page_text = $res['page_text'];

				$a_merger_numeral[$i]++;
			}
		}
	}

	return array(
		"array"		=>$a_new, 
		"in_db"		=>$inDB, 
		"now_id"	=>$now_id, 
		"page_type"	=>$page_type, 
		"page_head"	=>$page_head, 
		"page_text"	=>$page_text
	);
}
?>