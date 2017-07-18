<?php if ( ! defined('andromed')) exit('');
//L_array
/*
работа в массивами (28.05.12)
randAssign() - создание массива, заполненного случайными значениями из другого массива

2013-06-05 - MixedToOne()
*/

class L_array{
		
	private static $error;
	private static $thisObject = null;
	public static function init($error){
		if ( !isset(self::$thisObject) ){
			self::$thisObject = new L_array();
			self::$error = $error;
			return self::$thisObject;
		}
		else {
			echo "L_array: Объект L_array уже создан ранее!";
			exit();
		}
	}

//создание массива, заполненного случайными значениями из другого массива
	//examplesArray - массив, из которого будем брать значения (числовой массив)
	//examples - сколько значений вытащить из массива examplesArray
	//doubleArr_key - ключи у элементов в результирующем итоговом массиве (если = null, массив на выходе одномерный)
	public function randAssign ($examplesArray, $examples, $doubleArr_key = null) {

		//чтобы лишний раз не нагружать сервер - берутся все значения массива examplesArray
		if ( count($examplesArray) <= $examples ) {
			$result = $examplesArray;
		}
		//запускаем основную выборку случайных значений
		else {
			$now = array();
			for ($i = 0; $i < $examples; $i++) {

				//пока true не будет = true, то есть не будет повторяющихся значений
				$true = false;
				while (! $true) {
					$true = true;//сразу даем возможность выйти из цикла while

					//присвоим случайное значение key нашему i-му элементу
					$now[$i] = rand(0, count($examplesArray)-1);
					
					//теперь проверим, есть ли такое знечение ключа в предыдущих элементах этого массива
					for ($j=0; $j < count($now)-1; $j++) {
						//если есть хотя бы одно совпадение 
						// (оно по сути будет единственным, т.к. совпадения мы не пропускаем)
						if ($now[$j] == $now[$i]) {
								//не выпускаем из while
							$true = false;
							break;
						}
					}
				}
			}
			
			//заменим все ключи в массиве now на реальные значения из массива examplesArray
			if ($doubleArr_key == null) {
				for ($k=0; $k<count($now); $k++) {
					$result[$k] = $examplesArray[$now[$k]];
				}
			}
			else {
				$result = array();
				for ($k=0; $k<count($now); $k++) {
					$result[$k] = array(
						$doubleArr_key => $examplesArray[$now[$k]]
					);
				}
				
			}
		}
		
		return $result;
	}
	
	//делает из смешанного массива одномерный 
	//смешанный массив типа
	// pref - индикатор элементов 1го уровня (чтобы выделить их из числа бывших 2го уровня)
	/*
		0=>array(
			"sub"=>array(
				"anons"=>"Анонс главных событий",
				"obyavleniya"=>"Объявления",
				"lenta_novostey"=>"Лента новостей",
			),
			""=>"Главная",
		),
		1=>array(
			"sub"=>array(
				"otraslevie_sobitiya"=>"Отраслевые события",
				"gosorgani"=>"Госорганы, общественные организации",
			),
			"zhizhn_otrasli"=>"Жизнь отрасли",
		),
	*/
	//преобразуется в обычный одномерный массив
	public function MixedToOne($array, $pref = ""){
		$a_new = array();
		
		for($i=0; $i<count($array); $i++){
			$first = array();
			$second = array();
			
			foreach($array[$i] as $key=>$val) {
				if ($key == "sub") {
					foreach ($val as $key2=>$val2) {
						$a_paths = explode("|", $key2);
						$pathMenu = $a_paths[0];
						$second[$pathMenu] = $val2;
					}
				}
				else {
					$a_paths = explode("|", $key);
					$pathMenu = $a_paths[0];
					if ($pathMenu == "") $pathMenu = "index";
					$first[$pathMenu] = $pref.$val;
				}
			}
			
			$a_new = array_merge($a_new, $first);
			$a_new = array_merge($a_new, $second);	
		}
		return $a_new;
	}
}
//L_array
?>