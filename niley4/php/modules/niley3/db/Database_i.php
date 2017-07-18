<?php if ( ! defined('andromed')) exit(''); 
/*
класс, работающий с базой данных mysqli
21.06 
12.08.2012 - без prefix (таблицы вызываются из sql-запросов)
16.09.12 - усовершенствование. Добавлено свойство lastQuery - сюда записывается последний запрос
*/
include_once "Explain.php"; //добавлен модуль

final class Database_i{
	
	private $host = '';
	private $user = '';
	private $pass = '';
	private $db = '';
	private $character_set;
	private $db_tables = array();	//создаваемые таблицы
	public $error;
	public $lastQuery = "";
	
	//true false
	private $view_speed = false;		//разрешение/запрещение записи просмотра скорости выполнения запросов
	private $view_query = false;		//разрешение/запрещение записи просмотра скорости выполнения запросов
	private $all_time_query = 0;	//суммарное время запросов
	
	private $link = '';//ресурс объекта
	
	public function __construct($host, $user, $pass, $db, $character_set, $error){
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->db = $db;
		$this->character_set = $character_set;


		$this->error = $error;			//объект Error
		
		$this->link = $this->connect();
		
        $this->explain = new Explain($this->link, $error);
	}
	
	public function __destruct(){
		//$this->close_connect($this->link);
	}

//-----------------------------------------------------------------------------------------------------
//ВЫПОЛНЕНИЕ ЗАПРОСОВ
//-----------------------------------------------------------------------------------------------------

//запросы выполняются тут
	public function query ( $query, $link='', $method='' ) {
		if ( $link == '' ) $link = $this->link;

		if ( $this->view_speed ) {
			$start_time = $this->getmicrotime();
		}

		$result = @mysqli_query($link, $query); //resource, true, false
		$error = @mysqli_error($link);

        if ($error) {
            $this->error->add($error);
        }

		if ( $this->view_query ) {
			echo $query;
			echo '<br />';
		}
		
		if ( $this->view_speed ) {
			$end_time = $this->getmicrotime();
			$queries_time = ($end_time - $start_time);
			$this->all_time_query += $queries_time;
			$this->error->add("<b>{$method}</b><br />time={$queries_time}<br />t_all={$this->all_time_query}<Br />query={$query}", 'database');
		}
		return $result;
	}

//вспомагательная функция для определения microtime
	private function getmicrotime(){ 
		list($usec, $sec) = explode(" ", microtime()); 
		return ((float) $usec + (float) $sec); 
	}
//-----------------------------------------------------------------------------------------------------
//СОЗДАНИЕ ТАБЛИЦ
//-----------------------------------------------------------------------------------------------------
	/**
     * Создание стартовых таблиц
     */
    public function createTables($db_tables){
        $this->db_tables = $db_tables;
        $this->create_tables();
	}

//проверка созданных таблиц БД
//если таблицы нет, она создается
	private function create_tables(){
		if ( $this->db_tables ) {
			//получение массива имен таблиц
			foreach ( $this->db_tables as $key=>$val ){
				if ( $this->show($key) == FALSE ) {
					if ($this->create($val, $this->link))$this->error->add("Таблица '{$key}' создана", get_class($this));
					else $this->error->add("Таблицу '{$key}' создать не удалось", get_class($this));
				}
			}
		}
	}
	
//проверка существования таблицы в базе
	private function show($table){
		$query = "SHOW TABLES LIKE '{$table}'";
		$this->lastQuery = $query;
		
		$result = $this->query($query, '', 'show') or die('');
		$row = mysqli_fetch_assoc($result);
		if ( $row == FALSE ) { 
			$this->error->add("Таблицы '{$table}' нет в базе", get_class($this));
			return FALSE;
		}
		else { return TRUE;}
	}
	
//создание таблицы в базе
	private function create($query, $link){
		if ( $this->query($query, $link, 'create') ) { return TRUE;}
		else {
			$this->error->add("Не удалось создать таблицу. Ошибка: ".mysqli_error($link)."<br />Запрос: {$query}", get_class($this));
			return FALSE;
		}
	}

//-----------------------------------------------------------------------------------------------------
//СОЕДИНЕНИЕ С БАЗОЙ
//-----------------------------------------------------------------------------------------------------
	
//создание соединения с базами без разрыва
	private function pconnect(){
		if (!$this->link){
			$host = $this->host;
			$user = $this->user;
			$pass = $this->pass;
			$db = $this->db;
		
			$link = @mysqli_pconnect($host, $user, $pass, $db);
			if ( !$link ) {
				echo "Pconnect: Невозможно подключиться к серверу.";
				$this->error->add("Pconnect: Невозможно подключиться к серверу. Ошибка: ".mysqli_error($link), get_class($this));
				return;
			}
			
			if ( @mysqli_select_db($link, $db)){}
			else {
				echo "Pconnect: Невозможно открыть БД.";
				$this->error->add("Pconnect: Невозможно открыть БД {$db}. Ошибка: ".mysqli_error($link), get_class($this));
				return;
			}
				
			if (@mysqli_query($link, "SET CHARACTER SET ".$this->character_set)){}
			else {
				echo "Pconnect: Невозможно перевести кодировку.";
				$this->error->add("Pconnect: Невозможно перевести кодировку. Ошибка: "
					.mysql_error().", кодировка - ".$this->character_set, get_class($this));
				return;
			}
				
			return $link;
		}
		else{
			return $this->link;
		}
	}
	
//создание соединения с базами с разрывом
	private function connect(){
		$host = $this->host;
		$user = $this->user;
		$pass = $this->pass;
		$db = $this->db;
		
    	$link = @mysqli_connect($host, $user, $pass, $db);
    	if ( !$link ) {
			echo "Connect: Невозможно подключиться к серверу.";
    		$this->error->add("Connect: Невозможно подключиться к серверу. Ошибка: ".mysqli_error($link), get_class($this));
    	}
		
    	if ( @mysqli_select_db($link, $db)){}
		else {
			echo "Connect: Невозможно открыть БД.";
			$this->error->add("Connect: Невозможно открыть БД {$db}. Ошибка: ".mysqli_error($link), get_class($this));
			return;
		}
			
		if (@mysqli_query($link, "SET CHARACTER SET ".$this->character_set."")){}
		else {
			echo "Connect: Невозможно перевести кодировку.";
			$this->error->add("Connect: Невозможно перевести кодировку. Ошибка: "
				.mysql_error().", кодировка - ".$this->character_set, get_class($this));
			return;
		}
			
		return $link;
	}

//закрытие соединения (при создании соединения с разрывом)
	private function close_connect( $link ){
		//если link является ресурсом
		if (is_resource($link)){
			
			if ( @mysqli_close ( $link )) { return true; } 
			else {
				echo "<br />Close_connect: Невозможно отключиться от сервера.";
				$this->error->add("Close_connect: Невозможно отключиться от сервера. Ошибка: ".mysqli_error($link), get_class($this));
				return false;
			}
		}
		else{
			echo "<br />Close_connect: link не является ресурсом.";
			$this->error->add("Close_connect:link не является ресурсом!", get_class($this));
			return false;
		}
	}

//------------------------------------------------------------------------------------------------------
//ОПЕРАТОРЫ
//------------------------------------------------------------------------------------------------------

//ОПЕРАТОР SELECT

//выбрать всё - возвращает полный двумерный массив таблицы, первой мерой которого является 
	//..выбранный столбец $key (как правило, выбирается уникальный столбец для отображения более полной
	//..таблицы данных
	//$mysql_num - если надо вернуть числовой массив (числа в 1м уровне массива)
	public function select($table, $where = 'true', $what='*', $key = 'id', $classWhichCalled='', $mysql_num = false) {
		$link = $this->link;
		$query = "SELECT {$what} FROM {$table} WHERE {$where}";
		$this->lastQuery = $query;
		
		$res = $this->query ( $query, $link, 'select' );
		if ( $res ) {
			if ($mysql_num == false) {
				while ( $row = @mysqli_fetch_assoc($res) ) {
					if(is_array($key)){
						$r = '';
						foreach ($key as $v){
							$r .= $row[$v];
						}
					} 
					else {
						$r = $row[$key];
					}
					$result[$r] = $row;
				}
			}
			else {
				$r = -1;
				while ( $row = @mysqli_fetch_assoc($res) ) {
					if(is_array($key)){
						foreach ($key as $v){
							$r++;
						}
					} 
					else {
						$r++;
					}
					$result[$r] = $row;
				}
			}
		}
		else $this->error->add('оператор SELECT: '.mysqli_error($link).'<Br />запрос: '.$query, $classWhichCalled);
		return $result;
	}

//выбрать только одну строку - 
	//- первое в списке совпадений
	//- ИЛИ уникальную строку в зависимости от $where
	public function select_line($table, $where = 'true', $what='*', $classWhichCalled=''){
		$link = $this->link;
		$query = "SELECT {$what} FROM {$table} WHERE {$where}";
		$this->lastQuery = $query;
		
		$res = $this->query ( $query, $link, 'select_line' ) or (
				$this->error->add('select_line: '.mysqli_error($link).'<Br />запрос: '.$query, $classWhichCalled)
			);
		//if ($res)...
		return @mysqli_fetch_assoc($res);
	}

//выбрать только один столбец, 
	//возвращает числовой массив со значениями всего столбца (удобно для поиска конкретной информации в 
	//..известном столбце
	//$q_array - название столбца
	public function select_column($table, $where = 'true', $what='*', $q_array='id', $classWhichCalled=''){
		$link = $this->link;
		$query = "SELECT {$what} FROM {$table} WHERE {$where}";
		$this->lastQuery = $query;
		
		$res = $this->query ( $query, $link, 'select_column' ) or (
				$this->error->add('select_column: '.mysqli_error($link).'<Br />запрос: '.$query, $classWhichCalled)
			);
		
		if ( is_array($q_array) ) {
			$j=0;//номер строки в ТБД
			while ( $row = mysqli_fetch_assoc($res) ) {
				for ( $i=0; $i<count($q_array); $i++ ) {
					$ret_arr[$q_array[$i]][$j] = $row[$q_array[$i]];
				}
				$j++;
			}
		}
		else {
			$j=0;//номер строки в ТБД
			while ( $row = mysqli_fetch_assoc($res) ) {
					$ret_arr[$j] = $row[$q_array];
				$j++;
			}
		}
		return $ret_arr;
	}

//ОПЕРАТОР INSERT
//добавить новую строку в базу
	//$data - ассоц.массив(поля-значения)
	public function insert($table, $data, $classWhichCalled=''){
		$link = $this->link;
		$data = $this->arrayToArrForInsert($data);
		$query = "INSERT INTO {$table} ({$data['fields']}) VALUES {$data['values']}";
		$this->lastQuery = $query;
		
		if(!$this->query($query,'','insert')){
		 	$this->error->add('оператор INSERT: '.mysqli_error($link).'<Br />запрос: '.$query, $classWhichCalled);
			return false;
		}
		return true;
	}
	
//простая вставка в insert
	//fields - строка. Столбцы через запятую - `name`, `price`, `cost`
	//values - строка. Строки через запятую, в скобках! - 
		//Сложная вставка - (баннеры, 40, 34), (баннеры, 40, 34), (баннеры, 40, 34)
	public function insertSimple($table, $fields, $values, $classWhichCalled=''){
		$link = $this->link;
		$query = "INSERT INTO {$table} ({$fields}) VALUES {$values}";
		$this->lastQuery = $query;

		if(!$this->query($query,'','insert')){
		 	$this->error->add('оператор INSERT: '.mysqli_error($link).'<Br />запрос: '.$query, $classWhichCalled);
			return false;
		}
		return true;
	}

//преобразование ассоц.массива в массив строк - для insert
	private function arrayToArrForInsert($array){
		$result = array('fields'=>'', 'values'=>'');
		$fieldNeed = true;
		$needBrekes = false;
		foreach ($array as $id=>$row){
			if(is_array($row)){
				$result['values'] .= '(';
				foreach ($row as $key=>$value){
					if($fieldNeed){
						$key = addslashes($key);
						$result['fields'] .= "`{$key}`,";
					}
					//тут надо экранировать кавычки - исправлено 27.04.11
					$value = addslashes($value);
					$result['values'] .= "'{$value}',";
				}
				//ошибка исправлена - 27.04.11
				//было -2, стало -1 ->уменьшение строки запроса для insert было слишком большим!
				//Это - для больших Insert'ов (в несколько циклов)
				$result['values'] = $result['values']!==''?substr($result['values'], 0, -1):$result['values'];
				$result['values'] .= '),';

				if($result['values'] === '(),'){
					return false;
				}
			} 
			else {
				$id = addslashes($id);
				$row = addslashes($row);
				$result['fields'] .= "`{$id}`,";
				$result['values'] .= "'{$row}',";
				$needBrekes = true;
			}
			$fieldNeed = false;
		}
		if($result['fields'] === '' || $result['values'] === ''){
			return false;
		}
		$result['fields'] = substr($result['fields'], 0, -1);
		$result['values'] = substr($result['values'], 0, -1);
		if($needBrekes){
			$result['values'] = "({$result['values']})";
		}
		return $result;
	}

//получение последней созданной ID
	public function id(){
		$link = $this->link;
		$last_id = mysqli_insert_id($link);
		return $last_id;
	}
	
//ОПЕРАТОР UPDATE
//обновление данных в базе
	public function update($table, $data, $where, $classWhichCalled='' ){
		$link = $this->link;
		$data = $this->arrayToStrForUpdate($data);
		if($data === ''){
			return false;
		}
		$query = "UPDATE {$table} SET {$data} WHERE {$where}";
		$this->lastQuery = $query;
		
		if ( ! $this->query($query) ){
			$this->error->add('оператор UPDATE: '.mysqli_error($link).', query: '.$query, $classWhichCalled);
			return false;
		}
		return true;
	}

//функция для update
	private function arrayToStrForUpdate($array, $exc = array()){
		$result = '';
		foreach ($array as $key=>$value){
			if(is_array($value) || is_array($key)){
				return false;
			}
			if(!in_array($key, $exc)){
				$result .= "`{$key}`='{$value}', ";
			}
		}
		$result = $result!==''?substr($result, 0, -2):$result;
		return $result;
	}
}
?>