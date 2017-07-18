<?php if ( ! defined('andromed')) exit('');

/*
Старый класс, созданный 5.12.11
Преобразован в класс-библиотеку 7.09.12 для общих нужд
2013-06-23 - fileDelete()
2013-12-10 - catalogDelete()
*/

class L_files{

	private $view_error = true;//разрешение/запрещение генерации ошибок-отчетов классом (true/false)
	private static $thisObject = null;
	private static $error;
	public static function init($error){
		if ( self::$thisObject == null ){
			self::$thisObject = new L_files();
			self::$error = $error;
			return self::$thisObject;
		}
		else {
			echo "L_files: Объект L_files уже создан ранее!";
			self::$error->add(2, "Ошибка. Один объект уже существует", 'L_files');
			exit();
		}
	}
	
	//проверка и создание каталога
	//$name - имя каталога (полный путь до каталога)
	public function catalogCreate ($name){
		//проверка, есть ли такой каталог
		if ( @opendir($name) == false ) {
			//если нет, создаем
			if ( @mkdir($name, 0777) ) {
				return true;
			}
			//не создан
			else {
				self::$error->add(2, "Ошибка. Каталог создать не удалось", 'L_files');
				return false;
			}
		}
		else return false;
	}
	
	//file - полный путь
	public function fileDelete ($file){
		if (@file_exists($file)){
			if (@unlink($file)) {
				return true;
			}
			else {
				self::$error->add(2, "Файл существует, но удалить его не удалось", 'L_files');
				return false;
			}
		}
		else {
			self::$error->add(2, "Файл {$file} не существует", 'L_files');
			return false;
		}
	}
	
	//удаление каталога
	public function catalogDelete ($dir) {
		if (@file_exists ($dir) ) {
			if ($objs = glob($dir . "/*")) {
				foreach ($objs as $obj) {
					if (is_dir($obj)) {
						$this->catalogDelete ($obj);
					}
					else {
						$this->fileDelete ($obj);
					}
				}
			}
			rmdir ($dir);
			return true;
		}
		else {
			return false;
			self::$error->add(2, "Каталог {$dir} не существует", 'L_files');
		}
	}
	
	
	//создание файла - общее
	public function createFile ($file, $stringdata = "") {
		if (! @file_exists ($file) ) {
			touch($file);
			file_put_contents($file, $stringdata);
			return true;
		}
		else return false;
	}
	
	
	//создание файла по шаблону (2014-06-09) 
	//чисто ограниченный функционал для узкой цели
	public function createFileFromTemplate ($file, $page = "", $template = "") {
		if ($template == "d_page") {
			$stringdata = '<?php if ( ! defined(\'andromed\')) exit(\'\'); 
$thisFile = "data/d_'.$page.'";

if ($dbTrue) {
	$table = "{$config[\'prefix\']}'.$page.'";
	$where = "NOT(`deleted`)";
	$what = "*";
	//$PAGE = $db->select($table, $where, $what, "id", $thisFile);
}
?>';
		}
		elseif ($template == "config") {
			$stringdata = '<?php if ( ! defined(\'andromed\')) exit(\'\'); 

//content(T_MENU_DYNAMIC);
//content_dynamic(T_MENU_DYNAMIC, $MENU, PARSE);

content(T_CONTENT);
?>';
		}
		elseif ($template == "t_content") {
			$stringdata = '<div></div>';
		}
		elseif ($template == "l_page") {
			$stringdata = '<?php if ( ! defined(\'andromed\')) exit(\'\'); 

$text["ru"][""] 	= "";

$text["en"][""] 	= "";

$text["de"][""] 	= "";
?>';
		}
		else $stringdata = "";
		
		return $this->createFile($file, $stringdata);
	}
}
?>
