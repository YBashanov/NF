<?php if ( ! defined('andromed')) exit('');

/*
набор пользовательского функионала вне классов

moneyFormat (2014-10-18) - разделяет пробелами триады чисел: 1200 -> 1 200
*/



//PRIVATE - является составляющей частью функции moneyFormat
//преобразование целой части - разделить длинное число на триады
function getTriades ($integ) {
	$integers = intval($integ / 1000);
	$fractal = $integ % 1000; //задняя часть

	//добавить нули спереди fractal
	$fractal = $fractal . "";
	$length = strlen($fractal);
	if ($length < 3 && $integers > 0) {
		$fractal = "000" . $fractal;
		$fractal = substr($fractal, $length, 3);
	}

	//проверить, передняя часть какой длины
	if ($integers >= 1000) {
		$integers = getTriades($integers);
	}

	if ($integers == 0) return $fractal;
	else return $integers . " " . $fractal;
} 
// -------------------------------------------------------------- 


//получить значение сортировки в зависимости от значения переменной
function getAD($num){
	if ($num == 1) return "ASC";
	else return "DESC";
}


//numstring - число или строка (желательно, чтобы было число, иначе обрежется дробная часть)
//coinsTrue - использовать ли копейки. По умолчанию - false, не указываем знаки
	//coinsTrue - указать TRUE или количество знаков после запятой (обычно указываем 2)
function moneyFormat($numstring, $coinsTrue=false, $error=false){
	if (gettype($numstring) == "string") {
		$numstring = preg_replace('/\s/', '', $numstring);
		settype($numstring, float);
	}

	//после преобразования нам - сюда
	if (gettype($numstring) == "integer" || gettype($numstring) == "float" || gettype($numstring) == "double") {

		$result = "";
		$floats = $numstring;
		$integ = intval($floats);

		
		//у числа нет дробной части
		if ($floats == $integ) {
			$result = getTriades($integ);
			
			if ($coinsTrue) {
				if ($coinsTrue === true) $coinsTrue = 2;
				
				$fractal = "";
				for($i=0; $i<$coinsTrue; $i++) {
					$fractal .= "0";
				}
				$fractal = "." . $fractal;
				$result .= $fractal;
			}
		}
		//у числа есть дробная часть
		else {
			$fractal = "";

			//расчет дробной части
			if ($coinsTrue) {
				if ($coinsTrue === true) $coinsTrue = 2;

				$floats = $floats . "";
				$a_floats = explode(".", $floats);
				$fractal = substr($a_floats[1], 0, $coinsTrue);
				if (strlen($fractal) < $coinsTrue){
					for($i=0; $i<$coinsTrue-strlen($fractal); $i++) {
						$fractal .= "0";
					}
				}
				
				$fractal = "." . $fractal;
				
				//если поле fractal не полное, заполнить остаток нулями
			}
			
			$result = getTriades($integ);
			$result = $result . $fractal;
		}
		
		return $result;
	}
	else {
		if ($error) {
			$error->add(0, "moneyFormat: 1й аргумент - не строка, не число", "universal.php");
		}
		return false;
	}
}
/*
обертка для стандартной функции include, только при отсутствии файла сразу выдает ошибку, не нарушая выполнение программы
*/
function includes($path, $wrap=false) {
    if (@file_exists($path)) {
		include $path;
        return true;
	}
	else {
        if ($wrap) {
            $wrap->add(2, "Нет такого файла: {$path}", "universal/includes");
        }
        else {
            echo "universal/includes: Нет такого файла: {$path}";
        }
        return false;
    }
}
?>