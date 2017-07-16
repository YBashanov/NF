<?php if ( ! defined('andromed')) exit('');

/*
Класс, расширяющий математические возможности
12.03.13

*/

class L_math{
	
//пример рабочего алфавита для decToAlfavit
	public $alfavit = "0ABCDEFGHIGKLMNOPQRSTUVWXYZ";

	private static $thisObject = null;
	public static function init(){
		if ( self::$thisObject == null ){
			self::$thisObject = new L_math();
			return self::$thisObject;
		}
		else {
			echo "L_math: Объект L_math уже создан ранее!";
			exit();
		}
	}
	
//перевод из десятичной системы счисления в систему счисления, представленную алфавитом
//условие: первый символ алфавита является "своим" нулем.
//num - символ, который мы хотим преобразовать
	public function decToAlfavit ($num, $alfavit){
		if (is_int ($num)) {
			$base = strlen($alfavit);	//основание

			$positions = 0;	//количество разрядов (для информации)
			$string = "";	//собираем новые числа
			$varNum = $num;
			$end = false;
			while ( $end == false ) {
				if ($varNum > $base) {
					$result = floor($varNum/$base);
					$balance = $varNum%$base;//остаток есть первое число
					$varNum = $result;
				}
				elseif ($varNum == $base) {
					$balance = 0;
					$varNum = 1;
				}
				elseif ($varNum < $base) {
					$balance = $varNum;
					$varNum = 0;
					$end = true;
				}
				$symbol = substr($alfavit, $balance, 1);
				$string = $symbol . $string;
				$positions++;
			}
			return $string;
		}
		return false;
	}
}
?>