<?php
$separator = "../../";
include "{$separator}ajax/_ScriptControl.php";
include "{$separator}modules/includesToAjax.php";

//описать pdf, audio, video, word - может быть, несколько форматов
//версия 2.44.279 - совершенно новая. Все более ранние версии подключаемых файлов необходимо изменять вручную!

$thisFile = "mediaUpload_boundary_public"; 

if (true) {
	$id 		= $regular->num($_POST['id']);
	$idParent 	= $regular->num($_POST['idParent']);
	$cell 		= $regular->sol_symbols_ext($_POST['cell']);
	$tableName 	= $regular->sol_symbols_ext($_POST['table']);
	$type 		= $regular->sol_symbols_ext($_POST['type']);

	if (! $_POST['language']) $_POST['language'] = "ru";
	$language	= $regular->eng_num_sol($_POST['language']);
	include "{$separator}data/languages/ajax/mediaUpload_boundary_language.php";
	
	if (isset ($_POST['quad'])) $quad = $regular->num($_POST['quad']);
	else $quad = "-1";
	
	if (
		$id 		!== false &&
		$idParent 	!== false &&
		$cell 		!== false &&
		$tableName 	!== false &&
		$type 		!== false &&
		$quad		!== false
	) {
		$file_include = "{$separator}data/personal/d_{$tableName}_arrays.php";
	
		if (file_exists($file_include)) {
			include "{$file_include}";
		}
		else {
			echo "2|{$text[$language]["servererror"]}|";
			$error->add (2, "0", "Не загружается файл подключения настроек данного раздела Панели Управления! (d_TABLE_arrays.php)", $thisFile);
			$error->write($db);
			die();
		}

		//проверка существования посланного имени файла
		if ( isset ($_FILES['up_foto']) ) {

			//главное, чтобы ошибка при закачке была = 0
			if ($_FILES['up_foto']['error'] == 0) {
				
				//закачиваем не больше заданного веса
				if ( $_FILES['up_foto']['size'] <= $var['maxMediaWeight'] ) {

					$typeTrue = false;
					if ($type == "pdf"){
							
						//проверка mime-типа
						if ($_FILES['up_foto']['type'] == "application/pdf") {
							$typeTrue = true;
						}
						else {
							echo "2|{$text[$language]["error_PDF"]}|";
							$error->add (3, "0", "Неверный формат файла: {$_FILES['up_foto']['type']}", $thisFile);
							$error->write($db);
							die();
						}
					}
					elseif ($type == "word"){
							
						//проверка mime-типа
						if (
							$_FILES['up_foto']['type'] == "application/msword"||
							$_FILES['up_foto']['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"||
							$_FILES['up_foto']['type'] == "application/x-force-download"||
							$_FILES['up_foto']['type'] == "application/vnd.ms-powerpoint"
						) {
							$typeTrue = true;
						}
						else {
							echo "2|{$text[$language]["error_Word"]}|";
							$error->add (3, "0", "Неверный формат файла: {$_FILES['up_foto']['type']}", $thisFile);
							$error->write($db);
							die();
						}
					}
					elseif ($type == "pdfword"){
						if (
							$_FILES['up_foto']['type'] == "application/pdf"||
							$_FILES['up_foto']['type'] == "application/msword"||
							$_FILES['up_foto']['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document"||
							$_FILES['up_foto']['type'] == "application/x-force-download"||
							$_FILES['up_foto']['type'] == "application/vnd.ms-powerpoint"
						) {
							$typeTrue = true;
						}
						else {
							echo "2|{$text[$language]["error_PdfWord"]}|";
							$error->add (3, "0", "Неверный формат файла: {$_FILES['up_foto']['type']}", $thisFile);
							$error->write($db);
							die();
						}
					}
					elseif ($type == "audio"){
						//проверка mime-типа
						if (
							$_FILES['up_foto']['type'] == "audio/mpeg"
						) {
							$typeTrue = true;
						}
						else {
							echo "2|{$text[$language]["error_mp3"]}|";
							$error->add (3, "0", "Неверный формат файла: {$_FILES['up_foto']['type']}", $thisFile);
							$error->write($db);
							die();
						}
					}
					elseif ($type == "video"){
						//проверка mime-типа
						if (
							$_FILES['up_foto']['type'] == "video/mp4" ||
							$_FILES['up_foto']['type'] == "video/mpeg"
						) {
							$typeTrue = true;
						}
						else {
							echo "2|{$text[$language]["error_mp4"]}|";
							$error->add (3, "0", "Неверный формат файла: {$_FILES['up_foto']['type']}", $thisFile);
							$error->write($db);
							die();
						}
					}
					//изображение
					elseif (
						$type == "simple" ||
						$type == "one" ||
						$type == "triple" ||
						$type == "none" ||
						$type == "cover"
					){
						if (
							$_FILES['up_foto']['type'] == "image/jpeg" ||
							$_FILES['up_foto']['type'] == "image/pjpeg" ||
							$_FILES['up_foto']['type'] == "image/png"
						) {
							$typeTrue = true;
						}
						else {
							echo "2|{$text[$language]["error_JpgPng"]}|";
							$error->add (3, "0", "Неверный формат файла: {$_FILES['up_foto']['type']}", $thisFile);
							$error->write($db);
							die();
						}
					}
					else {
						echo "2|{$text[$language]["servererror"]}|";
						$error->add (0, "0", "ВНСМ js, нет такого type из возможных", $thisFile);
						$error->write($db);
						die();
					}

					if ($typeTrue) {
						include "{$separator}libraries/L_files/index.php";
						$files = L_files::init($wrap);
						
						//----------------------------------------------------------------------------------------
						//временный путь
						$source = $_FILES['up_foto']['tmp_name'];
						//получаем расширение в зависимости от mime-типа
						$expan = $mime->getExpan($_FILES['up_foto']['type']);
						
						//проверяем существование папки для хранения. Если не создана - создаем.
						$filePath = "{$separator}uploads/{$tableName}/";
			
						if (! file_exists ($filePath)) {
							if ($files->catalogCreate ($filePath)) {}
							else {
								echo "2|{$text[$language]["servererror"]}. {$text[$language]["notcreatecat"]}|";
								$wrap->add (2, "Каталог не создан", $thisFile);
								$error->write($db);
								exit;
							}
						}
						//обзываем новую фотку
						$isFileName = false;
						if ($id != 0) {
							$action = "update";
							$where = "`id`='{$id}' AND NOT(`deleted`)";
							$what = "`id`, `{$cell}`";
							$thisTable = $db->select_line ("{$config['prefix']}{$tableName}", $where, $what, $thisFile);
							
							if ($thisTable[$cell]) {
								$isFileName = true;
								$newName = $thisTable[$cell];
							}
						}
						else $action = "insert";

						if ($isFileName == false) {
							$newNameTrigger = false;
							while ($newNameTrigger == false) {
								//создаем новое имя для фото
								$newName = $generator->gen20_extended (40);

								//смотрим, есть ли уже такое имя в базе
								$where = "`{$cell}`='{$newName}' AND NOT(`deleted`)";
								$what = "`id`";
								$newNameDb = $db->select_line ("{$config['prefix']}{$tableName}", $where, $what, $thisFile);
								
								if (! $newNameDb) {
									$newNameTrigger = true;
								}
							}
						}
						
						$target = $filePath.$newName.'.'.$expan;

						$newFile = false;
						if ($type == "pdf") {
							if (move_uploaded_file ($source, $target)) $newFile = true;
						}
						elseif ($type == "word") {
							if (move_uploaded_file ($source, $target)) $newFile = true;
						}
						elseif ($type == "pdfword") {
							if (move_uploaded_file ($source, $target)) $newFile = true;
						}
						elseif ($type == "audio") {
							if (move_uploaded_file ($source, $target)) $newFile = true;
						}
						elseif ($type == "video") {
							if (move_uploaded_file ($source, $target)) $newFile = true;
//разрешение файла
$srcWidth = 222;
$srcHeight = 128;
$screenshotSec = 2; // на какой секунде надо сделать скриншот
$ffmpegPath = 'ffmpeg'; // путь к FFMPEG 


$file = $target; // сам файл, с которого надо сделать скриншот
$fileJpg = $filePath.$newName.'.jpg'; // имя рисунка, который будет создан

if (file_exists($file) ) {
	$wrap->add (3, "делаем скриншот $fileJpg", $thisFile);
	//делаем скриншот
	exec($ffmpegPath . ' -i ' . $file . ' -an -ss ' . $screenshotSec . ' -vframes 1 -s ' . $srcWidth . 'x' . $srcHeight . ' -y -f mjpeg ' . $fileJpg);
}
else {
	$wrap->add (3, "Скрин сделать не удалось - нет такого файла", $thisFile);
}
						}
						elseif (
							$type == "simple" ||
							$type == "one" ||
							$type == "triple" ||
							$type == "none" ||
							$type == "cover"
						){
							$arr_image = getimagesize($source);
							if ($quad == 1) {
								$type = "quad";
							}

$width_1 	= $var[$cell]['width_1'];
$height_1 	= $var[$cell]['$height_1'];
$relation_1 = $var[$cell]['relation_1'];
$width_2 	= $var[$cell]['width_2'];
$height_2 	= $var[$cell]['height_2'];
$relation_2 = $var[$cell]['relation_2'];


							//вторая фотка (и остальные)
							//резку миниатюры необходимо выполнить до переноса move_uploaded_file, т.к. move... - вырезает изображение!
							// а вырезать можно и после.
							if (
								$type == "simple"
							) {
								$target_copy = $filePath . "m_" . $newName.'.'.$expan;
								$image->resize($source, $target_copy, $width_2, $arr_image, $relation_2);
							}


							//первая фотка
							//relation:  >0 - режем фотку, оставляя фрагмент из Width x Width/relation 
							//relation:  =0 - уменьшаем фотку в зависимости от пропорций (во что она упирается - в Width или Height
							//1. если $relation_1 > 0
							if ( $relation_1 > 0 ) {
								if ( $arr_image[0] > $width_1 ) {
									if ($image->resize($source, $target, $width_1, $arr_image, $relation_1)) $newFile = true;
								}
								else {
									$height_1 = $width_1 / $relation_1;
									
									if ($arr_image[1] > $height_1) {
										if ($image->resize($source, $target, $width_1, $arr_image, $relation_1)) $newFile = true;
									}
									else {
										if (move_uploaded_file ($source, $target)) $newFile = true;
									}
								}
							}
							//2. если $relation_1 = 0
							else {
								if ( $arr_image[0] > $width_1 ) {
									if ($image->resize($source, $target, $width_1, $arr_image, $relation_1)) $newFile = true;
								}
								else {
									if ($arr_image[1] > $height_1) {
										if ($image->resize($source, $target, $width_1, $arr_image, $relation_1)) $newFile = true;
									}
									else {
										if (move_uploaded_file ($source, $target)) $newFile = true;
									}
								}
							}
						}
						else {
							echo "2|{$text[$language]["servererror"]}|";
							$wrap->add (0, "Неверно указан тип результата - потенциальный взлом", $thisFile);
							$error->write($db);
							exit;
						}

						if ( $newFile ) {
							$dbWriteTrue = false;
							if ($action == "update"){
								$data = array(
									"time_update"=>$time,
									"user_update"=>$global['user_online']['id'],
									"{$cell}"=>$newName,
									"{$cell}_ext"=>$expan,
									"{$cell}_is"=>1
								);
									
								$where = "`id`='{$id}' AND NOT(`deleted`)";
								if ($db->update ("{$config['prefix']}{$tableName}", $data, $where, $thisFile)){
									$dbWriteTrue = true;
								}
							}
							elseif ($action == "insert") {
								if ($idParent) {
									$data = array(
										"parent_id"=>$idParent,
										"time_create"=>$time,
										"user_create"=>$global['user_online']['id'],
										"{$cell}"=>$newName,
										"{$cell}_ext"=>$expan,
										"{$cell}_is"=>1
									);
								}
								else {
									$data = array(
										"time_create"=>$time,
										"user_create"=>$global['user_online']['id'],
										"{$cell}"=>$newName,
										"{$cell}_ext"=>$expan,
										"{$cell}_is"=>1
									);
								}
								if ($db->insert ("{$config['prefix']}{$tableName}", $data, $thisFile)){
									$dbWriteTrue = true;
								}
							}

							if ($dbWriteTrue) {
								echo "1|{$text[$language]["load"]}|";
							}
							else {
								echo "2|{$text[$language]["servererror"]}";
								$wrap->add (1, "Файл создан, не удалось создать запись в БД", $thisFile);
							}
						}
						else {
							echo "2|{$text[$language]["servererror"]}|";
							$wrap->add (0, "Файл записать не удалось", $thisFile);
						}
					}
					else {
						echo "2|{$text[$language]["servererror"]}|";
						$error->add (0, "0", "Ошибка в коде этого файла", $thisFile);
					}
				}
				else {
					$maxWeightText = $var['maxWeight']/$var['mb'];
					echo "2|{$text[$language]["verybigfile"]} {$maxWeightText} {$text[$language]["mb"]}|";
					$wrap->add (3, "Слишком большой размер файла", $thisFile);
				}
			}
			else {
				if ($_FILES['up_foto']['error'] == 1) {
					echo "2|{$text[$language]["bigfile"]}";
					$wrap->add (3, "Размер файла выше предела в php.ini", $thisFile);
				}
				elseif ($_FILES['up_foto']['error'] == 2) {
					echo "2|{$text[$language]["bigfile"]}";
					$wrap->add (3, "Размер файла выше max_file_size", $thisFile);
				}
				elseif ($_FILES['up_foto']['error'] == 3) {
					echo "2|{$text[$language]["nofullfile"]}";
					$wrap->add (3, "Файл передан не полностью (частично)", $thisFile);
				}
				elseif ($_FILES['up_foto']['error'] == 4) {
					echo "2|{$text[$language]["nottransmit"]}";
					$wrap->add (3, "Файл не передан", $thisFile);
				}
			}
		}
		else {
			echo "2|{$text[$language]["nofile"]}|";
			$wrap->add (3, "Нет файла", $thisFile);
		}
	}
	else {
		echo "2|{$text[$language]["servererror"]}|";
		$wrap->add (0, "Введение некорректных символов минуя javascript. Возможно, не все id прописали в Boundary", $thisFile);
	}
}
else {
	echo "9|";
	$wrap->add (0, "Попытка внедрения неавторизованным аккаунтом", $thisFile);
}
include "{$separator}error/index.php";
?>