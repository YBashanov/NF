<?php

// 112 нет проверок законченность резки картинки

$separator = "../../";
include "{$separator}ajax/_ScriptControl.php";
include "{$separator}modules/includesToAjax.php";

$thisFile = "xmlUpload"; 

//типы результатов type
/*	simple	создается картинка (под заточенные размеры) и маленькая иконка m_
	one		создается картинка (под заточенные размеры)
	triple	создаются 3 картинки - большая, поменьше и маленькая (c_, m_) c - от center
	none	картинка загружается как есть, без сжатия
*/

if ($global['authorization']) {
	$id 		= $regular->num($_POST['id']);
	$idParent 	= $regular->num($_POST['idParent']);
	$tableName 	= $regular->sol_symbols_ext($_POST['table']);
	$type 		= $regular->sol_symbols_ext($_POST['type']);
	
	if (isset ($_POST['quad'])) $quad = $regular->num($_POST['quad']);
	else $quad = "-1";

	if (
		$id 		!== false &&
		$idParent 	!== false &&
		$tableName 	!== false &&
		$type 		!== false &&
		$quad		!== false
	) {
		if (
			$tableName == "price"
		) {
			//для апдейта
			if ($id != 0) {
				$action = "update";
				$where = "`id`='{$id}' AND NOT(`deleted`)";
				$what = "`id`";
				$thisTable = $db->select_line ("{$config['prefix']}{$tableName}", $where, $what, $thisFile);
				
				$newName = $thisTable['id'];
			}
			//для создания
			else {
				$action = "insert";
				$where = "true ORDER BY `id` DESC";
				$what = "`id`";
				$thisTable = $db->select_line ("{$config['prefix']}{$tableName}", $where, $what, $thisFile);
				
				if ($thisTable)	$newName = $thisTable['id'] + 1;
				else $newName = 1;
			}
			
			
				//проверка существования посланного имени файла
				if ( isset ($_FILES['up_foto']) ) {

					//главное, чтобы ошибка при закачке была = 0
					if ($_FILES['up_foto']['error'] == 0) {
					
						//закачиваем не больше заданного веса
						if ( $_FILES['up_foto']['size'] <= $var['maxWeight'] ) {

							//проверка mime-типа
							if (
								$_FILES['up_foto']['type'] == "text/xml"
							) {
								include "{$separator}libraries/L_mimeType/index.php";
								include "{$separator}libraries/L_files/index.php";
								$files = L_files::init($wrap);
								
								//----------------------------------------------------------------------------------------
								//временный путь
								$source = $_FILES['up_foto']['tmp_name'];

								//получаем расширение в зависимости от mime-типа
								$mime = L_mimeType::init($wrap);
								$expan = $mime->getExpan($_FILES['up_foto']['type']);

								
								//проверяем существование папки для хранения. Если не создана - создаем.
								$filePath = "{$separator}@/uploads/{$tableName}/";
			
								if (! file_exists ($filePath)) {
									if ($files->catalogCreate ($filePath)) {
										if (touch($filePath . "index.html")) {
											$wrap->add (3, "Каталог создан", $thisFile);
										}
										else {
											$wrap->add (2, "Каталог создан не до конца - нет index.html", $thisFile);
										}
									}
									else {
										echo "2|Ошибка сервера. Каталог не создан|"; 
										$wrap->add (2, "Каталог не создан", $thisFile);
										$error->write($db);
										exit;
									}
								}
								//обзываем новый файл
								$target = $filePath.$newName.'.'.$expan;

								
								$newFile = false;
								//записываем файл на сервер
								if (move_uploaded_file ($source, $target)) $newFile = true;
								
								
								if ( $newFile ) {
									$dbTrue = false;
									if ($action == "update"){
										$data = array(
											"time_update"=>$time,
											"user_update"=>$global['user_online']['id']
										);
										$where = "`id`='{$id}' AND NOT(`deleted`)";
										if ($db->update ("{$config['prefix']}{$tableName}", $data, $where, $thisFile)){
											$dbTrue = true;
										}
									}
									elseif ($action == "insert") {
										if ($tableName == "price"){
											$data = array(
												"time_create"=>$time,
												"user_create"=>$global['user_online']['id'],
												"status"=>1,
												"sections_id"=>$idParent
											);
										}
										else {
											$data = array(
												"time_create"=>$time,
												"user_create"=>$global['user_online']['id'],
												"status"=>1
											);
										}
										if ($db->insert ("{$config['prefix']}{$tableName}", $data, $thisFile)){
											$dbTrue = true;
										}
									}
									
									if ($dbTrue) {
										
//процедура анализа XML
if ($tableName == "price"){
	include "{$separator}libraries/L_xml/index.php";
	$xml = L_xml::init();
	$xmldata = $xml->getXMLData($target);

	//массив, который собирает номера ячеек, прошедшие 1й критерий - знак №
	//$goodArray_1 = array();
	
	if ($xmldata) {
		$triggerProducts = false;
		$classes_id = 0;
		for($i=0; $i<count($xmldata); $i++){
			
			//пока не открываем возможность формировать запрос, ждем ключевое слово - Товар
			if ($triggerProducts == false){
				for($j=0; $j<count($xmldata[$i]); $j++) {
					if ($xmldata[$i][$j] == "Товар") $triggerProducts = true;
				}
			}
			
			//считывать данные начинаем со следующей строки после ключевого слова
			else if ($triggerProducts == true){

				//если длина массива = 1, это название компании.
				if (count($xmldata[$i]) == 1) {
					//если это не пустая строка
					if ($xmldata[$i][0]) {
						//ищем classes_id, если такой класс есть (делаем запрос в базу по названию)
						//Если такого класса нет - создаем.
						
						//анализ name
						$elements = explode(" ", $xmldata[$i][0]);
						$previousName = "";
						$name = "";
						for ($k=0; $k<count($elements); $k++){
							if (
								$elements[$k] != "Буд-ки" &&
								$elements[$k] != "сетевые" &&
								$elements[$k] != "Настен." &&
								$elements[$k] != "Часы" &&
								$elements[$k] != "скульптурные" &&
								$elements[$k] != "дерев." &&
								$elements[$k] != "электронные"
							){
								$previousName .= $elements[$k];
								
								$elements_2 = explode(".", $previousName);

								for ($m=0; $m<count($elements_2); $m++){
									if (
										$elements_2[$m] != "Настен"
									){
										$name .= $elements_2[$m];
									}
								}
							}
						}
						$name = trim($xmldata[$i][0]);

						if ($name) {
							$table = "{$config['prefix']}cat_classes";
							$fields = "`sections_id`, `name`, `show`, `time_create`, `user_create`";
							$queryValues = "('{$idParent}', '{$name}', '1', '{$time}', '{$global['user']['id']}')";
							$queryValues .= " ON DUPLICATE KEY UPDATE";
							$queryValues .= " `time_update`= '{$time}',";
							$queryValues .= " `user_update`= '{$global['user']['id']}'";

							if ($db->insertSimple($table, $fields, $queryValues, $thisFile)) {
								$classes_id = $db->id();
							}
							else {
								$wrap->add (1, "Не удалось записать/обновить строку", $thisFile);
							}
						}
					}
				}
				//если длина массива отлична от 1, это - данные
				//замечу, что мы не прогоняем массив по внутреннему подмассиву. Нам достаточно взять первый элемент - 
				// название товара!
				else {

					//если это не пустая строка
					//  $xmldata[$i][0] - Часы наст."Тройка" №370 A11131155
					if ($xmldata[$i][0]) {
						//формируем строку запроса на основании полученной classes_id

						$articul = "";
						$serial = "";
						$name = "";
						
						//анализ тех строк (ячеек), в которых есть знак №
						if (strpos($xmldata[$i][0], "№") !== false){
						
							//разбиваем строку по пробелам
							$elements = explode(" ", $xmldata[$i][0]);

							//анализируем каждую часть отдельно - разбиваем целую строку
							for ($k=0; $k<count($elements); $k++){
								
								//если это не пустая строка
								if ($elements[$k]){
									
									//ищем строку с символом №
									//след.строка - анализируем следующую строку
									//1. цифры, англ.буквы (большие), 
									//2. не должно быть фраз - мм, других маленьких букв
									if (strpos($elements[$k], "№") !== false){
										$a_articul = explode("№", $elements[$k]);
										$articul = $a_articul[1];
										
										//$goodArray_1[$good_i] = $i;
										
										//анализ name
										$name = "";
										for ($m=0; $m<$k; $m++){
											//критерии записи в поле Имя
											if (
												$elements[$m] != "Часы" &&
												$elements[$m] != "наруч." 
											) {
												$name .= $elements[$m] . " ";
											}
										}
										$name = trim($name);
										
										//то, что после - возможно серийник
										if ($elements[$k+1]) {
											//критерии записи в поле Серийный номер (заводской)

											//ни первое, ни второе поле не должно содержать значения мм
											if (
												strpos($elements[$k+1], "мм") === false && 
												$elements[$k+2] != "мм" &&
												$elements[$k+1] != "Минута" &&
												$elements[$k+1] != "Секунда" &&
												$elements[$k+1] != "ЧЕРНЫЕ" &&
												$elements[$k+1] != "КОСМОС" &&
												$elements[$k+1] != "DAEWOO" &&
												$elements[$k+1] != "HYUNDAI" &&
												strpos($elements[$k+1], "(") === false
											) {
												//проверяем символы в нижнем регистре
												preg_match ("/^[0-9A-ZАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ][0-9A-ZАБВГДЕЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ\-\/]+/", $elements[$k+1], $array_match);

												if ($array_match[0] !== NULL) {
													$serial = $elements[$k+1];
												}
											}
										}
									}
								}
							}
						}
						//2 вариант - анализируем строку целиком на предмет №
						//только тут наоборот - смотрим те строки, в которых этого знака нет.
						elseif (strpos($xmldata[$i][0], "№") === false){
							
							$articul = "";//без артикула
							
							//делим строку по пробелам
							$elements = explode(" ", $xmldata[$i][0]);
							
							for ($k=0; $k<count($elements); $k++){
							
								//если это не пустая строка
								if ($elements[$k]){
									
									//ищем строку более 1 символа, в которой только большие буквы и цифры (и тире)
									preg_match ("/^[0-9A-ZАБВГДЕЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ]{2,}[\-\/]{0,1}[0-9A-ZАБВГДЕЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ]+/", $elements[$k+1], $array_match);
									//preg_match ("/^[[0-9A-ZАБВГДЕЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ]{2,}[\-\/]{1}]{2,}/", $elements[$k+1], $array_match);
									
									if ($array_match[0] !== NULL) {
										//анализ name
										$name = "";
										for ($m=0; $m<=$k; $m++){
											//критерии записи в поле Имя
											if (
												$elements[$m] != "Буд-ки" &&
												$elements[$m] != "Будильник" &&
												$elements[$m] != "кварц."
											) {
												$name .= $elements[$m] . " ";
											}
										}
										$name = trim($name);
										
										//если надо номер полностью (с добавлением -Зел., то тогра просто присвоить $elements[$k+1]
										$serial = $array_match[0];
									}
								}
							}
						}
						
						if ($name) {
							$table = "{$config['prefix']}cat_products";
							$fields = "`classes_id`, `name`, `serial_number`, `articul`, `quantity`, `price_sale`, `price_retail`, `show`, `time_create`, `user_create`";
							$queryValues = "('{$classes_id}', '{$name}', '{$serial}', '{$articul}', '1', '0', '0', '1', '{$time}', '{$global['user']['id']}')";
							$queryValues .= " ON DUPLICATE KEY UPDATE";
							$queryValues .= " `time_update`= '{$time}',";
							$queryValues .= " `user_update`= '{$global['user']['id']}'";
							$db->insertSimple($table, $fields, $queryValues, $thisFile);
						}
					}
				}
			}
		}
	}
	
	//данный файл сохраняется на сервере
	$tableClasses = "cat_classes";
	$tableProducts = "cat_products";
}
										echo "1|Загружено|";
									}
									else {
										echo "2|Ошибка сервера *|";
										$wrap->add (1, "Картинка создана, не удалось создать запись в БД", $thisFile);
									}
								}
								else {
									echo "2|Ошибка сервера **|";
									$wrap->add (0, "Файл записать не удалось", $thisFile);
								}
							}
							else {
								echo "2|Файл должен быть XML|";
								$wrap->add (3, "Неверный формат файла - {$_FILES['up_foto']['type']}", $thisFile);
							}
						}
						else {
							$maxWeightText = $var['maxWeight']/$var['mb'];
							echo "2|Слишком большой размер файла.<br>Должен быть не больше {$maxWeightText} Mб|";
							$wrap->add (3, "Слишком большой размер файла", $thisFile);
						}
					}
					else {
						if ($_FILES['up_foto']['error'] == 1) {
							echo "2|Слишком большой размер файла";
							$wrap->add (3, "Размер файла выше предела в php.ini", $thisFile);
						}
						elseif ($_FILES['up_foto']['error'] == 2) {
							echo "2|Слишком большой размер файла";
							$wrap->add (3, "Размер файла выше max_file_size", $thisFile);
						}
						elseif ($_FILES['up_foto']['error'] == 3) {
							echo "2|Файл передан не полностью";
							$wrap->add (3, "Файл передан не полностью (частично)", $thisFile);
						}
						elseif ($_FILES['up_foto']['error'] == 4) {
							echo "2|Файл не передан";
							$wrap->add (3, "Файл не передан", $thisFile);
						}
					}
				}
				else {
					echo "2|Нет файла|";
					$wrap->add (3, "Нет файла", $thisFile);
				}
			/*}
			else {
				echo "2|Ошибка сервера|";
				$wrap->add (0, "ВНСМ JS: В БД нет такой строки (id={$id})", $thisFile);
			}*/
		}
		else {
			echo "2|Ошибка сервера*|";
			$wrap->add (0, "Выбор иной таблицы БД для записи ({$tableName}), минуя javascript", $thisFile);
		}
	}
	//на самом деле - неверные символы не пропустила regular
	else {
		echo "2|Ошибка сервера**|";
		$wrap->add (0, "Введение некорректных символов минуя javascript", $thisFile);
	}
}
else {
	echo "9|";
	$wrap->add (0, "Попытка внедрения неавторизованным аккаунтом", $thisFile);
}
include "{$separator}error/index.php";
?>