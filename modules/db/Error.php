<?php if ( ! defined('andromed')) exit(''); 
/*
определить:
- в каком месте она была вызвана (контроллер - метод)
- показать (не показывать) описание ошибки
2.09.12 - небольшие изменения (viewingOld теперь 2 параметра)
*/

class Error{
	const E_OTHER = 0;
	const E_DB = 1;
	const E_LINE = 2;
	const E_VIEW = 3;
	private $prefix;

	
	private $e_value = array(
		self::E_OTHER=>'Особо важные ошибки', //фиксируют вероятность взлома
		self::E_DB=>'Ошибки базы данных',
		self::E_LINE=>'Ошибки второй сложности', //важные места
		self::E_VIEW=>'Незначительные ошибки', //примечания
	);
	private $e_array = array(self::E_OTHER, self::E_DB, self::E_LINE, self::E_VIEW);
	public $errors = array();
	
	public function __construct($prefix = ""){
		$this->prefix = $prefix;
	}
	
	//добавление ошибок в объект
	//$internalCode - число от 0 до ..10
	//$code - код ошибки (код скрипта с ошибкой), если это mysql - код ошибки mysql
	//$message - сообщение об ошибке
	public function add($internalCode, $message, $classWhichCalled=''){
	//public function add($internalCode, $code, $message, $classWhichCalled=''){
		$time = time();
		$time = $this->timeToDate($time);
		
		if(in_array($internalCode, $this->e_array)){
			$this->errors[$internalCode][] = array(
				'internalCode'=>$internalCode,
				//'code'=>$code,
				'code'=>"",
				'message'=>$message, 
				'time'=>$time, 
				'classWhichCalled'=>$classWhichCalled
			);
			return true;
		}
		else {
			$this->add(0, '<b>Выбрана неверная категория ошибок:</b><br />НеизвестныйКод: '
				//.$internalCode.', code: '.$code.', message: '.$message.'<Br />Откуда: '
				.$internalCode.', message: '.$message.'<Br />Откуда: '
				.$classWhichCalled, get_class($this));
			return false;
		}
	}
	
	//вывод ошибок на экран
	public function printErrors(){
		if ( $this->errors ) {
			$in = "";
			foreach ($this->errors as $internalCode=>$error){
				$in .= "<table style='color:#555555;width:100%;font-size:12px;'>
				<tr>
					<td colspan=3 style='background-color:#555555;font-weight:700;color:#ffffff;' align='center'>
						".$this->e_value[$internalCode]."
					</td>
				</tr>";
				$in .= "<tr>
					<td align='center' bgcolor='#bbbbbb' width=10%>Время</td>
					<td align='center' bgcolor='#bbbbbb' width=75%>Сообщение</td>
					<td align='center' bgcolor='#bbbbbb' width=15%>Откуда</td>
				</tr>";
				foreach ($error as $err){
				$in .= "<tr>
					<td style='padding-left:5px;' bgcolor='#FFFFFF' width=10%>{$err['time']}</td>
					<td style='padding-left:5px;' bgcolor='#FFFFFF' width=75%>{$err['message']}</td>
					<td style='padding-left:5px;' bgcolor='#FFFFFF' width=15%>{$err['classWhichCalled']}</td>
				</tr>";
				}
				$in .= "</table>";
				
				echo $in;
			}
		}
	}

	//запись ошибок в базу!
	public function write($db=''){
		if ( is_object($db) ){
			if ( $this->errors ) {
				$insert = array();
				
				//редактирование класса ошибок
				if ( isset($_COOKIE['id']) ) $counter_number = $_COOKIE['id'];
				else $counter_number = 0;
				//-----------------------------
				
				foreach ($this->errors as $internalCode=>$error){
					foreach ($error as $err){
						$insert[] = array(
							'internalCode'=>$internalCode, 
							//'code'=>$err['code'],
							'time'=>$err['time'], 
							'message'=>$err['message'],
							'classWhichCalled'=>$err['classWhichCalled'],
							'ip'=>$_SERVER['REMOTE_ADDR'],
							'browser'=>$_SERVER['HTTP_USER_AGENT'],
							'counter_number'=>$counter_number
						);
					}
				}
				return $db->insert("{$this->prefix}errors", $insert, get_class($this));
			}
			else{
				$this->add(3, 'Массив ошибок пустой', get_class($this));
				return false;
			}
		}
		else {
			$this->add(2, 'Класс баз данных не подключен для занесения ошибок в базу', get_class($this));
			return false;
		}
	}
	
	//просмотр записанных ошибок (выборка из базы)
	//db - объект базы данных
	//time1 - начальное время просмотра
	//time2 - конечное время (если 0, то просмотр по настоящее время)
	public function viewingOld($db='', $limit = 10){

		if ( is_object($db) ){

			$where = "true ORDER BY `id` DESC LIMIT 0, {$limit}";
			$all_errors = $db->select("{$this->prefix}errors", $where);
			
			if ( $all_errors ) {
				$in = "";
				$in .= "
				<div id='_clearAllErrors' style='border:1px solid #444; background-color:#eee;width:200px;height:22px;text-align:center;padding-top:5px;font-size:12px;cursor:pointer;' onclick='Error.clear()'>
					Стереть все ошибки
				</div>
				<table style='color:#555555;width:100%; font-size:12px;'>
				<tr>
					<td colspan=8 style='background-color:#555555;font-weight:700;color:#ffffff;' align='center'>
						Все ошибки
					</td>
				</tr>";
				$in .= "<tr>
					<td align='center' bgcolor='#aaaaaa' width=5%>Название</td>
					<td align='center' bgcolor='#aaaaaa' width=5%>Время</td>
					<td align='center' bgcolor='#aaaaaa' width=50%>Сообщение</td>
					<td align='center' bgcolor='#aaaaaa' width=13%>Откуда</td>
					<td align='center' bgcolor='#aaaaaa' width=5%>ip</td>
					<td align='center' bgcolor='#aaaaaa' width=22%>Браузер</td>
				</tr>";
				foreach ($all_errors as $id=>$error){
					$in .= "<tr>
						<td style='padding-left:5px;' bgcolor='#FFFFFF' width=5%>({$error['internalCode']})</td>
						<td style='padding-left:5px;' bgcolor='#FFFFFF' width=5%>{$error['time']}</td>
						<td style='padding-left:5px;' bgcolor='#FFFFFF' width=50%>{$error['message']}</td>
						<td style='padding-left:5px;' bgcolor='#FFFFFF' width=13%>{$error['classWhichCalled']}</td>
						<td style='padding-left:5px;' bgcolor='#FFFFFF' width=5%>{$error['ip']}</td>
						<td style='padding-left:5px;' bgcolor='#FFFFFF' width=22%>{$error['browser']}</td>
					</tr>";
				}
				$in .= "</table>";
				
				echo $in;
			}
			else {
				$this->add(2, 'В функции Error.viewingOld запрос SELECT оказался пустым. WHERE: '.$where, get_class($this));
				return false;
			}
		}
		else {
			$this->add(2, 'Класс баз данных не подключен для выборки информации из базы, viewingOld', get_class($this));
			return false;
		}
	}
	
	public function clearErrors ($db) {
		if ($db->query("TRUNCATE TABLE {$this->prefix}errors"))
			return true;
		else return false;
	}
	
	private function timeToDate ($time) {
		$date = date("Y.m.d H:i:s", $time);
		return $date;
	}
	private function dateToTime ($date) {
		$d = explode(".", $date);
		$time = mktime($d[3], $d[4], $d[5], $d[1], $d[2], $d[0]);
		return $time;
	}
}
?>