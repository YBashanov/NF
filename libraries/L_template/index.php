<?php if ( ! defined('andromed')) exit('');

//класс не является единичным экземпляром
//2014-02-08
//2014-02-18 - расширение includeConfigTemlpate
//2014-02-19 - расширение includeConfigTemlpate - переменна $TrueFileName
//2014-02-26 - setPage
//2014-03-03 - parse
//2014-04-10 - [KEY_] text [/KEY_] - добавлена возможность управления содержимым
	//эта [KEY_] не должна начинаться с первого символа в файле, иначе не будет работать!
//2014-10-04 - работа с module_
//2014-10-07 - parseDynamic_light. Пользоваться так:
	//	$template->setPath("html/t_catalog/t_products_one_parameters.html");
	//	$template->parseDynamic_light("T_PRODUCTS_ONE_PARAMETERS", $parameters, "PARAMETER");
//2015-02-12 - добавлено ключевое слово-тег [ONCE] - теперь всё, что между этими тегами 
	// - показывается в одноуровневом цикле 1 раз
/*2015-03-30 - добавлена ключевая конструкция 
	[DATE] [SHOW] d.m.Y [/SHOW] {имя_переменной} [/DATE] - возвращает любой формат, тот что между SHOW
	[DATE] [SHOW]wday[/SHOW] {имя_переменной} [/DATE] - возвращает день недели

  2015-08-23
    linesInOne() - преобразует содержимое файла в строку без пробелов и переносов (для сохранения в переменную js)
*/
	

class L_template{
	private $global = array();		//глобальные переменные
	private $arrayvars = array();	//переменные замены, массив (параметры, пришедшие извне, как строки)
	private $arraynext = array();	//переменные, присутствовавшие в прошлых циклах, отпарсены, прячутся в arrayparse, массив
									//это маркеры, по ним можно определить, куда вставлять в следующем цикле
									//в arrayparse массиве они наполняются следующими за ними значениями (т.е. как бы "переменная наперёд")
	private $path = "";				//путь к файлу шаблона
	private $error = null;			//класс ошибок
	private $content = "";			//содержимое последнего полученного файла (как части шаблона)
	private $arrayparse = array();	//массив с заполненными переменными после парсинга (тут все переменные статичны, из arrayvars + arraynext)
	
	//private $arraydynamic = array();//переменные для сохранения в цикле - не требуется, т.к. необходимо условие проще (см. метод)
	private $arrayparsedin = "";	//строка с сохраненным динамическим контентом
	
	public function __construct(){}
	
	//записать в класс глобальные переменные
	public function setGlobal($global){
		if (is_array ($global)) {
			$this->global = $global;
			$this->error = $global['wrap'];
			return true;
		}
		else return false;
	}
	
	//записать в класс путь к папке шаблона
	//inChangeTemplate = true - путь опускается в выбранный шаблон по умолчанию (например, в default)
	//	= false - путь определяется только от templates/, без global['template']
	public function setPath ($path, $inChangeTemplate = true) {
		$tplpath = "";
		if ($this->global['separator']) {
			$tplpath .= "{$this->global['separator']}templates/";
		}

		if ($inChangeTemplate == true) {
			if ($this->global['template']) {
				$tplpath .= $this->global['template'] . "/";
			}
		}
		$tplpath .= $path;

		$this->path = $tplpath;
		return $this->analyzeFile($path);
	}
	//если файл существует, забираем содержимое
	private function analyzeFile ($filename) {
		if (file_exists ($this->path) ){
			$content = implode("", (@file($this->path)));

			if ( (!$content) or (empty ($content)) )
			{
				$this->content = "<div style='font-size:12px;color:red;'>< -- Нет данных в файле -- No data in the file -- > ({$filename})</div>";
				$this->error->add(2, "Нет данных в файле ! No data in the file ({$filename})", get_class($this));
				return false;
			}
			else {
				$this->content = $content;
				return $content;
			}
		}
		else {
			$this->content = "<div style='font-size:12px;color:red;'>< -- Нет файла -- No file -- > ({$filename})</div>";
			$this->error->add(2, "Нет файла ({$filename})", get_class($this));
			return false;
		}
	}
	
	//записать в класс переменные для замены
	//array - массив 
	//action  = w - запись поверх, = a - добавление к предыдущему
	public function setVars ($array, $action = "a"){
		if (is_array ($array)) {
			if ($action == "w") {
				$this->arrayvars = $array;
			}
			elseif ($action == "a") {
				$this->arrayvars = array_merge($this->arrayvars, $array);
			}
			return true;
		}
		else return false;
	}
	//клон метода setVars
	public function add ($array, $action = "a"){
		$this->setVars ($array, $action);
	}
	
	//записать в класс переменные для взятия из arrayparse
	//action  = w - запись поверх, = a - добавление к предыдущему
	public function setNext ($array, $action = "a"){
		if (is_array ($array)) {
			if ($action == "w") {
				$this->arraynext = $array;
			}
			elseif ($action == "a") {
				$this->arraynext = array_merge($this->arraynext, $array);
			}
			return true;
		}
		else return false;
	}
	
    //несколько строк текста перевести в одну (удобно для сохранения содержимого html в переменную javascript)
    public function linesInOne ($content = false) {
        if ($content == false) $fileData = $this->content;
        else $fileData = $content;
        
        //если пробелов больше 1, то они заменяются на один пробел
        $fileData = preg_replace("/(\s){1,}/", " ", $fileData);
        
        if ($content == false) $this->content = $fileData;
        
        return $fileData;
    }
	
	//одеваем переменные
	//$keyparse - ключ массива, куда будет записываться содержимое
	//$content - текстовое содержимое
	public function parse ($keyparse = "HTML", $content = false){
		if ($content == false) $fileData = $this->content;
		else $fileData = $content;
        
		//это переменные из array(), добавленные вручную в функции
		if (! empty ($this->arrayvars) ) {
			foreach ($this->arrayvars as $key=>$val) {
				$key2Up 	= strtoupper($key);
				$tagOpen 	= '['."$key2Up".']';
				$tagClose 	= '[/'."$key2Up".']';
				$keystring = '{'."$key2Up".'}';

				$isTag 		= false;
				$isTagClose = false;
				$forSubstr 	= "";

				if (! (empty($key)) && ! (empty($val))){
					if(gettype($val) != "string"){
						settype($val, "string");
					}

					$isTag = strpos($fileData, $tagOpen);
					if ($isTag) {
						//образец: [KEY_] (какой-то код) [/KEY_] - убрать маркеры, если они есть
						$fileData = str_replace($tagOpen, "", $fileData);
						$fileData = str_replace($tagClose, "", $fileData);
					}

					$fileData = str_replace($keystring, $val, $fileData);
				}
				else {
					//убрать всю часть, ограниченную [] в шаблоне.
					$isTag = strpos($fileData, $tagOpen);
					if ($isTag){
						$isTagClose = strpos($fileData, $tagClose);
						if ($isTagClose) {
							$forSubstr = substr($fileData, 0, $isTag);
							$forSubstr .= substr($fileData, ($isTagClose+strlen($tagClose)), strlen($fileData));
							$fileData = $forSubstr;
						}
					}
					
					$val = "";//заменяет пустым, если не существует такого val_
					$fileData = str_replace($keystring, $val, $fileData);
				}
			}
		}

		//сюда добавляются переменные из config.php
		if (! empty ($this->arraynext) ) {
			foreach ($this->arraynext as $key=>$val) {
				if (! (empty($key))){
					$keystring = '{'."$key".'}';
					$valstring = $this->arrayparse[$key];
					$fileData = str_replace($keystring, $valstring, $fileData);
				}
			}
		}
		$this->arrayparse[$keyparse] = $fileData;
		$this->content = "";
		return $fileData;
	}
	
	
	
	//здесь прогоняется цикл только по одной единственной переменной - key
	//массив arrayNumber - простой, числовой
	//keyparse - как и в остальных случаях - ключ к глобальному массиву шаблонов
	public function parseDynamic_light ($keyparse, $arrayNumber, $key){
		$fileData = $this->content;
		$fileResult = "";

		if ($fileData) {
			$key2Up = strtoupper($key);
			$keystring = '{'."$key2Up".'}';

			for ($i=0; $i<count($arrayNumber); $i++) {
				$fileResult .= str_replace($keystring, $arrayNumber[$i], $fileData);
			}			
			
			$this->arrayvars[$keyparse] = $fileResult;
			$this->content = "";
			return $fileResult;
		}
		else {
			$this->error->add(2, "parseDynamic_light: Пустой шаблон (либо шаблона не существует)", get_class($this));
			return false;
		}
	}
	
	
	
	//наращивание контента в цикле с заполнением переменными
	//arrayData - массив с данными (как правило, двумерный, взятый из базы методом select)
	//Имена переменных в шаблоне должны совпадать с ключами массива, и тогда будет происходить замена.
	// это сделано с целью убрать лишний цикл из выборки по массиву с переменными шаблона. Остался только массив с переменными из базы.
	//add_iterator - добавляет переменную I (в разделы разного уровня! - одна именная переменная для всех уровней)
	//suffix_example - это суффикс подчиненного файла, используемого в сложных (2- 3- уровневых вложенных) циклах.
	// Может быть массивом вида array("_SUB", "_FOTO" ...), тогда парсинг происходит столько раз, сколько элементов в массиве
	// Может быть строкой со значениями, разделенными запятыми вида "_SUB, _FOTO ...", строка преобразуется в массив
	// по умолчанию _SUB
	
	//2014-04-01 - 
	//анализ разделов 2-го уровня. Если в keyparse вложить двумерный массив (классический, из базы), в котором
	// есть переменная [sub] (! зарезервирована !), то тот двумерный массив, что содержится в [sub],
	// разложится динамически по шаблонам, собранном в файле $keyparse_SUB
	//Только для начала этот файл (например, T_LEFT_MENU_DYNAMIC_SUB) надо указать простой функцией, такой как html, content...
	// перед динамическими функциями, такими как html_dynamic, content_dynamic...
	
	//2014-04-02 - анализ разделов 3-го уровня и глубже. Рекурсивная.
	private $add_iterator = false;
	public function parseDynamic($keyparse, $arrayData, $add_iterator = false, $suffix_example = "_SUB"){
		if (! $suffix_example) $suffix_example = "_SUB";
		$this->add_iterator = $add_iterator;

		//строка становится массивом
		if (! is_array ($suffix_example)) {
			$suffix_example = preg_replace('/\s/', '', $suffix_example);
			$suffix_example = explode(",", $suffix_example);
		}
		
		$this->parallel_count = count($suffix_example);
		
		$keyparse_s 	= array();	//[параллельный] тут название шаблона - T_LEFT_MENU_DYNAMIC, T_LEFT_MENU_DYNAMIC_SUB... 
		$fileData 		= array(); 	//[параллельный] тут шаблон для замены
		$resultData 	= ""; 		//строка для сохранения и передачи дальше
		
		//нужно ведение нескольких одноуровневых параллельных массивов 
		// (если в одном динамическом файле прогоняются 2, 3 и более динамических файлов)
		// i_parallel - счетчик итераций, отвечающий за параллельные массивы в файле 2-го уровня (второй уровень вложенности)
		// Если suffix_example всего один, тогда i_parallel = 0
		for ($i_parallel=0; $i_parallel<$this->parallel_count; $i_parallel++) {
			$whileGo = true;
			
			$keyparse_s[$i_parallel] 	= array();
			$fileData[$i_parallel]		= array();
			$wh_num_suf = 0; //сколько раз повторяются итерации с наращиванием суффиксов

			//выцепляем все возможные варианты данного шаблона с подчиненными шаблонами с суффиксом _SUB
			// подключаем соответствующие существующие файлы, и берем из них шаблоны (сохраняем в массив)
			while ($whileGo) {
				$suffix = "";
				for ($wh_i=0; $wh_i<$wh_num_suf; $wh_i++) {
					$suffix .= $suffix_example[$i_parallel];
				}
				$keyparse_s[$i_parallel][$wh_num_suf] = $keyparse . $suffix;
				$fileData[$i_parallel][$wh_num_suf] = $this->arrayparse[$keyparse_s[$i_parallel][$wh_num_suf]];

				//если на этот файл нет данных, либо нет этого файла - выход из массива
				if (! $fileData[$i_parallel][$wh_num_suf]) $whileGo = false;
				$wh_num_suf++;
			}
		}

		//после того, как fileData сформирован, выносим его вовне
		$this->fileData = $fileData;
		

		if (is_array ($arrayData) && ! empty ($arrayData) ) {
			$level = 0;
			$this->result_i[$level] = 0; //для порядковой нумерации внутри шаблона
			
			foreach ($arrayData as $key=>$val) {
				$resultData .= $this->parseDynamic_Foreach($val, $keyparse_s, $fileData[0][0], $level);
				$this->result_i[$level]++;
			}
		}
		
		//keyparse_s[0][0] и [1][0] совпадают - тут тот же шаблон
		//resultData - конечный правильно сформированный макет
		$this->arrayparse[$keyparse_s[0][0]] = $resultData;
		//динамичный парсинг пройден, 
		// записываем в файл измененные данные для статичного парсинга
		$this->content = $resultData;
	}
	
	private $parallel_count = 1;//показывает, сколько параллельных циклов 2 уровня (файлов 2го уровня) будет в цикле файла 1 уровня
	private $result_i = array();//для порядковой нумерации внутри шаблона
	private $fileData = array();
	//подчиненная функция для метода parseDynamic - прогон цикла (с самовызовом, т.к. массив val - вложенный)
	//level меняется только здесь
	
	//val			- элемент массива (по сути, сам является массивом)
	//keyparse_s 	- ключи шаблонов - T_LEFT_MENU_DYNAMIC, T_LEFT_MENU_DYNAMIC_SUB... 
	//fileData		- один шаблон для вставки (голый)
	//level			- уровень глубины анализа информационного массива. Mеняется только здесь
	
	//параллели 	- несколько самостоятельных мини-циклов внутри одного динамического файл-цикла
	private function parseDynamic_Foreach ($val, $keyparse_s, $fileData, $level) {
		$resultFileData = ""; //строка для сохранения промежуточных значений
		$resultData 	= array(); //строка для сохранения и передачи дальше
		
		$level_next = $level+1;
		$i_parallel = 0;
		
		//анализ внутреннего раздела (раздела 2-го уровня) ---------------------
		if (is_array ($val['sub']) && ! (empty($val['sub']))){
			
			//ориентир на параллели
			if ($level == 0 && $this->parallel_count > 1) {
				for ($i_parallel=0; $i_parallel<$this->parallel_count; $i_parallel++) {
					//если подключен шаблон sub
					if ($this->fileData[$i_parallel][$level_next]) {
						$this->result_i[$level_next] = 0;
						foreach ($val['sub'] as $key2=>$val2) {
							
							//тут функция запускается рекурсивно
							
							$resultData[$i_parallel] .= $this->parseDynamic_Foreach($val2, $keyparse_s, $this->fileData[$i_parallel][$level_next], $level_next);
							$this->result_i[$level_next]++;
						}
					}
					else {
						$this->error->add(2, "Не подключен шаблон ! Not attached template ({$keyparse_s[$i_parallel][$level_next]}). Цикл №{$this->result_i[$level_next]}", get_class($this));
					}
				}
			}
			else {
				//если подключен шаблон sub
				if ($this->fileData[0][$level_next]) {
					$this->result_i[$level_next] = 0;
					foreach ($val['sub'] as $key2=>$val2) {
						
						//тут функция запускается рекурсивно
						
						$resultData[0] .= $this->parseDynamic_Foreach($val2, $keyparse_s, $this->fileData[0][$level_next], $level_next);
						$this->result_i[$level_next]++;
					}
				}
				else {
					$this->error->add(2, "Не подключен шаблон ! Not attached template ({$keyparse_s[0][$level_next]}). Цикл №{$this->result_i[$level_next]}", get_class($this));
				}
			}
		}
		


		//ориентир на параллели
		if ($level == 1 && $this->parallel_count > 1) {
			for ($i_parallel=0; $i_parallel<$this->parallel_count; $i_parallel++) {
				$resultFileData = $this->parseDynamic_Replace($fileData, $val);
			}
		}
		else {
			$resultFileData = $this->parseDynamic_Replace($fileData, $val);
		}


		
		
		//[ONCE]: находясь в настоящем уровне, отпираем последующий (чтобы там снова можно было использовать [ONCE])
		if ($this->isOnce[$keyparse_s[$i_parallel][$level_next]] == true) {
			$this->isOnce[$keyparse_s[$i_parallel][$level_next]] = false;//открываем для работы ключевого слова-тега [ONCE]
		}
		//[ONCE]: если метка в isOnce не установлена, либо = false, разрешается показывать то, что между тегами [ONCE]
		if ($this->isOnce[$keyparse_s[$i_parallel][$level]] == false) {
			$resultFileData = $this->show_once($resultFileData, true);
			$this->isOnce[$keyparse_s[$i_parallel][$level]] = true;
		}
		else {
			$resultFileData = $this->show_once($resultFileData, false);
		}

		
		//[DATE]
		$resultFileData = $this->show_date($resultFileData);

		
		//ориентир на параллели
		if ($level == 0 && $this->parallel_count > 1) {
			for ($i_parallel=0; $i_parallel<$this->parallel_count; $i_parallel++) {
				//добавить изменения из цикла 2-го уровня в раздел 1-го уровня
				//а если в 1м уровне - несколько переменных 2го уровня ???
				if ($this->fileData[$i_parallel][$level_next]) {
					if (is_array ($val['sub']) && ! (empty($val['sub']))) {
						$key2Up = strtoupper($keyparse_s[$i_parallel][$level_next]);
						$keystring = '{'."$key2Up".'}';
						$resultFileData = str_replace($keystring, $resultData[$i_parallel], $resultFileData);
					}
					//если во 2м уровне для данного раздела - пусто
					else {
						$key2Up = strtoupper($keyparse_s[$i_parallel][$level_next]);
						$keystring = '{'."$key2Up".'}';
						$resultFileData = str_replace($keystring, "", $resultFileData);
					}
				}
			}
		}
		else {
			//добавить изменения из цикла 2-го уровня в раздел 1-го уровня
			//а если в 1м уровне - несколько переменных 2го уровня ???
			if ($this->fileData[0][$level_next]) {
				if (is_array ($val['sub']) && ! (empty($val['sub']))) {
					$key2Up = strtoupper($keyparse_s[0][$level_next]);
					$keystring = '{'."$key2Up".'}';
					$resultFileData = str_replace($keystring, $resultData[0], $resultFileData);
				}
				//если во 2м уровне для данного раздела - пусто
				else {
					$key2Up = strtoupper($keyparse_s[0][$level_next]);
					$keystring = '{'."$key2Up".'}';
					$resultFileData = str_replace($keystring, "", $resultFileData);
				}
			}
		}


		if ($this->add_iterator == true) {
            if ($this->add_iterator === true) {
                $resultFileData = str_replace("{I}", $this->result_i[$level], $resultFileData);
            }
            else {
                $resultFileData = str_replace("{I}", $this->result_i[$level] + $this->add_iterator, $resultFileData);
            }
		}
		
		return $resultFileData;
	}
	
	
	//подчиненная функция для метода parseDynamic - замена переменных
	//fileTemplate - голый шаблон (содержимое)
	//val - ассоциативный массив данного уровня вложенности (тут не важно, какого уровня)
	private function parseDynamic_Replace ($fileTemplate, $val) {
		$toReplace = $fileTemplate;

		foreach ($val as $key_=>$val_) {
			$key2Up = strtoupper($key_);
			$keystring = '{'."$key2Up".'}';
			$tagOpen = '['."$key2Up".']';
			$tagClose = '[/'."$key2Up".']';
			$isTag 		= false;
			$isTagClose = false;
			$forSubstr = "";
			
			//если значение переменной записано
			if (! (empty($key_)) && ! (empty($val_))){
				$toReplace = str_replace($keystring, $val_, $toReplace);
				
				$isTag = strpos($toReplace, $tagOpen);
				if ($isTag) {
					//образец: [KEY_] (какой-то код) [/KEY_] - убрать маркеры, если они есть
					$toReplace = str_replace($tagOpen, "", $toReplace);
					$toReplace = str_replace($tagClose, "", $toReplace);
				}
			}
			//если значение переменной пустое
			else {
				//убрать всю часть, ограниченную [] в шаблоне.
				//эта [переменная] не должна начинаться с первого символа в файле, иначе не будет работать!
				$isTag = strpos($toReplace, $tagOpen);

				if ($isTag){
					$isTagClose = strpos($toReplace, $tagClose);
					if ($isTagClose) {
						$forSubstr = substr($toReplace, 0, $isTag);
						$forSubstr .= substr($toReplace, ($isTagClose+strlen($tagClose)), strlen($toReplace));
						$toReplace = $forSubstr;
					}
				}
				
				$val_ = "";//заменяет пустым, если не существует такого val_
				$toReplace = str_replace($keystring, $val_, $toReplace);
			}
		}
		
		return $toReplace;
	}
	
	
	//дополнительная функция - использование ключевого слова-тега [ONCE]
	//Показывает содержимое, ограниченное тегами [ONCE] всего 1 раз в цикле одного уровня.
	private $isOnce = array(); //было ли использование [ONCE] в запущенном в настоящий момент цикле
	//action = true, значит показываем, = false - скрываем всё, что между тегами
	private function show_once($toReplace, $action){
		$tagOpen = "[ONCE]";
		$tagClose = "[/ONCE]";
		$forSubstr = "";
		
		$isTag = strpos($toReplace, $tagOpen);
		if ($isTag) {
			if ($action == true){
				//образец: [ONCE] (какой-то код) [/ONCE] - убрать маркеры, если они есть
				$toReplace = str_replace($tagOpen, "", $toReplace);
				$toReplace = str_replace($tagClose, "", $toReplace);
				
				$this->isOnce[$keyparse_key] = true; //запираем
			}
			else {
				$isTagClose = strpos($toReplace, $tagClose);
				if ($isTagClose) {
					$forSubstr = substr($toReplace, 0, $isTag);
					$forSubstr .= substr($toReplace, ($isTagClose+strlen($tagClose)), strlen($toReplace));
					$toReplace = $forSubstr;
				}
			}
		}
		return $toReplace;
	}
	
	
	//дополнительная функция - использование ключевого слова-тега [DATE]
	//Преобразовывает time между тегами в формат date (то, что показывать - выбирается внутри тега ключевым словом SHOW
	//Образец: [DATE] [SHOW] d.m.Y [/SHOW] {имя_переменной} [/DATE]
	private $isDate	= array(); //было ли использование [DATE] в запущенном в настоящий момент цикле
	private function show_date($toReplace){
		$tagOpen = "[DATE]";
		$tagClose = "[/DATE]";
		$tagShowOpen = "[SHOW]";
		$tagShowClose = "[/SHOW]";
		$forSubstr = "";
		
		$isTagStart = strpos($toReplace, $tagOpen);
		if ($isTagStart) {
			
			$isTagEnd = strpos($toReplace, $tagClose);
			if ($isTagEnd) {
				
				if ($isTagStart < $isTagEnd) {
					//убираем лишние метки DATE
					$substr1 = substr($toReplace, 0, $isTagStart);
					$substr2 = substr($toReplace, ($isTagEnd+strlen($tagClose)), strlen($toReplace));
					
					$isTagStart += strlen($tagOpen);
					$lengthString = $isTagEnd - $isTagStart;
					$minisubstr = substr($toReplace, $isTagStart, $lengthString);

					//проверяем теги SHOW
					$isTag2Start = strpos($minisubstr, $tagShowOpen);
					if ($isTag2Start) {
						
						$isTag2End = strpos($minisubstr, $tagShowClose);
						if ($isTag2End) {
							
							if ($isTag2Start < $isTag2End) {
								//убираем лишние метки SHOW
								$substr1_1 = substr($minisubstr, 0, $isTag2Start);
								//$substr2_1 = substr($minisubstr, ($isTag2End+strlen($tagShowClose)), strlen($minisubstr));

								$isTag2Start += strlen($tagShowOpen);
								$length2String = $isTag2End - $isTag2Start;
								
								//установки типа d.m.Y
								$minisubstr2 = trim(substr($minisubstr, $isTag2Start, $length2String));
								//содержимое - time
								$minisubstr3 = trim(substr($minisubstr, $isTag2Start+$length2String+strlen($tagShowClose),
										$lengthString - $length2String - strlen($tagShowOpen) - strlen($tagShowClose)));
								
								if ($minisubstr2 == "wday") {
									$getdate = getdate($minisubstr3);
									$wday = $getdate['wday'];
									if ($wday == 1) $minisubstr3 = "понедельник";
									if ($wday == 2) $minisubstr3 = "вторник";
									if ($wday == 3) $minisubstr3 = "среда";
									if ($wday == 4) $minisubstr3 = "четверг";
									if ($wday == 5) $minisubstr3 = "пятница";
									if ($wday == 6) $minisubstr3 = "суббота";
									if ($wday == 0) $minisubstr3 = "воскресенье";
								}
								else {
									//тут замена - параметры из SHOW
									$minisubstr3 = date($minisubstr2, $minisubstr3);
								}
								$minisubstr = $minisubstr3;

							}
						}
					}

					$toReplace = $substr1 . $minisubstr . $substr2;
				}
			}
		}
		return $toReplace;
	}

	
	
	
	//выводим содержимое с помощью echo
	public function prints ($keyparse) {
		if(! $this->arrayparse[$keyparse]){
			$this->error->add(2, "Пустой шаблон", get_class($this));
			return false;
		}
		else {
			echo $this->arrayparse[$keyparse];
			return true;
		}
	}
	
	//возвращает содержимое части массива по ключу
	public function getParse ($keyparse) {
		return $this->arrayparse[$keyparse];
	}
	
	//экспресс-сборка для подключения файлов по образцу установленного файлового дерева
	//file - название файла маленькими буквами (расширение html)
	//type - место, где находится файл. Сейчас только 2 варианта
	// 1. в папке html
	// 2. в папке t_...x - это уже content
	
	//Для html_dynamic: array - массив с данными, взятыми из выборки базы данных методом select
	//parse - для parseDynamic. Если parse == PARSE, то включается дополнительный парсинг статичных значений
	//TrueFileName - истинное имя файла, необходимо для подмены зеркального имени file (file - переменная вставки, но файл может быть иным)
    
    //for_js=false - переводить ли содержимое файла в одну строку (используется для сохранения файла в переменную javascript)
	public function includeConfigTemlpate($file, $type, $array = array(), $parse = false, $TrueFileName = "", $add_iterator = false, $suffix_example = "_SUB", $for_js = false) {
		$fileUp = strtoupper($file);
		$fileDo = strtolower($file);
		
		if ($TrueFileName == "") {}
		else {
			$fileDo = strtolower($TrueFileName);
		}

		if (
			$type == "module"
			|| $type == "module_"
			|| $type == "tpl"
			|| $type == "content"
			|| $type == "html"
			|| $type == "style"
			|| $type == "templdirectory"
		) {
			$this->setVars($array, "a");
			if ($type == "module") {
				$this->setPath("html/modules/{$fileDo}.html");
			}
			//для обратной совместимости - новые "модули" будут вставляться отсюда
			//общая структура для всех модулей
			elseif ($type == "module_") {
				if ($TrueFileName == "") $this->setPath("modules/{$fileDo}/index.html");
				else $this->setPath("modules/{$fileDo}.html");
			}
			elseif ($type == "tpl") {
				$this->setPath("tpl/{$fileDo}.html");
			}
			elseif ($type == "content") {
				$this->setPath("html/t_{$this->global['page']}/{$fileDo}.html");
			}
			elseif ($type == "html") {
				$this->setPath("html/{$fileDo}.html");
			}
			elseif ($type == "style") {
				$this->setPath("style/{$fileDo}.css");
			}
			elseif ($type == "templdirectory") {
				$this->setPath("{$fileDo}.html");
			}
			
			if ($for_js == true) {
                $this->linesInOne();
            }
			if ($parse == false) {
				$this->parse($fileUp);
			}
			//двойной прогон по массивам - используется, если переменные из базы не успевают обернуться,
			// например - BASE_UPLOAD
			else {
				$parse_content = $this->parse($fileUp);
				$this->parse($fileUp, $parse_content);
			}
		}
		elseif ($type == "tpl_dynamic") {
			$this->setPath("tpl/{$fileDo}.html");
			$this->parseDynamic($fileUp, $array, $add_iterator, $suffix_example);
			if ($parse == PARSE) {
				$this->parse($fileUp);
			}
		}
		elseif ($type == "module__dynamic") {
			//fileDo - здесь название файла и название модуля одновременно через слэш: модуль/файл
			$this->setPath("modules/{$fileDo}.html");
			$this->parseDynamic($fileUp, $array, $add_iterator, $suffix_example);
			if ($parse == PARSE) {
				$this->parse($fileUp);
			}
		}
		elseif ($type == "html_dynamic") {
			$this->setPath("html/{$fileDo}.html");
			$this->parseDynamic($fileUp, $array, $add_iterator, $suffix_example);
			if ($parse == PARSE) {
				$this->parse($fileUp);
			}
		}
		elseif ($type == "content_dynamic") {
			$this->setPath("html/t_{$this->global['page']}/{$fileDo}.html");
			$this->parseDynamic($fileUp, $array, $add_iterator, $suffix_example);
			if ($parse == PARSE) {
				$this->parse($fileUp);
			}
		}
		elseif ($type == "templdirectory_dynamic") {
			$this->setPath("t_{$this->global['page']}/{$fileDo}.html");
			$this->parseDynamic($fileUp, $array, $add_iterator, $suffix_example);
			if ($parse == PARSE) {
				$this->parse($fileUp);
			}
		}
		else {
			$this->error->add(2, "includeConfigTemlpate: тип шаблона не определен", get_class($this));
			return false;
		}
		
		//добавляем в следующий парс. Если будет такое же имя - оба содержимых объединятся
		$this->setNext (array (
			$fileUp	=> "",
		));
	}
	
	//для ajax,
	public function setPage ($page) {
		if ($page) {
			$this->global['page'] = $page;
		}
	}
	
	//для авто-создания страниц через админку - аналогична setPage, но смысл понятия другой
	//Используется в content_management
	public function setPageType($pageType){
		if ($pageType) {
			$this->global['page'] = $pageType;
		}
	}
}
?>