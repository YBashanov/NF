<?php if ( ! defined('andromed')) exit('');
//подключаем libraries (обычно в корне - в includes)
include "{$separator}libraries/L_date/index.php";
$date = L_date::init ($wrap);
$date->separator = $separator;
$global['libraries']['date'] = $date;


//подключаем javascript (в файле модул€)
"<script src='{$separator}libraries/L_date/Date.js'></script>";


//сначала js-код - присваивает стили div-блокам
Popup.open ("calendar0_"+id, true);
Popup.openClose("calendar0_"+id); //сразу скрываем div-блоки
Popup.open ("calendar1_"+id, true);
Popup.openClose("calendar1_"+id);


//html - код (теперь div-блоками можно управл€ть - отображать и скрывать)
$in2 .= "<div class='td new1'>
	<form name='SearchForm0_{$val['id']}'>
		<a onclick='Popup.openClose(\"calendar0_{$val['id']}\")'>
			<input name='date_input0_1' id='time_public{$val['id']}' class='text' type='text' value='{$val['time_public']}' readonly title='ƒата публикации'/>
		</a>
	</form>
	<div id='calendar0_{$val['id']}'>";
		$in2 .= $date->_calendar("SearchForm0_{$val['id']}", "date_input0_1", "0_{$val['id']}", 0, false, "calendar0_{$val['id']}");
	$in2 .= "</div>";
	$in2 .= $popup->popupOpen_style("calendar0_{$val['id']}", 3, 21);
$in2 .= "</div>";
$in2 .= "<div class='td new1'>
	<form name='SearchForm1_{$val['id']}'>
		<a onclick='Popup.openClose(\"calendar1_{$val['id']}\")'>
			<input name='date_input1_1' id='time_public_end{$val['id']}' class='text' type='text' value='{$val['time_public_end']}' readonly title='ƒата публикации'/>
		</a>
	</form>
	<div id='calendar1_{$val['id']}'>";
		$in2 .= $date->_calendar("SearchForm1_{$val['id']}", "date_input1_1", "1_{$val['id']}", 0, false, "calendar1_{$val['id']}");
	$in2 .= "</div>";
	$in2 .= $popup->popupOpen_style("calendar1_{$val['id']}", 3, 21);
$in2 .= "</div>";
?>