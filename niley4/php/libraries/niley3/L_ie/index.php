<?php if ( ! defined('andromed')) exit('');

/*
28.06.13
*/

class L_ie{
	private static $thisObject = null;
	private static $error;
	public static function init($error){
		if ( self::$thisObject == null ){
			self::$thisObject = new L_ie();
			self::$error = $error;
			return self::$thisObject;
		}
		else {
			echo "L_ie: Объект L_ie уже создан ранее!";
			self::$error->add(2, "Ошибка. Один объект уже существует", 'L_ie');
			exit();
		}
	}
	
	public function ieTrue(){
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		if ( strpos($userAgent, "MSIE") ) $ieTrue = true;
		else $ieTrue = false;
		
		return $ieTrue;
	}

}
?>