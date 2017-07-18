<?php if ( ! defined('andromed')) exit('');

//выбрать из массива значение случайным образом
//quantity - сколько значений выбирать из массива. Если > 1, то возвращает числовой массив значений
function random ($array, $quantity = 1) {
	$count = count($array);

	if ($count > 0) {
		//есть варианты
		if ($count == 1) {
			return array_shift($array);
		}
		elseif ($count > 1) {
			$rand = rand(0, $count-1);
			$i=0;
			foreach($array as $val) {
				if ($i == $rand) {
					return $val;
				}
				$i++;
			}
		}
	}
	else return false;
}

?>