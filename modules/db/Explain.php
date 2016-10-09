<?php 

class Explain{
    public $error;
    private $link;
    
    
    public function __construct($link, $error){
        $this->link = $link;
        $this->error = $error;
    }
    
    
    public function query ( $query, $link='', $method='' ) {
		if ( $link == '' ) $link = $this->link;

		if ( $this->view_speed ) {
			$start_time = $this->getmicrotime();
		}
		
		$result = @mysql_query($query, $link); //resource, true, false
		$error = @mysql_error();

		if ( $this->view_query ) {
			echo $query;
			echo '<br />';
		}
		
		if ( $this->view_speed ) {
			$end_time = $this->getmicrotime();
			$queries_time = ($end_time - $start_time);
			$this->all_time_query += $queries_time;
			$this->error->add(1, "<b>{$method}</b><br />time={$queries_time}<br />t_all={$this->all_time_query}<br />query={$query}", 'database');
		}
		return $result;
	}

//вспомагательная функция для определения microtime
	private function getmicrotime(){ 
		list($usec, $sec) = explode(" ", microtime()); 
		return ((float) $usec + (float) $sec); 
	}
    
    
    //ОПЕРАТОР SELECT
	public function select($table, $where = 'true', $what='*', $key = 'id', $classWhichCalled='', $mysql_num = false) {
		$link = $this->link;
		$query = "EXPLAIN SELECT {$what} FROM {$table} WHERE {$where}";
		$this->lastQuery = $query;
		
		$res = $this->query ( $query, $link, 'select' );
		if ( $res ) {
			if ($mysql_num == false) {
				while ( $row = @mysql_fetch_assoc($res) ) {
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
				while ( $row = @mysql_fetch_assoc($res) ) {
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
		else $this->error->add(1, 'оператор SELECT: '.mysql_error().'<Br />запрос: '.$query, $classWhichCalled);
		return $result;
	}

//выбрать только одну строку - 
	//- первое в списке совпадений
	//- ИЛИ уникальную строку в зависимости от $where
	public function select_line($table, $where = 'true', $what='*', $classWhichCalled=''){
		$link = $this->link;
		$query = "EXPLAIN SELECT {$what} FROM {$table} WHERE {$where}";
		$this->lastQuery = $query;
		
		$res = $this->query ( $query, $link, 'select_line' ) or (
			$this->error->add(1, 'select_line: '.mysql_error().'<Br />запрос: '.$query, $classWhichCalled)
		);
		//if ($res)...
		return @mysql_fetch_assoc($res);
	}

//выбрать только один столбец, 
	//возвращает числовой массив со значениями всего столбца (удобно для поиска конкретной информации в 
	//..известном столбце
	//$q_array - название столбца
	public function select_column($table, $where = 'true', $what='*', $q_array='id', $classWhichCalled=''){
		$link = $this->link;
		$query = "EXPLAIN SELECT {$what} FROM {$table} WHERE {$where}";
		$this->lastQuery = $query;
		
		$res = $this->query ( $query, $link, 'select_column' ) or (
				$this->error->add(1, 'select_column: '.mysql_error().'<Br />запрос: '.$query, $classWhichCalled)
			);
		
		if ( is_array($q_array) ) {
			$j=0;//номер строки в ТБД
			while ( $row = mysql_fetch_assoc($res) ) {
				for ( $i=0; $i<count($q_array); $i++ ) {
					$ret_arr[$q_array[$i]][$j] = $row[$q_array[$i]];
				}
				$j++;
			}
		}
		else {
			$j=0;//номер строки в ТБД
			while ( $row = mysql_fetch_assoc($res) ) {
					$ret_arr[$j] = $row[$q_array];
				$j++;
			}
		}
		return $ret_arr;
	}

//ОПЕРАТОР INSERT
	public function insert($table, $data, $classWhichCalled=''){
		$link = $this->link;
		$data = $this->arrayToArrForInsert($data);
		$query = "EXPLAIN INSERT INTO {$table} ({$data['fields']}) VALUES {$data['values']}";
		$this->lastQuery = $query;
		
		if(!$this->query($query,'','insert')){
		 	$this->error->add(1, 'оператор INSERT: '.mysql_error().'<Br />запрос: '.$query, $classWhichCalled);
			return false;
		}
		return true;
	}
	
//простая вставка в insert
	public function insertSimple($table, $fields, $values, $classWhichCalled=''){
		$link = $this->link;
		$query = "EXPLAIN INSERT INTO {$table} ({$fields}) VALUES {$values}";
		$this->lastQuery = $query;
		
		if(!$this->query($query,'','insert')){
		 	$this->error->add(1, 'оператор INSERT: '.mysql_error().'<Br />запрос: '.$query, $classWhichCalled);
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
		$last_id = mysql_insert_id($link);
		return $last_id;
	}
	
//ОПЕРАТОР UPDATE
	public function update($table, $data, $where, $classWhichCalled='' ){
		$link = $this->link;
		$data = $this->arrayToStrForUpdate($data);
		if($data === ''){
			return false;
		}
		$query = "EXPLAIN UPDATE {$table} SET {$data} WHERE {$where}";
		$this->lastQuery = $query;
		
		if ( ! $this->query($query) ){
			$this->error->add(1, 'оператор UPDATE: '.mysql_error().', query: '.$query, $classWhichCalled);
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