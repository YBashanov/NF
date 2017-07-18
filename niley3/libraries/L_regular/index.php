<?php if ( ! defined('andromed')) exit('');

/*
Класс, расширяющий регулярные выражения
1.12.11 - доработан. Возвращает string вместо true
5.06.12 - mail (для удобства)
5.06.12 - добавлена проверка string на NULL (уменьшает пропускающую способность класса)
12.02.13 - экранирование символов 

num		 		- только цифры (для одиночных слов)
eng_num 		- англ буквы, цифры (для одиночных слов)
rus_num 		- рус буквы, цифры (для одиночных слов)
eng_num_sol		- англ буквы, цифры, мин.разрешенных символов (для логинов, паролей.. )
rus_num_sol		- рус буквы, цифры, мин.разрешенных символов (для русских имен с пробелами)
sol_symbols_min	- англ буквы, рус буквы, цифры, мин.разрешенных символов
str				- упрощенный вызов sol_symbols_min (31.10.12)
sol_symbols_ext - англ буквы, рус буквы, цифры, все разрешенные символы (для общения)
xstr			- упрощенный вызов sol_symbols_ext (7.11.12)
reg_mail, mail	- e-mail, правильность написания
ext				- только экранирование (13.02.2013) а также отмена некторых строк
ext_absolute    - абсолютное экранирование !!! без проверки на опасные теги
tinymce			- отмена тех же строк, что и ext, за исключением style (2.10.2013)
url             - для адресной строки
url_download    - 2015-11-08 - для адресной строки, но без точек

10.09.12 - floats ()

апрель 2013 - изменения благодаря Кад - проверка на важные слова (link, script...)
2013-05-28 - переход на версию 2.41

2013-06-03 - errorIn (ошибки внутри класса), show()
2013-06-20 - доработки
2014-01-24 - функция mail - везде есть точки
2014-10-02 - функция set, с возможностью просматривать какие именно ошибки фильтрации были допущены!
*/

class L_regular{

	private $view_error = true;//разрешение/запрещение генерации ошибок-отчетов классом (true/false)
	private static $thisObject = null;
	private static $error;
	public $calls = 0;//количество вызовов данной функции. Можно определить, на каком вызове закралась ошибка.
	public $errorIn = "Ошибки: "; //какие ошибки возникают внутри класса - записываются сюда
	public static function init($error){
		if ( self::$thisObject == null ){
			self::$thisObject = new L_regular();
			self::$error = $error;
			return self::$thisObject;
		}
		else {
			echo "L_regular: Объект L_regular уже создан ранее!";
			self::$error->add(2, "Ошибка. Один объект уже существует", 'L_regular');
			exit();
		}
	}
	
	//проверка (для одиночных слов)
	//только цифры
	public function num($string){
		$this->calls++;

		if ($string === NULL)
			return false;

		preg_match("/[0-9]+/", $string, $preg_array);
		if ( $string == @$preg_array[0] && $string !== '' && $string !== false) {
			$string = addslashes (trim ($string));
			return $string;
		}
		else {
			$this->errorIn .= ", num - ".$string." -> ".$preg_array[0]. "|";
			if ($this->view_error == true){
				self::$error->add(2, "num: string={$string}, array_0={$preg_array[0]}. Вызов: {$this->calls}", get_class($this));
			}
			return false;
		}
	}
	
	public function floats ($string){
		$this->calls++;
		if ($string === NULL)
			return false;
		
		preg_match("/[0-9.,-]+/", $string, $preg_array);
		if ( $string == @$preg_array[0] && $string !== '' && $string !== false) {
			$string = addslashes (trim ($string));
			return $string;
		}
		else {
			$this->errorIn .= ", floats - ".$string." -> ".$preg_array[0]. "|";
			if ($this->view_error == true){
				self::$error->add(2, "floats: string={$string}, array_0={$preg_array[0]}. Вызов: {$this->calls}", get_class($this));
			}
			return false;
		}
	}
	
	//для совместимости - с 1.1, 30.08.11
	//вызов num()
	public function numerals($string){ return $this->num($string);}
	
	
	
	//проверка (для одиночных слов)
	//английские буквы и цифры
	public function eng_num($string){
		$this->calls++;
		if ($string === NULL)
			return false;
	
		preg_match("/[A-Za-z0-9]+/", $string, $preg_array);
		if ( $string == @$preg_array[0] && $string !== false) {
			$string = addslashes (trim ($string));
			return $string;
		}
		else {
			$this->errorIn .= ", eng_num - ".$string." -> ".$preg_array[0]. "|";
			if ($this->view_error == true){
				self::$error->add(2, "eng_num: string={$string}, array_0={$preg_array[0]}. Вызов: {$this->calls}", get_class($this));
			}
			return false;
		}
	}
	
	//проверка (для одиночных слов)
	//русские буквы и цифры
	public function rus_num($string){
		$this->calls++;
		if ($string === NULL)
			return false;
			
		preg_match("/[А-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя0-9]+/", $string, $preg_array);
		if ( $string == @$preg_array[0] && $string !== false) {
			$string = addslashes (trim ($string));
			return $string;
		}
		else {
			$this->errorIn .= ", rus_num - ".$string." -> ".$preg_array[0]. "|";
			if ($this->view_error == true){
				self::$error->add(2, "rus_num: string={$string}, array_0={$preg_array[0]}. Вызов: {$this->calls}", get_class($this));
			}
			return false;
		}
	}

	//проверка (для логинов, паролей.. при регистрации)
	//английские буквы, цифры, разрешенные символы (минимальный набор)
	//\s-_. 
	public function eng_num_sol($string){
		$this->calls++;
		if ($string === NULL)
			return false;
			
		preg_match("/[A-Za-z0-9\s-_\.\/]+/", $string, $preg_array);
		if ( $string == @$preg_array[0] && $string !== false) {
			$string = addslashes (trim ($string));
			return $string;
		}
		else {
			$this->errorIn .= ", eng_num_sol - ".$string." -> ".$preg_array[0]. "|";
			if ($this->view_error == true){
				self::$error->add(2, "eng_num_sol: string={$string}, array_0={$preg_array[0]}. Вызов: {$this->calls}", get_class($this));
			}
			return false;
		}
	}
	
	//проверка (для русских имен в несколько слов)
	//русские буквы и цифры, разрешенные символы (минимальный набор)
	//\s-_. 
	public function rus_num_sol($string){
		$this->calls++;
		if ($string === NULL)
			return false;
			
		preg_match("/[А-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя0-9\s-_.]+/", $string, $preg_array);
		if ( $string == @$preg_array[0] && $string !== false) {
			$string = addslashes (trim ($string));
			return $string;
		}
		else {
			$this->errorIn .= ", rus_num_sol - ".$string." -> ".$preg_array[0]. "|";
			if ($this->view_error == true){
				self::$error->add(2, "rus_num_sol: string={$string}, array_0={$preg_array[0]}. Вызов: {$this->calls}", get_class($this));
			}
			return false;
		}
	}
	
	//проверка (--)
	//английские буквы, русские буквы, цифры, разрешенные символы (минимальный набор)
	//\s-_.
	public function sol_symbols_min($string){
		$this->calls++;
		if ($string === NULL)
			return false;
			
		preg_match("/[A-Za-zА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя0-9\s-_.,]+/", $string, $preg_array);
		if ( $string == @$preg_array[0] && $string !== false) {
			$string = addslashes (trim ($string));
			return $string;
		}
		else {
			$this->errorIn .= ", sol_symbols_min - ".$string." -> ".$preg_array[0]. "|";
			if ($this->view_error == true){
				self::$error->add(2, "sol_symbols_min: string={$string}, array_0={$preg_array[0]}. Вызов: {$this->calls}", get_class($this));
			}
			return false;
		}
	}
	
	public function str ($string) {
		return $this->sol_symbols_min($string);
	}
	
	//проверка (для общения - расширенный набор)
	//английские буквы, русские буквы, цифры, разрешенные символы (расширенный набор)
	//\s-_.,:;!?() 
	public function sol_symbols_ext($string){
		$this->calls++;
		if ($string === NULL)
			return false;
			
		preg_match("/[A-Za-zА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя0-9\s-_.,:;!?()\[\]\/*+=«»&#№@|\"]+/", $string, $preg_array);
		if ( $string == @$preg_array[0] && $string !== false) {
			if (
				strpos($string, "style") === false && 
				strpos($string, "script") === false &&
				strpos($string, "link") === false &&
				strpos($string, "meta") === false
			) {
				$string = addslashes (trim ($string));
				return $string;
			}
			else {
				if ($this->view_error == true){
					self::$error->add(2, "в строке есть данные фразы: style, script, link, meta. Вызов: {$this->calls}", get_class($this));
				}
				return false;
			}
		}
		else {
			$this->errorIn .= ", sol_symbols_ext - ".$string." -> ".$preg_array[0]. "|";
			if ($this->view_error == true){
				self::$error->add(2, "sol_symbols_ext: string={$string}, array_0={$preg_array[0]}. Вызов: {$this->calls}", get_class($this));
			}
			return false;
		}
	}
	
	public function xstr ($string) {
		return $this->sol_symbols_ext($string);
	}
	
	//проверка (e-mail)
	public function mail($string){
		$this->calls++;
		if ($string === NULL)
			return false;
			
		//preg_match("/([A-Za-z0-9_\-\.]+)([@])([A-Za-z0-9\-\.]+)\.([A-Za-z]{1,6})/", $string, $preg_array);
		preg_match("/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{1,6})+$/", $string, $preg_array);
		if ( $string == @$preg_array[0] && $string !== '' && $string !== 0 && $string !== false) {
			$string = addslashes (trim ($string));
			return $string;
		}
		else {
			$this->errorIn .= ", sol_symbols_ext - ".$string." -> ".$preg_array[0]. "|";
			if ($this->view_error == true){
				self::$error->add(2, "mail: string={$string}, array_0={$preg_array[0]}. Вызов: {$this->calls}", get_class($this));
			}
			return false;
		}
	}
	
	//без проверки - ТОЛЬКО ЭКРАНИРОВАНИЕ
	//обновлена 2013-10-02
	public function ext($string){
		$this->calls++;
		if ($string === NULL)
			return false;
		
		if (
			strpos($string, "style") === false && 
			strpos($string, "script") === false &&
			strpos($string, "link") === false &&
			strpos($string, "meta") === false
		) {
			$string = addslashes (trim ($string));
			return $string;
		}
		else {
			if ($this->view_error == true){
				self::$error->add(2, "ext: в строке есть данные фразы: style, script, link, meta. Вызов: {$this->calls}", get_class($this));
			}
			return false;
		}
	}
	
	//без проверки - АБСОЛЮТНОЕ ЭКРАНИРОВАНИЕ
	//даже нет проверки на опасные теги !
	//обновлена 2014-05-21
	public function ext_absolute($string){
		$this->calls++;
		if ($string === NULL)
			return false;
		
		$string = addslashes (trim ($string));
		return $string;
	}
	
	//без проверки - ТОЛЬКО ЭКРАНИРОВАНИЕ
	//обновлена 2013-10-02
	public function tinymce($string){
		$this->calls++;
		if ($string === NULL)
			return false;
			
		if (
			strpos($string, "script") === false &&
			strpos($string, "meta") === false
		) {
			$string = addslashes (trim ($string));
			return $string;
		}
		else {
			if ($this->view_error == true){
				self::$error->add(2, "tinymce: в строке есть данные фразы: script, link, meta. Вызов: {$this->calls}", get_class($this));
			}
			return false;
		}
	}
	
	
	//для адресной строки
	public function url($string){
		$this->calls++;
		if ($string === NULL)
			return false;
			
		preg_match("/[A-Za-z0-9\s-_.?\/:=&#\"]+/", $string, $preg_array);
		if ( $string == @$preg_array[0] && $string !== false) {
			if (
				strpos($string, "style") === false && 
				strpos($string, "script") === false &&
				strpos($string, "link") === false &&
				strpos($string, "meta") === false
			) {
				$string = addslashes (trim ($string));
				return $string;
			}
			else {
				if ($this->view_error == true){
					self::$error->add(2, "в строке есть данные фразы: style, script, link, meta. Вызов: {$this->calls}", get_class($this));
				}
				return false;
			}
		}
		else {
			$this->errorIn .= ", url - ".$string." -> ".$preg_array[0]. "|";
			if ($this->view_error == true){
				self::$error->add(2, "url: string={$string}, array_0={$preg_array[0]}. Вызов: {$this->calls}", get_class($this));
			}
			return false;
		}
	}
    
    
    //для адресной строки - только для скачивания (без точек)
	public function url_download($string){
		$this->calls++;
		if ($string === NULL) {
			return false;
        }

		preg_match("/[a-z0-9\/]+/", $string, $preg_array);
		if ($string == @$preg_array[0] && $string !== false){
			$string = addslashes (trim ($string));
			return $string;
		}
		else {
			$this->errorIn .= ", url - ".$string." -> ".$preg_array[0]. "|";
			if ($this->view_error == true){
				self::$error->add(2, "url_download: string={$string}, array_0={$preg_array[0]}. Вызов: {$this->calls}", get_class($this));
			}
			return false;
		}
	}
	
	
	//автоматически устанавливает проверку на каждый элемент массива POST.
	//array 		- массив элементов (может быть POST)
	//a_regName 	- массив строк-названий пользовательских reg-функций (строки)
	private $seterror = array();
	private $seterrorKeys = array();
	private $setresult = array();
	public function set($array, $a_regName = array()){
		if ($array) {
			$sendTrue = true;
			$arrayThisText = "";
			$error = array();
			$errorKeys = "";
			$result = array();

			$i=0;
			foreach ($array as $key=>$val){
				$arrayThisText = "";
				if (! $a_regName[$i]) $a_regName[$i] = "ext";
				
				
				if ($a_regName[$i] == "num"){
					$arrayThisText = $this->num($val);
				}
				elseif ($a_regName[$i] == "floats"){
					$arrayThisText = $this->floats($val);
				}
				elseif ($a_regName[$i] == "eng_num"){
					$arrayThisText = $this->eng_num($val);
				}
				elseif ($a_regName[$i] == "rus_num"){
					$arrayThisText = $this->rus_num($val);
				}
				elseif ($a_regName[$i] == "eng_num_sol"){
					$arrayThisText = $this->eng_num_sol($val);
				}
				elseif ($a_regName[$i] == "rus_num_sol"){
					$arrayThisText = $this->rus_num_sol($val);
				}
				elseif ($a_regName[$i] == "sol_symbols_min"){
					if ($val) $arrayThisText = $this->sol_symbols_min($val);
					else $arrayThisText = "";
				}
				elseif ($a_regName[$i] == "sol_symbols_ext"){
					if ($val) $arrayThisText = $this->sol_symbols_ext($val);
					else $arrayThisText = "";
				}
				elseif ($a_regName[$i] == "mail"){
					$arrayThisText = $this->mail($val);
				}
				elseif ($a_regName[$i] == "ext_absolute"){
					if ($val) $arrayThisText = $this->ext_absolute($val);
					else $arrayThisText = "";
				}
				elseif ($a_regName[$i] == "tinymce"){
					if ($val) $arrayThisText = $this->tinymce($val);
					else $arrayThisText = "";
				}
				elseif ($a_regName[$i] == "url"){
					if ($val) $arrayThisText = $this->url($val);
					else $arrayThisText = "";
				}
				//ext - без проверки, только экранирование
				else {
					if ($val) $arrayThisText = $this->ext($val);
					else $arrayThisText = "";
				}
				
				
				if ($arrayThisText === false) {
					$error[$i] = array(
						"key"=>$key,
						"regfunction"=>$a_regName[$i],
						"val"=>$val
					);
					$errorKeys .= $key . ", ";
					$sendTrue = false;
				}
				
				$result[$key] = $arrayThisText;
				$i++;
			}
			$errorKeys = substr($errorKeys, 0, strlen($errorKeys)-2);
			
			$this->setresult = $result;
			$this->seterror = $error;
			$this->seterrorKeys = $errorKeys;
			return $sendTrue;
		}
		else {
			self::$error->add(2, "Regular.set: не задан массив группы", get_class($this));
			return false;
		}
	}
	//показать ошибки set
	//если keys=true, возвращает только ключи
	public function getError($keys = false){
		if ($keys == false) return $this->seterror;
		else return $this->seterrorKeys;
	}
	//забрать результаты set
	public function getResult(){
		return $this->setresult;
	}
	
	
	
	//посмотреть ошибки
	public function show(){
		var_dump($this->errorIn);
	}
}
?>