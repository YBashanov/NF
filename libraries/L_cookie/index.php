<?php if ( ! defined('andromed')) exit('');
//L_cookie
/*
создание переменных сессий - специальных триггеров
	на одноразовое включение того или иного кода
	$_SESSION['trigger'] = array();
	$session = array(); - новая версия
	
Применяется 
- при проверке создания таблиц баз данных,

5.06.12 - добавлена строка в функции создания кукис
2014-09-24 - изменена set_cookie, при timeLive=0 становится сессионной
*/

class L_cookie{
	
	private static $timeLive = 0;//время жизни кукис (если=0, это будут сессионные кукис)
	private $path = '/';
	private $domain = null;
	
	private static $error;
	private static $thisObject = null;
	public static function init($error){
		if ( !isset(self::$thisObject) ){
			self::$thisObject = new L_cookie();
			self::$error = $error;
			return self::$thisObject;
		}
		else {
			echo "L_cookie: Объект L_cookie уже создан ранее!";
			self::$error->add(2, 'Error: Object is exists!', 'L_cookie');
			exit();
		}
	}

//смена значения конкретной триггерной сессионной переменной 
	//если такой переменной еще нет - создаем и присваиваем значение
	//$timeLive - если не определена, устанавливается до закрытия браузера
	public function set_cookie($name, $var, $timeLive = false, $rewrite = false){
		if ($rewrite == false) {
			if ($this->get_cookie($name)){
				return false;
			}
			else {
				$this->_set_cookie($name, $var, $timeLive);
				return true;
			}
		}
		else {
			$this->_set_cookie($name, $var, $timeLive);
			return true;
		}
	}
	
	//вспомогательный метод
	private function _set_cookie($name, $var, $timeLive){
		if ($timeLive == false) {
			@setcookie($name, $var, 0, $this->path, $this->domain);
		}
		else {
			$timeLive = time() + $timeLive;
			@setcookie($name, $var, $timeLive, $this->path, $this->domain);
		}
	}

//возвращает значение конкретной триггерной сессионной переменной
	public function get_cookie($name = false){
		if ($name == false) {
			return $_COOKIE;
		}
		else {
			if ( ! isset ($_COOKIE[$name]) ) {
				return null;
			}
			else return $_COOKIE[$name];
		}
	}
	
//удаляет существующую кукис
	public function delete_cookie($name){
		if ( isset ($_COOKIE[$name]) ) {
			$time = time() - 1036800;
			@setcookie($name, '', $time, $this->path, $this->domain);
			return true;
		}
		else return false;
	}
}
//L_cookie
?>