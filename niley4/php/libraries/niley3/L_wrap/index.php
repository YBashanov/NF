<?php if ( ! defined('andromed')) exit(''); 
/*
класс, обертывающих функции DB (2013-05-28)
ВЫЗЫВАЕТСЯ В ЭТОМ ЖЕ ФАЙЛЕ

все включаемые параметры добавляются внизу, после вызова
*/
class L_wrap{
	private $globals = array();
	public $errorClass = array();		//класс ошибок
	public $dbClass = array();		//класс ошибок
	
	private static $thisObject = null;
	public static function init(){
		if ( self::$thisObject == null ){
			self::$thisObject = new L_wrap();
			return self::$thisObject;
		}
		else {
			echo "Объект L_wrap уже создан ранее!";
			exit();
		}
	}
	
	public function setGlobal ($array) {
		$this->globals = $array;
	}
	public function setError ($errorClass){
		$this->errorClass = $errorClass;
	}
	public function setDb ($dbClass){
		$this->dbClass = $dbClass;
	}
	
	//обертка функции ошибок БД (класс Error)
	public function add($code, $message, $thisFile=""){
		if ($this->globals['dbTrue']){
			//if ($this->errorClass->add($code, "0", $message, $thisFile)) return true;
			if ($this->errorClass->add($code, $message, $thisFile)) return true;
			else return false;
		}
		else return false;
	}
	public function write(){
		if ($this->globals['dbTrue']){
			if ($this->errorClass->write($this->dbClass)) return true;
			else return false;
		}
		else return false;
	}
	
	//проверочная (для отладки)
	public function show() {
		echo "<pre>";
		//var_dump($this->errorClass);
		echo "</pre>";
	}
}
$wrap = L_wrap::init();
$wrap->setGlobal($global);
$wrap->setError($error);
$wrap->setDb($db);

//$wrap->show();

$global['wrap'] = $wrap;
?>