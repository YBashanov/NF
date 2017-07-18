<?php if ( ! defined('andromed')) exit('');

/*
Класс, расширяющий выражения select
1.12.11
5.02.12 доработано 
*/

class L_select{

	private static $thisObject = null;
	private static $error;
	public static function init($error){
		if ( !isset(self::$thisObject) ){
			self::$thisObject = new L_select();
			self::$error = $error;
			return self::$thisObject;
		}
		else {
			echo "L_select: Объект L_select уже создан ранее!";
			self::$error->add(2, "Ошибка. Один объект уже существует", 'L_select');
			exit();
		}
	}

	
	
//возвращает options из ключей массива (или формирует массив options)
	//array - ассоциативный массив значений (например array(array())
		//либо - просто число (например, 12)
	//post - то, что сейчас выбрано (и передано GET, POST) - атрибут selected=selected
	//asc - порядок следования чисел (asc - нормальный, desc - обратный)
	//assoc=0 - массив числовой (число->value, значение->text)
		//assoc=1 - array раскладывать как простой ассоц массив ([ключ]->value, значение->text)
		//assoc=2 - array раскладывать как двумерный ассоц массив (+ как многомерный массив)
			//используется post_key
	//post_key - для формирования вложений (двумерный ассоц массив)
		//используется как post в предыдущем select для последующей выборки
	//post_val - если указан, массив раскладывается так: [key1->'post_key' => value, key1->'post_val' => text] (с циклом по key1)
	//numReadout - начало отсчета в числовом массиве (с использованием $x) default=1
	//with_which_element - с какого элемента начинается показ массива (отсчет ведется с начала отображения массива)

	public function get_option($array, $post='', $asc='asc', $assoc=0, $post_key='', $post_val='', $numReadout=1, $with_which_element=1){
		
		$num_view = 1; //счетчик, который позволяет разрешать/запрещать отображение определенным номерам

		//проверяем, что содержится в array

		//подготовительные работы - преобразования типа Строка
		//если в строке содержатся только числа, проверяем с помощью регулярности
			//и переводим строку в число
		if ( is_string($array) ){
			preg_match("/[0-9]+/", $array, $preg_array);
			if ( $array == @$preg_array[0] && $array !== '' && $array !== 0) {
				settype($array, integer);
			}
			else {
				self::$error->add(2, 'Переменная является нечисловой строкой!', get_class($this));
				return false;
			}
		}
		$ret = '';
		
		//число	
		if ( is_int($array)){
			//прямой порядок
			if ( $asc == 'asc' || $asc == 'ASC'){
				for ( $i=$numReadout; $i<=($array+$numReadout-1); $i++ ){
					//проверка-разрешение на показ строк массива
					if ( $num_view >= $with_which_element ) {
				
						$ret .= "<option value='{$i}'";
						$ret .= ($i == $post)?"selected='selected'":'';
						$ret .= ">" .$i. "</option>\n";
					}
					$num_view++;
				}
				return $ret;
			}
			//обратный порядок
			elseif ( $asc == 'desc' || $asc == 'DESC' ){
				for ( $i=($array+$numReadout-1); $i>=$numReadout; $i-- ){
					//проверка-разрешение на показ строк массива
					if ( $num_view >= $with_which_element ) {
						
						$ret .= "<option value='{$i}'";
						$ret .= ($i == $post)?"selected='selected'":'';
						$ret .= ">" .$i. "</option>\n";
					}
					$num_view++;
				}
				return $ret;
			}
			else{
				self::$error->add(2, "Недопустимое значение сортировки. Должно быть (asc/desc) ={$asc}", get_class($this));
				return false;
			}
		}
		
		//массив
		elseif ( is_array($array)){
			//выбор нормального или обратного порядка следования элементов
			if ( $asc == 'asc' || $asc == 'ASC'){
				$x=$numReadout;//только для числового массива
				$increment=1;
			}
			elseif ( $asc == 'desc' || $asc == 'DESC' ){
				$array = array_reverse($array);//для всех массивов
				$x=(count($array)+$numReadout-1);//только для числового
				$increment=0;
			}
			else {
				self::$error->add(2, "Недопустимое значение сортировки. Должно быть (asc/desc) ={$asc}", get_class($this));
				return false;
			}
			//числовой массив = число(x)->value, значение->text
			//ассоциативный = число(x)->value, значение->text
			//двумерный = число(x)->value, [ключ]->text
			if ($assoc == 0){
				
				foreach ( $array as $key=>$val ) {
					//проверка-разрешение на показ строк массива
					if ( $num_view >= $with_which_element ) {
					
						//если массив одномерный
						if ( ! is_array($val) ){
							$ret .= "<option value='{$x}'";
							$ret .= ($x == $post)?"selected='selected'":'';
							$ret .= ">" .$val. "</option>\n";
						}
						//если массив двумерный
						else{
							$ret .= "<option value='{$x}'";
							$ret .= ($x == $post)?"selected='selected'":'';
							$ret .= ">" .$key. "</option>\n";
						}
						$x = $this->increment($x,$increment);
					}
					$num_view++;
				}
				return $ret;
			}
			//числовой массив = [ключ-число]->value, значение->text
			//ассоциативный = [ключ]->value, значение->text
			//двумерный = [ключ]->value, [ключ]->text
			elseif ($assoc == 1){
				foreach ( $array as $key=>$val ) {
					//проверка-разрешение на показ строк массива
					if ( $num_view >= $with_which_element ) {
					
						//если массив одномерный
						if ( ! is_array($val) ){
							$ret .= "<option value='{$key}'";
							$ret .= ($key == $post)?"selected='selected'":'';
							$ret .= ">" .$val. "</option>\n";
						}
						//если массив двумерный
						else{
							$ret .= "<option value='{$key}'";
							$ret .= ($key == $post)?"selected='selected'":'';
							$ret .= ">" .$key. "</option>\n";
						}
					}
					$num_view++;
				}
				return $ret;
			}
			//числовой массив c post_key = одна строка: post_key->value, значение->text
			//числовой массив без post_key = [ключ-число]->value, [ключ-число]->text
			//ассоциативный c post_key = одна строка: post_key->value, значение->text
			//ассоциативный без post_key = [ключ]->value, [ключ]->text
			//двумерный c post_key = post_key->[[ключ2]->value, значение2->text]
			//двумерный без post_key = [ключ]->value, [ключ]->text
			elseif ($assoc == 2){
				//если второй цикловой параметр не указан
				//раскладка двумерного массива с указанием одного ключа
				if ( $post_val == '' ) {
					//такое сочетание существует (в массиве есть такой ключ)
					if ($array[$post_key] != NULL) {
				
						//если массив одномерный
						if (!is_array($array[$post_key])){
							$ret .= "<option value='{$post_key}'";
							$ret .= ($post_key == $post)?"selected='selected'":'';
							$ret .= ">" .$array[$post_key]. "</option>\n";
							return $ret;
						}
						//если массив двумерный, т.е. целевой
						else{
							foreach ( $array[$post_key] as $key=>$val ) {
								//проверка-разрешение на показ строк массива
								if ( $num_view >= $with_which_element ) {
								
									$ret .= "<option value='{$key}'";
									$ret .= ($key == $post)?"selected='selected'":'';
									$ret .= ">" .$val. "</option>\n";
								}
								$num_view++;
							}
							return $ret;
						}
					}
					//нет такого ключа в массиве - проводится как одномерный + запись ошибки в базу!
					else{
						foreach ( $array as $key=>$val ) {
							//проверка-разрешение на показ строк массива
							if ( $num_view >= $with_which_element ) {
							
								$ret .= "<option value='{$key}'";
								$ret .= ($key == $post)?"selected='selected'":'';
								$ret .= ">" .$key. "</option>\n";
							}
							$num_view++;
						}
						return $ret;
					}
				}
				
				//раскладка двумерного массива с указанием двух ключей - 
				//каждый массив второго уровня должен содержать оба ключа
				else {
					//первый элемент массива - массив?
					$array2 = $array;

					if (is_array (array_shift ($array2) ) ) {
						$array2 = $array;

						//работаем как с двумерным
						$first = array_shift($array2);

						//проверяем, есть ли такой ключ
						if ( array_key_exists($post_key, $first) ) {
							if ( array_key_exists($post_val, $first) ) {

								foreach ( $array as $key=>$val ){
									//проверка-разрешение на показ строк массива
									if ( $num_view >= $with_which_element ) {
									
										$ret .= "<option value='{$val[$post_key]}'";
										$ret .= ($val[$post_key] == $post)?"selected='selected'":'';
										$ret .= ">" .$val[$post_val]. "</option>\n";
									}
									$num_view++;
								}
								return $ret;
							}
							else return false;
						}
						else return false;
					}
					else {
						//работаем как с одномерным - всего одна строка
						
						//проверяем, есть ли такой ключ
						if ( array_key_exists($post_key, $array) ) {
							if ( array_key_exists($post_val, $array) ) {
								$ret .= "<option value='{$array[$post_key]}'";
								$ret .= ($array[$post_key] == $post)?"selected='selected'":'';
								$ret .= ">" .$array[$post_val]. "</option>\n";
								return $ret;
							}
							else return false;
						}
						else return false;
					}
				}
				return false;
			}
			else{
				self::$error->add(2, "Недопустимое значение раскладки массива. Должно быть (0,1,2) ={$assoc}", get_class($this));
				return false;
			}
		}
		
		//нет подходящего типа array
		else{
			self::$error->add(2, "Переменная не является -> строкой, числом, массивом. Недопустимое значение переменной =".gettype($array), get_class($this));
			return false;
		}
	}
	
//вспомагательная функция для числового массива
	private function increment($x,$increment){$increment==1?$x++:$x--;return $x;}
}
?>