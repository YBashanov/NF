<?php if ( ! defined('andromed')) exit(''); 

//13.08.12
//в стилях надо прописывать связь с id

//таблица, реагирующая на движения мыши
//необходимо подключение скрипта table.js
/*
$array - двумерный массив данных
$class - класс таблиц стилей
$subhead - число, показывающее, сколько строк будет написано в другом стиле "subhead" и без css-изменений.
	javascript также подключен

$explode_td - разделитель столбцов
$explode_tr - разделитель строк

$top  - соединитель с таблицей сверху
$bottom-соединитель с таблицей снизу
$left - соединитель с таблицей слева
$right- соединитель с таблицей справа

$array_style - стиль для ячеек (массив двумерный)
	в нем находятся классы стилей, применяемые к ячейкам
*/
function table(
	$array, $class, $subhead = 0, $top = 0, $bottom = 0, $left = 0, $right = 0,
	$separator_tr = '|', $separator_td = ';', $array_style = null, $separator = "../"
){
	$echo = "";
	$a_tr = array();
	$a_td = array();

	//$echo .= "<script src='{$separator}modules/functions/table/Table.js' type='text/javascript'></script>";
	
	//проверка на соединение таблиц
	if ( $top != 0 || $left != 0 ) {}
	else {
		$echo .= "<table class='{$class}' cellpadding='0' cellspacing='0'>";
	}
	
	$a_tr = explode ($separator_tr, $array);
	$count_tr = count($a_tr);

	//строки subhead
	for ($i = 0; $i<$subhead; $i++) {
		$echo .= "<tr>";

		$a_td[$i] = explode ($separator_td, $a_tr[$i]);		

		for ($j = 0; $j<count($a_td[$i]); $j++) {
			$echo .= "<td class='subhead'
				onmouseover='Table.overtd (\"{$class}\",\"{$i}\",\"{$j}\", \"{$count_tr}\")'
				onmouseout='Table.outtd (\"{$class}\",\"{$i}\",\"{$j}\", \"{$count_tr}\")'
			>";
			$echo .= $a_td[$i][$j];
			$echo .= "</td>";
		}
		$echo .= "</tr>";
	}
	//обычные строки
	for ($i = $subhead; $i<count($a_tr); $i++) {
		$echo .= "<tr id='noactive'>"; //4й вариант
		
		$a_td[$i] = explode ($separator_td, $a_tr[$i]);		
		$style_1td = 'noactive';//стиль для первого столбца - мы его сохраним в переменную
			//и обнулим его в начале новой строки
		
		for ($j = 0; $j<count($a_td[$i]); $j++) {
			//проверяем массив стилей
			
			$style_id = 'noactive';//стиль, заданный изначально
			
			
			if (is_array($array_style)) {
				if (isset($array_style[$i][$j])) {
					$style_id = $array_style[$i][$j];
					//чтобы запомнить стиль для первого столбца - мы его сохраним в переменную, но использовать будем
					// только в javascript
					$style_1td = $style_id;
				}
			}


			if ($j == 0) {//первый столбец
				$echo .= "<td class='{$class}tr{$i} {$class}tr{$i}td0' id='{$style_id}'";
			}
			else {
				$echo .= "<td class='{$class}tr{$i} {$class}td{$j} {$class}tr{$i}td{$j}' id='{$style_id}'"; //4й вариант
			}
			$echo .= "
				onmouseover='Table.overtd (\"{$class}\",\"{$i}\",\"{$j}\", \"{$count_tr}\")'
				onmouseout='Table.outtd (\"{$class}\",\"{$i}\",\"{$j}\", \"{$count_tr}\", \"{$style_1td}\")'
			>";
			$echo .= $a_td[$i][$j];
			$echo .= "</td>";
		}
		$echo .= "</tr>";
	}

	//проверка на соединение таблиц
	if ( $bottom != 0 || $right != 0 ) {}
	else {
		$echo .= "</table>"; 
	}
	
	
	return $echo;
}
?>