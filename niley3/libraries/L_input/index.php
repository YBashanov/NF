<?php if ( ! defined('andromed')) exit('');
//L_input
/*
	2013-06-06
*/

class L_input{
		
	private static $error;
	private static $thisObject = null;
	public static function init($error){
		if ( !isset(self::$thisObject) ){
			self::$thisObject = new L_input();
			self::$error = $error;
			return self::$thisObject;
		}
		else {
			echo "L_input: Объект L_input уже создан ранее!";
			exit();
		}
	}
	
	public function checked ($bool){
		if ($bool == true) 
			return "checked='checked'";
		else 
			return "";
	}

}
?>