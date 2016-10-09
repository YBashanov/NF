<?php if ( ! defined('andromed')) exit(''); 
/*
2013-06-25
*/

class L_csv {

	private static $thisObject = null;
	private static $error;
	public $separator = "";
	
	public static function init($error){
		if ( !isset(self::$thisObject) ){
			self::$thisObject = new L_csv();
			self::$error = $error;
			return self::$thisObject;
		}
		else {
			echo "L_csv: Объект L_csv уже создан ранее!";
			self::$error->add(2, 'Error: Объект уже создан!', 'L_csv');
			exit();
		}
	}
	
	//перевод из csv в двумерный массив
	public function csvToArray ($string, $separator_tr = '|', $separator_td = ';;') {
		$a_tr = array();
		$a_td = array();
		$a_tr = explode ($separator_tr, $string);
	
		for ($i = 0; $i<count($a_tr); $i++) {
			$a_td[$i] = explode ($separator_td, $a_tr[$i]);	
		}
		return $a_td;
	}
	
//таблица, реагирующая на движения мыши
//необходимо подключение скрипта table.js
/*
$string - строка
	если в ячейке структура такая: 4 %% данные, то в первой части стоит параметр colspan='4' align='center' (2012-12-18)
	если в ячейке 4 %% style='color:white' %% данные - во второй части стоит применяемый стиль ! но только один стиль, т.к. стили разделяются 
		точкой с запятой
$class - класс таблиц стилей
$subhead - число, показывающее, сколько строк будет написано в другом стиле "subhead" и без css-изменений.
	javascript также подключен

$separator_td - разделитель столбцов
$separator_tr - разделитель строк

$top  - соединитель с таблицей сверху
$bottom-соединитель с таблицей снизу
$left - соединитель с таблицей слева
$right- соединитель с таблицей справа

$array_style - стиль для ячеек (массив двумерный)
	в нем находятся классы стилей, применяемые к ячейкам
	
$under_subhead - строки в другом стиле "under_subhead", без css-изменений (под таблицей, аналог subhead) (2012-12-19)
*/
	private $triggerStyles = false;
	public function table(
		$string, $class, $subhead = 0, $top = 0, $bottom = 0, $left = 0, $right = 0,
		$separator_tr = '|', $separator_td = ';', $array_style = null, $under_subhead = 0
	){		
		$echo = "";
		$a_tr = array();
		$a_td = array();

		$a_tr = explode ($separator_tr, $string);
		$count_tr = count($a_tr);
		if ($under_subhead == 0) $under_subhead = $count_tr;

		if ($this->triggerStyles == false) {
			$echo .= "<script src='../js/table.js' type='text/javascript'></script>";
			
			$this->triggerStyles = true;
		}

		
		//проверка на соединение таблиц
		if ( $top != 0 || $left != 0 ) {}
		else {
			$echo .= "<table class='{$class}' cellpadding='0' cellspacing='0'>";
		}
		
		//строки subhead
		for ($i = 0; $i<$subhead; $i++) {
			$echo .= "<tr>";

			$a_td[$i] = explode ($separator_td, $a_tr[$i]);		

			for ($j = 0; $j<count($a_td[$i]); $j++) {
				$echo .= "<td class='subhead'
					onmouseover='overtd (\"{$class}\",\"{$i}\",\"{$j}\", \"{$count_tr}\")'
					onmouseout='outtd (\"{$class}\",\"{$i}\",\"{$j}\", \"{$count_tr}\")'
				>";
				$echo .= $a_td[$i][$j];
				$echo .= "</td>";
			}
			$echo .= "</tr>";
		}
		//обычные строки
		//for ($i = $subhead; $i<count($a_tr); $i++) {
		for ($i = $subhead; $i<$under_subhead; $i++) {
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

				//colspan
				$colspanArr = explode("%%", $a_td[$i][$j]);
				if (count($colspanArr) == 2) {
					$colspan = " colspan='{$colspanArr[0]}' align='center'";
					$a_td[$i][$j] = $colspanArr[1];
				}
				elseif (count($colspanArr) == 3) {
					$colspan = " colspan='{$colspanArr[0]}' align='center'";
					$colspan .= " " . $colspanArr[1];
					$a_td[$i][$j] = $colspanArr[2];
				}
				else $colspan = "";

				if ($j == 0) {//первый столбец 
					$echo .= "<td{$colspan} class='{$class}tr{$i} {$class}tr{$i}td0' id='{$style_id}'";
				}
				else {
					$echo .= "<td{$colspan} class='{$class}tr{$i} {$class}td{$j} {$class}tr{$i}td{$j}' id='{$style_id}'"; //4й вариант
				}
				$echo .= "
					onmouseover='overtd (\"{$class}\",\"{$i}\",\"{$j}\", \"{$count_tr}\")'
					onmouseout='outtd (\"{$class}\",\"{$i}\",\"{$j}\", \"{$count_tr}\" ,\"{$style_1td}\")'
				>";
				$echo .= $a_td[$i][$j];
				$echo .= "</td>";
			}
			$echo .= "</tr>";
		}
		//строки under_subhead
		for ($i = $under_subhead; $i<count($a_tr); $i++) {
			$echo .= "<tr>";

			$a_td[$i] = explode ($separator_td, $a_tr[$i]);		

			for ($j = 0; $j<count($a_td[$i]); $j++) {
				$echo .= "<td class='under_subhead'
					onmouseover='overtd (\"{$class}\",\"{$i}\",\"{$j}\", \"{$count_tr}\")'
					onmouseout='outtd (\"{$class}\",\"{$i}\",\"{$j}\", \"{$count_tr}\")'
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
}
?>