<?php

/**
Класс, проверяющий переменные с использованием регулярных выражений

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

2016-07-19 - Переименовал класс в Reg. Удален лишний код. Остались только наиболее важные паттерны
    Нет зависимости от класса Error
*/
class Reg{
	private static $thisObject = null;
	public static function init(){
		if ( self::$thisObject == null ){
			self::$thisObject = new Reg();
			return self::$thisObject;
		}
		else {
			return self::$thisObject;
		}
	}

	private $patterns = [
		"num" => "/[0-9]+/",
		"float" => "/[0-9.,]+/",
		"string" => "/[\wА-Яабвгдеёжзийклмнопрстуфхцчшщъыьэюя\-$]+/",
		"login" => "/[\w-\.\/]+/",
		"mail" => "/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{1,6})+$/",
		"url" => "/[\w\s-_.?\/:=&#\"]+/",
		"ext" => ""
	];

	/**
	 * @param $name
	 * @param $pattern
	 * @return bool
	 */
	public function setPattern($name, $pattern){
		if (isset($name) && isset($pattern)){
			$this->patterns[$name] = $pattern;
			return true;
		}
		else return false;
	}

	//автоматически устанавливает проверку на каждый элемент массива POST.
	//array 		- массив элементов (может быть POST)
	//a_regName 	- массив строк-названий reg-функций (строки)
	private $seterror = array();
	private $seterrorKeys = array();
	private $setresult = array();
	public function search($array, $a_regName = array()){
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

				$arrayThisText = $this->_preg_match($this->patterns[$a_regName[$i]], $val);

				if ($arrayThisText === false) {
					$error[$i] = array(
						"key"=>$key,
						"patternName"=>$a_regName[$i],
						"patternVal"=>$this->patterns[$a_regName[$i]],
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
			return false;
		}
	}
	//показать ошибки set
	//если keys=true, возвращает только ключи
	public function getErrors($keys = false){
		if ($keys == false) return $this->seterror;
		else return $this->seterrorKeys;
	}
	//забрать результаты set
	public function getResult(){
		return $this->setresult;
	}




	private function _preg_match($pattern, $string){
		// если это только экранирование (нет паттерна)
		if ($pattern === "") {
			return $this->ext($string);
		}
		else {
			if ($string === NULL)
				return false;

			if ($pattern === NULL)
				return false;

			preg_match($pattern, $string, $preg_array);
			if ($string === @$preg_array[0] && $string !== false) {
				$string = addslashes(trim($string));
				return $string;
			} else {
				return false;
			}
		}
	}

	//без проверки - ТОЛЬКО ЭКРАНИРОВАНИЕ
	//обновлена 2013-10-02
	private function ext($string){
		if ($string) $string = addslashes (trim ($string));
		else $string = "";
		return $string;
	}
}
?>