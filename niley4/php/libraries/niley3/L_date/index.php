<?php if ( ! defined('andromed')) exit(''); 
/*
Работы с датами
добавил 23.04.2012
10.06.2012 - _calendar() - отображение календаря без jquery
	необходимо подключить класс Date.js
12.06.2012 - _calendar() - изменена. Возвращается постоянно один блок (тело отдельно не возвращается, когда
	ajax=true, как в случае с calendar()
	Добавлены к ней в комплект - l_call_object.php, Date.js
	Отдельно вынес Calendar.css
15.06.12 - _calendar(): последняя javascript-функция вынесена за пределы html-кода в файл Date.js
22.06.12 - добавил определение сегодняшнего месяца - monthNow()
25.06.12 - добавил monthNowToTime() - возвращает 2 time() начала и конца месяца
26.06.12 - добавил getMonthName() - возвращает текстовое название месяца. Метод с возможностью расширения другими языками
28.08.12 - добавил monthNowTo_Time() - аналог monthNowToTime, но входные данные без номера месяца
2.09.12 - переход на вызов конструктора через init
2013-06-07 - убрал gen20 из calendar
2013-07-04 - hmsNow() - возвращает массив
2013-07-05 - getTime_fromString()
*/

class L_date {

	private $time_zone = 3;//часовой пояс
	private $path_jquery = "js/jquery-1.3.2.js";
	private $view_error = true;//разрешение/запрещение генерации ошибок-отчетов классом (true/false)
	private static $thisObject = null;
	private static $error;
	public $separator = "";
	
	public static function init($error){
		if ( !isset(self::$thisObject) ){
			self::$thisObject = new L_date();
			self::$error = $error;
			return self::$thisObject;
		}
		else {
			echo "L_date: Объект L_date уже создан ранее!";
			self::$error->add(2, 'Error: Объект уже создан!', 'L_date');
			exit();
		}
	}
	
	
//возвращает массив array(key='time()', val='18.00')...
	//timeStart - время time, с которого начнется отсчет часов
		//Если = 0, то от начала сегодняшнего дня (Настоящих суток)
	//timeEnd - время time, до которого будет формироваться массив часов. 
		//Если = 0, то до 0 часов сегодняшнего дня (до конца Настоящих суток)
	//start_0 - true - отсчет времени будет производиться четко от 0 часов дня, false - произвольно
	//end_0 - true - отсчет времени будет производиться четко до 0 часов следующего дня, false - произвольно
	//step - шаг массива времени (по умолчанию = 1 час)
	//format - формат выходных данных value массива
		//2 - часы и минуты
		//4 - день, месяц, часы, минуты
		//5 - день, месяц, год, часы, минуты
	public function hoursList($timeStart = 0, $timeEnd = 0, $start_0 = true, $end_0 = true, $step = 3600, $format = 2){
		if (is_int($step)){
			if ($step > 0 ){
				if (is_int($timeStart) ){
					if (is_int($timeEnd) ){
						//вычисляем целое представление timeStart относительно ближайшего часа
						if ( $timeStart == 0 ){
							$getdate = getdate(time());
							
							if ( $start_0 )	$getdate_hours = 0;
							else $getdate_hours = $getdate['hours'];
							
							$time_hour_0 = mktime($getdate_hours, 0, 0, $getdate['mon'], $getdate['mday'], $getdate['year']);
						}
						else {
							$getdate = getdate($timeStart);
							
							if ( $start_0 )	$getdate_hours = 0;
							else $getdate_hours = $getdate['hours'];
							
							$time_hour_0 = mktime($getdate_hours, 0, 0, $getdate['mon'], $getdate['mday'], $getdate['year']);
						}
						//вычисляем целое представление timeEnd относительно ближайшего часа
						if ( $timeEnd == 0 ){
							$getdate = getdate(time());
							
							if ( $end_0 )	$getdate_hours = 24;
							else $getdate_hours = $getdate['hours'];
							
							$time_hour_End = mktime($getdate_hours, 0, 0, $getdate['mon'], $getdate['mday'], $getdate['year']);
						}
						else {
							$getdate = getdate($timeEnd);
							
							if ( $end_0 )	$getdate_hours = 24;
							else $getdate_hours = $getdate['hours'];
							
							$time_hour_End = mktime($getdate_hours, 0, 0, $getdate['mon'], $getdate['mday'], $getdate['year']);
						}
						
						//создаем массив
						if ( $time_hour_0 <= $time_hour_End ){
							
							//проверяем заданный формат вывода
							if ( $format == 2 ){
								$array = array();
								for ( $i=$time_hour_0; $i<=$time_hour_End; $i+=$step ){
									$array[$i] = date("H.i", $i);
								}
								return $array;
							}
							elseif ( $format == 4 ) {
								$array = array();
								for ( $i=$time_hour_0; $i<=$time_hour_End; $i+=$step ){
									$array[$i] = date("d.m, H.i", $i);
								}
								return $array;
							}
							elseif ( $format == 5 ) {
								$array = array();
								for ( $i=$time_hour_0; $i<=$time_hour_End; $i+=$step ){
									$array[$i] = date("d.m.Y, H.i", $i);
								}
								return $array;
							}
							else {
								if ($this->view_error == true){
									self::$error->add(2, "hoursList: формат вывода не соответствует существующим критериям", get_class($this));
								}
								return false;
							}
						}
						//последняя временная метка меньше первой - массив создать не получится
						else{
							if ($this->view_error == true){
								self::$error->add(2, "hoursList: time_hour_0 > time_hour_End", get_class($this));
							}
							return false;
						}
					}
					else{
						if ($this->view_error == true){
							self::$error->add(2, "hoursList: timeEnd - не int", get_class($this));
						}
						return false;
					}
				}
				else{
					if ($this->view_error == true){
						self::$error->add(2, "hoursList: timeStart - не int", get_class($this));
					}
					return false;
				}
			}
			else {
				if ($this->view_error == true){
					self::$error->add(2, "hoursList: step - не int", get_class($this));
				}
				return false;
			}
		}
		else {
			if ($this->view_error == true){
				self::$error->add(2, "hoursList: step < 0", get_class($this));
			}
			return false;
		}
	}
	
	
//возвращает временную метку по заданным часам (18.00)
	//$hours - число (...13, 17, 5). 
		//Если не задано - возрващает time от начала суток
	//$time - временная метка, относительно которой (день месяц год) возвращается временная метка заданного часа
	public function hours_to_time($hours = 0, $time = 0 ){
		if ( is_int($time) ){
			
			if ( $time == 0 )$time = time();
			
			if ( is_int($hours) ){
				$getdate = getdate($time);
				$time_hour = mktime($hours, 0, 0, $getdate['mon'], $getdate['mday'], $getdate['year']);
				return $time_hour;
			}
			else {
				if ($this->view_error == true){
					self::$error->add(2, "hours_to_time: hours - не int", get_class($this));
				}
				return false;
			}
		}
		else {
			if ($this->view_error == true){
				self::$error->add(2, "hours_to_time: time - не int", get_class($this));
			}
			return false;
		}
	}

	
//устанавливает значение time по заданным - день, месяц, год
	//возвращает временную метку
	public function set_time($day=0, $month=0, $year=0){
		if ( $day < 1 ) $day = 1;
		if ( $month < 1 ) $month = 1;
		if ( $year < 1970 ) $year = 1970;
		
		return mktime(0, 0, 0, $month, $day, $year);
	}
	

//возвращает дату в формате "Y г, m мес, d д", или короче, если соответствущие элементы массива getdate=0
	//(возвращает отформатированную строку)
	public function time_to_date($time){
		if ( is_int($time) ){
			
			if ( $time >= 0 ) {
			
				if ( $time == 0 )$time = time();
				$getdate = getdate($time);
				
				//преобразование в связи с нахождением в другом часовом поясе
				$time2 = mktime(
					($getdate['hours']-$this->time_zone),
					$getdate['minutes'],
					$getdate['seconds'],
					$getdate['mon'],
					$getdate['mday'],
					$getdate['year']
				);
				$getdate2 = getdate($time2);
				
				$date_array = "Y г, m мес, d д";
				if ( $getdate2['year'] == 1970 && $getdate2['mon'] == 1 && $getdate2['mday'] == 1 && $getdate2['hours'] == 0 && $getdate2['minutes'] == 0)
					$date_array = "s сек";
				elseif ( $getdate2['year'] == 1970 && $getdate2['mon'] == 1 && $getdate2['mday'] == 1 && $getdate2['hours'] == 0)
					$date_array = "i мин";
				elseif ( $getdate2['year'] == 1970 && $getdate2['mon'] == 1 && $getdate2['mday'] == 1)
					$date_array = "H ч, i мин";
				elseif ( $getdate2['year'] == 1970 && $getdate2['mon'] == 1)
					$date_array = "d д, H ч, i мин";
				elseif ( $getdate2['year'] == 1970 )
					$date_array = "m мес, d д, H ч";

				$date = date($date_array, $time2);
				return $date;
			}
			else{
				if ($this->view_error == true){
					self::$error->add(2, "time < 0", get_class($this));
				}
				return false;
			}
		}
		else{
			if ($this->view_error == true){
				self::$error->add(2, "time_to_date: time - не int", get_class($this));
			}
			return false;
		}
	}
	

	
//вывод одного месяца - календарь (+ использует файл l_date/l_call_object для повторного вызова)
	//FormName 		- имя внешней формы с которой будет работать календарь
	//InputName 	- имя поля внешней формы, в которое будет возвращено значение выбранной даты
	//ajax=false 	- факт открытия через ajax. Если ДА - не будет открываться первый div
	//path_jquery	- для смены пути до jquery - если файл ajax будет находиться в другой папке (../)
	
	//для управления используются 
	//$_POST['SetMonth']
	//$_POST['SetYear']
	private $call_calendar_open = false;
	private $call_calendar_number = 0; 		//сколько раз вызывалась данная фунцкия + для нумерования div'ов
	private $pathToAjax = 'libraries/L_date/l_call_object.php';
	public function calendar($FormName, $InputName, $ajax=false, $path_jquery='../', $call_calendar_number=false){

		//..потому что эти файлы будут браться с конкретного уровня папок
		$pathToAjax = $path_jquery.$this->pathToAjax;
		
		if (file_exists($pathToAjax)){
		
			//определяем путь до jquery
			$path_jquery = $path_jquery.$this->path_jquery;

			//проверка пути до jquery
			if (file_exists($path_jquery)){
			
				$day = 86400;
				
				//определяем - для ajax - номер данного блока div - call_calendar_number
				if ( $call_calendar_number != false ) $this->call_calendar_number = $call_calendar_number;
				
				//ajax-блок
				if ( $ajax == false ) {$this->call_calendar_number++;}
			
				$menu = array(
					"01,Январь",
					"02,Февраль",
					"03,Март",
					"04,Апрель",
					"05,Май",
					"06,Июнь",
					"07,Июль",
					"08,Август",
					"09,Сентябрь",
					"10,Октябрь",
					"11,Ноябрь",
					"12,Декабрь"
				);

				//MKTIME ДЛЯ ВЫВОДА ДАННЫХ В СЕТКУ КАЛЕНДАРЯ
				if ( isset($_POST['time']) ) {
					//новая временная метка определена
					$getdate = getdate($_POST['time']);
					
					//MKTIME ДЛЯ ИЗМЕНЕНИЯ МЕСЯЦА И ГОДА В СЕТКЕ КАЛЕНДАРЯ

					if ( isset($_POST['SetMonth']) ){
						$time = mktime(0,0,0,$_POST['SetMonth'],1,$getdate['year']);
					}
					elseif ( isset($_POST['SetYear']) ){
						$time = mktime(0,0,0,$getdate['mon'],1,$_POST['SetYear']);
					}
					//если не выбран ни месяц, ни год
					else {
						$time = mktime(0,0,0,$getdate['mon'],1,$getdate['year']);
					}
					$getdate = getdate($time);
				}
				else {
					$getdate = getdate(time());
					$time = mktime(0,0,0,$getdate['mon'],1,$getdate['year']);
					$getdate = getdate($time);
				}

				
				//вычисляем номер следующего месяца
				if ( $getdate['mon'] < 12 ) {
					$next_month = $getdate['mon']+1;
					$next_year = $getdate['year'];
				}
				else {
					$next_month = 1;
					$next_year = $getdate['year']+1;
				}
				//вычисляем количество дней в выбранном месяце
				$time_NextMonth = mktime(0,0,0,$next_month,1,$next_year);
				$getdate_NextMonth = getdate($time_NextMonth);
				$daysInMonth = ($time_NextMonth - $time)/$day;
				//округляем до целого (март и октябрь выводятся не целыми числами)
				$daysInMonth = round($daysInMonth);
				

				//script-блок
				if ( $this->call_calendar_open == false ) {
					$print .= "<style>
						td.cell{text-align:center;}
						td.cell:hover{background-color:#3c3;}
						.head_cell{width:22px;}
					</style>";
					$print .= "<script type='text/javascript' src='{$path_jquery}'></script>";
					$print .= "<script type='text/javascript'>
						function set_month(id, FormName, InputName){
							var time = \"{$time}\";
							var a1 = document.getElementById('month_'+id+'');
							var SetMonth = a1.options[a1.selectedIndex].value;
							
							$('.calendar_button_'+id+'').attr('disabled','disabled');
							$('#month_'+id+'').attr('disabled','disabled');
							
							$.post(
								'{$pathToAjax}',
								'FormName='	+ FormName +
								'&InputName='	+ InputName +
								'&SetMonth=' 	+ SetMonth +
								'&time=' 		+ time +
								'&id='			+ id,
								function(data){
									complete:
									$('#date_calendar_'+id+'').html(data);
								},
								'html'
							);
						}
						function set_year(id, year, FormName, InputName){
							var time = \"{$time}\";

							$('.calendar_button_'+id+'').attr('disabled','disabled');
							$('#month_'+id+'').attr('disabled','disabled');
							
							$.post(
								'{$pathToAjax}',
								'FormName='	+ FormName +
								'&InputName='	+ InputName +
								'&SetYear=' 	+ year +
								'&time=' 		+ time +
								'&id='			+ id,
								function(data){
									$('#date_calendar_'+id+'').html(data);
								},
								'html'
							);
						}
					</script>";
					$this->call_calendar_open = true;
				}
				
				//ajax-блок
				if ( $ajax == false ) {$print .= "<div id='date_calendar_{$this->call_calendar_number}'>\n";}
				
					$NewMonth = sprintf("%02s", $getdate['mon']);
					$NewYear = $getdate['year'];
					$NewYear_m = $NewYear - 1;
					$NewYear_p = $NewYear + 1;
					
					$print .= "<form name='Calendar' action='' method='POST'>\n";
					$print .= "<table class='CalendarManage' align='center'>\n";
					$print .= "\t<tr>\n";
					$print .= "\t<td>\n";
					$print .= "\t<select name='SetMonth' id='month_{$this->call_calendar_number}' 
						onchange='javascript:set_month(\"{$this->call_calendar_number}\", \"{$FormName}\", \"{$InputName}\")'>\n";
					//выпадающий список select для выбора месяца
					foreach ($menu as $value) {
						$submenu = preg_split("/\,/", $value);
						$selected = "";
						if ($submenu[0] == $NewMonth) $selected = "selected";
						$print .= "\t\t<option {$selected} value = '{$submenu[0]}'>
							{$submenu[1]}</option>\n";
					}
					$print .= "\t</select>\n";
					$print .= "\t</td>\n";
					$print .= "\t<td>\n";
					//кнопки для выбора года
					$print .= "\t<input type='button' value='<' size=1 class='calendar_button_{$this->call_calendar_number}' 
						onclick='javascript:set_year(\"{$this->call_calendar_number}\", \"{$NewYear_m}\", \"{$FormName}\", \"{$InputName}\")' />\n";
					$print .= "\t<input type='text' name='SetYear' value='{$NewYear}' size=4 maxlength=4 readonly />\n";
					$print .= "\t<input type='button' value='>' size=1 class='calendar_button_{$this->call_calendar_number}' 
						onclick='javascript:set_year(\"{$this->call_calendar_number}\", \"{$NewYear_p}\", \"{$FormName}\", \"{$InputName}\")' />\n";
					$print .= "\t</td>\n";
					$print .= "\t</tr>\n";
					$print .= "</table>\n";
					
					
					//предварительное количество ячеек
					$total_cells = $daysInMonth;
					//отступ в количествах ячеек перед первым числом месяца
					$indent = 0;
					
					//вычисляем размеры месячной сетки
					//если 1 день недели - не понедельник, то перед ним должны быть пустые ячейки
					//если последний день недели - не воскресенье, то после него должны быть пустые ячейки
					if ( $getdate['wday'] != 1 ) { 
						
						//также рассчитаем, насколько необходимо отступить ячеек перед первым числом месяца
						if ( $getdate['wday'] == 0 ) $indent = 6;
						else $indent = $getdate['wday']-1;

						$total_cells += $indent;
					}
					if ( $getdate_NextMonth['wday'] != 1 ){
						if ( $getdate_NextMonth['wday'] == 0 ) $total_cells += 1;
						else $total_cells += 7-$getdate_NextMonth['wday']+1;
					}
					
					//всего недель (строк в сетке)
					$total_tr = $total_cells/7;
					$printDay = 1; //начинаем с первого дня
					
					//новая таблица - СЕТКА МЕСЯЦА
					$print .= "<table class='Calendar' border='1' align='center'>\n";
					$print .= "\t<tr style='background:#D3D3D3;font-weight:700'>
						<td class='head_cell'>Пн</td>
						<td class='head_cell'>Вт</td>
						<td class='head_cell'>Ср</td>
						<td class='head_cell'>Чт</td>
						<td class='head_cell'>Пт</td>
						<td class='head_cell'>Сб</td>
						<td class='head_cell'>Вс</td></tr>\n";
					//цикл по неделям
					for ( $i=0; $i<$total_tr; $i++ ){
						$print .= "\t<tr>\n";
						
						for ( $j=0; $j<7; $j++ ) {

							//цвет выходного дня (суббота и воскресенье)
							if (($j == 5) || ($j == 6)) $color="#FF9900";
							//цвет остальных дней и пустых ячеек
							else $color="#FFFFFF";

							//indent, если он есть - действует по накопительной схеме
							if ( $indent > 0 ) {
								$print .= "\t<td bgcolor=$color>&nbsp;</td>\n";
								$indent--;
							}
							else {
								if ( $printDay <= $daysInMonth ) {
									$FprintDay = sprintf("%02s", $printDay);
									$print .= "<td class='cell' bgcolor={$color} 
										style='cursor:pointer;'
										onClick=\"{$FormName}.{$InputName}.value='{$FprintDay}-{$NewMonth}-{$NewYear}'\">{$printDay}</td>";
									$printDay++; 
								}
								else{
									$print .= "\t<td bgcolor=$color>&nbsp;</td>\n";
								}
							}
						}
						$print .= "\t</tr>\n";
					}
					$print .= "</table>\n";
					$print .= "</form>\n";
				
				//ajax-блок
				if ( $ajax == false ) {$print .= "</div>\n";}
				
				return $print;
			}
			else{
				//echo "Неверный путь до jquery";
				//if ( $this->view_error ) self::$error->add(2, "popup_open: Не верный путь до jquery! {$path_jquery}", 'l_popup');
				return $this->_calendar($FormName, $InputName, $ajax=false, $call_calendar_number=false);
			}
		}
		else{
			echo "Неверный путь до Ajax-файла";
			if ( $this->view_error ) self::$error->add(2, "popup_open: Не верный путь до Ajax-файла: {$pathToAjax}", 'l_popup');
			return false;
		}
	}
	
	
//вывод одного месяца - календарь (+ использует файл l_date/l_call_object для повторного вызова)
//Без использования jquery!
	//FormName 		- имя внешней формы с которой будет работать календарь
	//InputName 	- имя поля внешней формы, в которое будет возвращено значение выбранной даты
	//$this->separator		- (../)
	//windowid - персональный номер открытого окна (если эти окна выпадающие). Это может быть id клиента или заказа...
	//inTheSameDiv - показывает, записывается ли внутри верхнего div (true), либо поверх него, с наложением и смещением (false - default)
	
	//для управления используются 
	//$_POST['SetMonth']
	//$_POST['SetYear']
	//outside_div - важное дополнение (2013-08-16) Говорит календарю о внешнем div-е, в котором происходит действие.
		//Нужно в основном для того, чтобы календарь исчезал после клика
	
	//Стили -  привязываемся к классу .Calendar
	private $_call_calendar_open = false;
	private $_call_calendar_number = 0; //сколько раз вызывалась данная фунцкия + для нумерования div'ов
	//т.е. сколько окон открыто
	
	//Если мы вызываем функцию так:
	// $date->_calendar("SearchForm0_{$val['id']}", "date_input0_1", false, 0, false, "calendar0_{$val['id']}");
	// третий параметр у нас =false, это приемлемо, если все календари будут открываться одновременно
	//Если нет, то желательно искусственно указывать нумерацию div-ов календарей, указывая вместо false число, например,
	// id товара
	public function _calendar($FormName, $InputName, $_call_calendar_number=false, $windowid=0, $inTheSameDiv = false, $outside_div = "calendar0_0"){
		
	
		//..потому что эти файлы будут браться с конкретного уровня папок
		$pathToAjax = $this->separator.$this->pathToAjax;
		
		if (file_exists($pathToAjax)){

			$day = 86400;
				
			//определяем - для ajax - номер данного блока div - _call_calendar_number
			if ( $_call_calendar_number != false ) $this->_call_calendar_number = $_call_calendar_number;
			else $this->_call_calendar_number++;
			
			$parent_div = "date_calendar{$windowid}_{$this->_call_calendar_number}";
			
			//изменяемый параметр извне!!! (по умолчанию принимаю так:)
			//$outside_div = "calendar{$windowid}_{$this->_call_calendar_number}";
			
			$menu = array(
				"01,Январь",
				"02,Февраль",
				"03,Март",
				"04,Апрель",
				"05,Май",
				"06,Июнь",
				"07,Июль",
				"08,Август",
				"09,Сентябрь",
				"10,Октябрь",
				"11,Ноябрь",
				"12,Декабрь"
			);


			//MKTIME ДЛЯ ВЫВОДА ДАННЫХ В СЕТКУ КАЛЕНДАРЯ
			if ( isset($_POST['time']) ) {
				//новая временная метка определена
				$getdate = getdate($_POST['time']);
				
				//MKTIME ДЛЯ ИЗМЕНЕНИЯ МЕСЯЦА И ГОДА В СЕТКЕ КАЛЕНДАРЯ
				if ( isset($_POST['SetMonth']) ){
					$time = mktime(0,0,0,$_POST['SetMonth'],1,$getdate['year']);
				}
				elseif ( isset($_POST['SetYear']) ){
					$time = mktime(0,0,0,$getdate['mon'],1,$_POST['SetYear']);
				}
				//если не выбран ни месяц, ни год
				else {
					$time = mktime(0,0,0,$getdate['mon'],1,$getdate['year']);
				}
				$getdate = getdate($time);
			}
			else {
				$getdate = getdate(time());
				$time = mktime(0,0,0,$getdate['mon'],1,$getdate['year']);
				$getdate = getdate($time);
			}

				
			//вычисляем номер следующего месяца
			if ( $getdate['mon'] < 12 ) {
				$next_month = $getdate['mon']+1;
				$next_year = $getdate['year'];
			}
			else {
				$next_month = 1;
				$next_year = $getdate['year']+1;
			}
			//вычисляем количество дней в выбранном месяце
			$time_NextMonth = mktime(0,0,0,$next_month,1,$next_year);
			$getdate_NextMonth = getdate($time_NextMonth);
			$daysInMonth = ($time_NextMonth - $time)/$day;
			//округляем до целого (март и октябрь выводятся не целыми числами)
			$daysInMonth = round($daysInMonth);
				

				if ($inTheSameDiv == false) $print .= "<div class='Calendar' id='{$parent_div}'>\n";

				if ( $this->_call_calendar_open == false ) {
					$print .= "<input type='hidden' id='pathToAjax' path='{$pathToAjax}'/>";
					$this->_call_calendar_open = true;
				}
				
				$NewMonth = sprintf("%02s", $getdate['mon']);
				$NewYear = $getdate['year'];
				$NewYear_m = $NewYear - 1;
				$NewYear_p = $NewYear + 1;
				
				$print .= "<input type='hidden' id='timenow_{$this->_call_calendar_number}' timenow='{$time}'/>";
				$print .= "<input type='hidden' id='windowid_{$this->_call_calendar_number}' info='{$windowid}'/>";
				$print .= "<input type='hidden' id='outside_div_{$this->_call_calendar_number}' info='{$outside_div}'/>";
				
				$print .= "<form name='Calendar' action='' method='POST'>\n";
				$print .= "<table id='buttonsTable_{$this->_call_calendar_number}' class='CalendarManage' align='center'>\n";
				$print .= "\t<tr>\n";
				$print .= "\t<td>\n";
				$print .= "\t<select class='month' name='SetMonth' id='month_{$this->_call_calendar_number}'
					onchange='javascript:Date.set_month(\"{$this->_call_calendar_number}\", \"{$FormName}\", \"{$InputName}\")'>\n";
				//выпадающий список select для выбора месяца
				foreach ($menu as $value) {
					$submenu = preg_split("/\,/", $value);
					$selected = "";
					if ($submenu[0] == $NewMonth) $selected = "selected";
					$print .= "\t\t<option {$selected} value = '{$submenu[0]}'>
						{$submenu[1]}</option>\n";
				}
				$print .= "\t</select>\n";
				$print .= "\t</td>\n";
				$print .= "\t<td>\n";
				//кнопки для выбора года
				$print .= "<nobr>";
				$print .= "\t<input class='button' type='button' value='<' size=1 id='calendar_button1_{$this->_call_calendar_number}' 
					onclick='javascript:Date.set_year(\"{$this->_call_calendar_number}\", \"{$NewYear_m}\", \"{$FormName}\", \"{$InputName}\")' />\n";
				$print .= "\t<input class='inputText' type='text' name='SetYear' value='{$NewYear}' size=4 maxlength=4 readonly />\n";
				$print .= "\t<input class='button' type='button' value='>' size=1 id='calendar_button2_{$this->_call_calendar_number}' 
					onclick='javascript:Date.set_year(\"{$this->_call_calendar_number}\", \"{$NewYear_p}\", \"{$FormName}\", \"{$InputName}\")' />\n";
				$print .= "</nobr>";
				$print .= "\t</td>\n";
				$print .= "\t</tr>\n";
				$print .= "</table>\n";
					
					
				//предварительное количество ячеек
				$total_cells = $daysInMonth;
				//отступ в количествах ячеек перед первым числом месяца
				$indent = 0;
				
				//вычисляем размеры месячной сетки
				//если 1 день недели - не понедельник, то перед ним должны быть пустые ячейки
				//если последний день недели - не воскресенье, то после него должны быть пустые ячейки
				if ( $getdate['wday'] != 1 ) { 
					
					//также рассчитаем, насколько необходимо отступить ячеек перед первым числом месяца
					if ( $getdate['wday'] == 0 ) $indent = 6;
					else $indent = $getdate['wday']-1;

					$total_cells += $indent;
				}
				if ( $getdate_NextMonth['wday'] != 1 ){
					if ( $getdate_NextMonth['wday'] == 0 ) $total_cells += 1;
					else $total_cells += 7-$getdate_NextMonth['wday']+1;
				}
					
				//всего недель (строк в сетке)
				$total_tr = $total_cells/7;
				$printDay = 1; //начинаем с первого дня
				
				//новая таблица - СЕТКА МЕСЯЦА
				$print .= "<table class='Calendar' border='1' align='center'>\n";
				$print .= "\t<tr class='trHead'>
					<td class='head_cell'>Пн</td>
					<td class='head_cell'>Вт</td>
					<td class='head_cell'>Ср</td>
					<td class='head_cell'>Чт</td>
					<td class='head_cell'>Пт</td>
					<td class='head_cell'>Сб</td>
					<td class='head_cell'>Вс</td></tr>\n";
				//цикл по неделям
				for ( $i=0; $i<$total_tr; $i++ ){
					$print .= "\t<tr class='tr'>\n";
						
					for ( $j=0; $j<7; $j++ ) {

						//цвет выходного дня (суббота и воскресенье)
						if (($j == 5) || ($j == 6)) {
							$tdClass = " weekend";
							$color="#FF9900";
						}
						//цвет остальных дней и пустых ячеек
						else {
							$tdClass = " others";
							$color="#FFFFFF";
						}

						//indent, если он есть - действует по накопительной схеме
						if ( $indent > 0 ) {
							$print .= "\t<td class='cell{$tdClass}' bgcolor={$color}>&nbsp;</td>\n";
							$indent--;
						}
						else {
							if ( $printDay <= $daysInMonth ) {
								$FprintDay = sprintf("%02s", $printDay);
								$print .= "<td class='cell{$tdClass}' bgcolor={$color} 
									style='cursor:pointer;'
									onClick='Date.setInput(\"{$FormName}\", \"{$InputName}\", \"{$FprintDay}\", \"{$NewMonth}\", \"{$NewYear}\", \"{$outside_div}\")'>{$printDay}</td>";
									//onClick='Date.setInput(\"{$FormName}\", \"{$InputName}\", \"{$FprintDay}\", \"{$NewMonth}\", \"{$NewYear}\", \"{$name_id}\", \"{$to_id}\", \"{$this->_call_calendar_number}\", \"{$windowid}\")'>{$printDay}</td>";
									//onClick=\"{$FormName}.{$InputName}.value='{$FprintDay}-{$NewMonth}-{$NewYear}'\">{$printDay}</td>";
								$printDay++; 
							}
							else{
								$print .= "\t<td class='cell{$tdClass}' bgcolor={$color}>&nbsp;</td>\n";
							}
						}
					}
					$print .= "\t</tr>\n";
				}
				$print .= "</table>\n";
				$print .= "</form>\n";
				if ($inTheSameDiv == false) $print .= "</div>\n";
			
			return $print;
		}
		else{
			echo "(2) Неверный путь до ajax-файла";
			if ( $this->view_error ) self::$error->add(2, "Не верный путь до Ajax-файла: {$pathToAjax}", 'L_date');
			return false;
		}
	}
	
	//преобразует time в дату календаря (23-04-2012)
	function timeToDate ($time) {
		if ($time != 0 && $time != "") {
			return date("d-m-Y", $time);
		}
		else return "";
	}
	
	//переводит дату 23-04-2012 в time
	function dateToTime ($dateStr, $separator = "-") {
		$dateArray = explode($separator, $dateStr);
		$time = mktime(0, 0, 0, $dateArray[1], $dateArray[0], $dateArray[2]);
		return $time;
	}
	
	//возвращает номер настоящего месяца
	public function monthNow ($time) {
		$getdate = getdate($time);
		return $getdate['mon'];
	}
	
	//возвращает номер часа, минуты и секунды - массивом
	public function hmsNow ($time) {
		$getdate = getdate($time);
		$array = array(
			0=>$getdate['hours'],
			1=>$getdate['minutes'],
			2=>$getdate['seconds']
		);
		return $array;
	}
	
	//возвращает номер дня, месяца, года
	public function dmyNow ($time) {
		$getdate = getdate($time);
		$array = array(
			0=>$getdate['mday'],
			1=>$getdate['mon'],
			2=>$getdate['year'],
			3=>$getdate['wday']
		);
		return $array;
	}
	
	//возвращает 2 временные метки - начало и конец месяца (1е и 1е числа этого и след.месяца, время 0-0-0)
	//month - от 1 до 12
	public function monthNowToTime ($month = false, $time = null) {
		if ($time == null) $time = time();
		if ($month == false) {
			$getdate = getdate ($time);
			$month = $getdate['mon'];
		}
	
		if ($month < 1 && $month > 12) {
			echo "l_date->monthNowToTime: ошибка вызова месяца (должен быть от 1 до 12)";
		}
		else {
			$year = $getdate['year'];
			
			if ($month == 12) {
				$month2 = 1;
				$year2 = $year + 1;
			}
			else {
				$month2 = $month + 1;
				$year2 = $year;
			}
			
			$mktime[0] = mktime (0, 0, 0, $month, 1, $year);
			$mktime[1] = mktime (0, 0, 0, $month2, 1, $year2);
			
			return $mktime;
		}
	}
	
	//возвращает 2 временные метки - начало и конец месяца (1е и 1е числа этого и след.месяца, время 0-0-0)
	//а также значение года и месяца в формате 2012_08
	//month - от 1 до 12
	public function monthNowTo_Time ($time = null) {
			if ($time == null) 
				$time = time();
			
			$getdate = getdate ($time);
			$year = $getdate['year'];
			$month = $getdate['mon'];
			
			if ($month == 12) {
				$month2 = 1;
				$year2 = $year + 1;
			}
			else {
				$month2 = $month + 1;
				$year2 = $year;
			}
			
			$mktime[0] = mktime (0, 0, 0, $month, 1, $year);
			$mktime[1] = mktime (0, 0, 0, $month2, 1, $year2);
			$mktime[2] = $year . "_" . $month;
			
			return $mktime;
	}
	
	//возвращает временную метку - начало суток (время 0-0-0)
	public function dayNowTo_Time ($time) {
		if ($time == null) 
			$time = time();
			
		$getdate = getdate ($time);
		$mktime = mktime (0, 0, 0, $getdate['mon'], $getdate['mday'], $getdate['year']);
		
		return $mktime;
	}
	
	//возвращает метки начала суток трех дней подряд (рабочих): сегодня-завтра-послезавтра (время 0-0-0)
	public function daysThreeTo_Time ($time) {
		if ($time == null) 
			$time = time();
		
		$getdate_1 = getdate ($time);
		$mktime_1 = mktime (0, 0, 0, $getdate_1['mon'], $getdate_1['mday'], $getdate_1['year']);

		$time_2 = $time + 86400;
		
		$getdate_2 = getdate ($time_2);
		if ($getdate_2['wday'] == 6){
			$time_2 += 86400*2;
			$getdate_2 = getdate ($time_2);
		}
		elseif ($getdate_2['wday'] == 0) {
			$time_2 += 86400;
			$getdate_2 = getdate ($time_2);
		}
		$mktime_2 = mktime (0, 0, 0, $getdate_2['mon'], $getdate_2['mday'], $getdate_2['year']);
		
		$time_3 = $time_2 + 86400;
		
		$getdate_3 = getdate ($time_3);
		if ($getdate_3['wday'] == 6){
			$time_3 += 86400*2;
			$getdate_3 = getdate ($time_3);
		}
		elseif ($getdate_3['wday'] == 0) {
			$time_3 += 86400;
			$getdate_3 = getdate ($time_3 + 86400);
		}
		$mktime_3 = mktime (0, 0, 0, $getdate_3['mon'], $getdate_3['mday'], $getdate_3['year']);
		
		$mktimes = array(
			0=>$mktime_1,
			1=>$mktime_2,
			2=>$mktime_3
		);
		
		return $mktimes;
	}
	
	//возвращает временную метку, спрятанную в строке (функция для редких случаев)
	//Строка формата: 04.07.2013, 08:12 или 04.07.2013, 08.12
	//функция негармоничная. Вызывает exit(), отображает echo
	public function getTime_fromString ($string){
		$a_dmyhm = explode(",", $string);
		
		if (count($a_dmyhm) < 2) {
			echo "2|Неверный формат даты. Нет запятой после года. Правильно - ДД.ММ.ГГГГ, ЧЧ:ММ";
			exit();
		}
		$a_dmy = explode(".", $a_dmyhm[0]);
		
		if (count($a_dmy) !== 3) {
			echo "2|Неверный формат даты. Дата разделяется точками. Правильно - ДД.ММ.ГГГГ, ЧЧ:ММ";
			exit();
		}
		$a_hm = explode(":", $a_dmyhm[1]);
		
		if (count($a_hm) !== 2) {
			$a_hm = explode(".", $a_dmyhm[1]);
			
			if (count($a_hm) !== 2) {
				echo "2|Неверный формат даты. Часы разделяются точками или двоеточием. Правильно - ДД.ММ.ГГГГ, ЧЧ:ММ";
				exit();
			}
		}

		settype($a_hm[0], int);
		settype($a_hm[1], int);
		settype($a_dmy[0], int);
		settype($a_dmy[1], int);
		settype($a_dmy[2], int);

		$time = mktime($a_hm[0], $a_hm[1], 0, $a_dmy[1], $a_dmy[0], $a_dmy[2]);
		return $time;
	}
	
	//возвращает название месяца (язык русский)
	public function getMonthName ($month, $language = "ru") {
		if ($language == "ru") {
			$names = array (
				"1"=>"Январь",
				"01"=>"Январь",
				"2"=>"Февраль",
				"02"=>"Февраль",
				"3"=>"Март",
				"03"=>"Март",
				"4"=>"Апрель",
				"04"=>"Апрель",
				"5"=>"Май",
				"05"=>"Май",
				"6"=>"Июнь",
				"06"=>"Июнь",
				"7"=>"Июль",
				"07"=>"Июль",
				"8"=>"Август",
				"08"=>"Август",
				"9"=>"Сентябрь",
				"09"=>"Сентябрь",
				"10"=>"Октябрь",
				"11"=>"Ноябрь",
				"12"=>"Декабрь"
			);
		}
		elseif ($language == "ru_ro") {
			$names = array (
				"1"=>"января",
				"01"=>"января",
				"2"=>"февраля",
				"02"=>"февраля",
				"3"=>"марта",
				"03"=>"марта",
				"4"=>"апреля",
				"04"=>"апреля",
				"5"=>"мая",
				"05"=>"мая",
				"6"=>"июня",
				"06"=>"июня",
				"7"=>"июля",
				"07"=>"июля",
				"8"=>"августа",
				"08"=>"августа",
				"9"=>"сентября",
				"09"=>"сентября",
				"10"=>"октября",
				"11"=>"ноября",
				"12"=>"декабря"
			);
		}
		elseif ($language == "ru_abb") {
			$names = array (
				"1"=>"янв",
				"01"=>"янв",
				"2"=>"фев",
				"02"=>"фев",
				"3"=>"мар",
				"03"=>"мар",
				"4"=>"апр",
				"04"=>"апр",
				"5"=>"мая",
				"05"=>"мая",
				"6"=>"июн",
				"06"=>"июн",
				"7"=>"июл",
				"07"=>"июл",
				"8"=>"авг",
				"08"=>"авг",
				"9"=>"сен",
				"09"=>"сен",
				"10"=>"окт",
				"11"=>"ноя",
				"12"=>"дек"
			);
		}
		elseif ($language == "en") {
			$names = array (
				"1"=>"January",
				"01"=>"January",
				"2"=>"February",
				"02"=>"February",
				"3"=>"March",
				"03"=>"March",
				"4"=>"April",
				"04"=>"April",
				"5"=>"May",
				"05"=>"May",
				"6"=>"June",
				"06"=>"June",
				"7"=>"July",
				"07"=>"July",
				"8"=>"August",
				"08"=>"August",
				"9"=>"Septemper",
				"09"=>"Septemper",
				"10"=>"October",
				"11"=>"November",
				"12"=>"December"
			);
		}
		
		return $names[$month];
	}
	
	//возвращает название дня недели (язык русский)
	public function getWeekdayName ($month, $language = "ru") {
		if ($language == "ru") {
			$names = array (
				"0"=>"воскресенье",
				"1"=>"понедельник",
				"2"=>"вторник",
				"3"=>"среда",
				"4"=>"четверг",
				"5"=>"пятница",
				"6"=>"суббота"
			);
		}
		return $names[$month];
	}
	
	//возвращает время суток
	public function getTimeofDay () {
		$getdate = getdate();
		$hours = $getdate['hours'];

		if ($hours >= 21 && $hours < 24 || $hours >= 0 && $hours < 5)
			$return = "night";
		elseif ($hours >= 5 && $hours < 11)
			$return = "morning";
		elseif ($hours >= 11 && $hours < 17)
			$return = "day";
		elseif ($hours >= 17 && $hours < 21)
			$return = "evening";

		//$return = "day";
			
		return $return;
	}
}
?>