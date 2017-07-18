<?php if ( ! defined('andromed')) exit('');

/*
Старый класс, созданный 5.12.11
Преобразован в класс-библиотеку 7.09.12 для общих нужд
2013-06-23 - fileDelete()
*/

class L_content{

	private $view_error = true;//разрешение/запрещение генерации ошибок-отчетов классом (true/false)
	private static $thisObject = null;
	private static $error;
	public $base_url = "";
	public $separator = "";
	public $global_template = "";
	public $page = "";
	public $is_language = false;
	public $language = "";
	private $menuArray = array();
	private $menuArrayRotate = array(); //нормально отформатированный массив
	
	public static function init($error){
		if ( self::$thisObject == null ){
			self::$thisObject = new L_content();
			self::$error = $error;
			return self::$thisObject;
		}
		else {
			echo "L_content: Объект L_content уже создан ранее!";
			self::$error->add(2, "Ошибка. Один объект уже существует", 'L_content');
			exit();
		}
	}
	
	/*используем меню*/
	//submenu - контент, возвращаемый отдельной функцией submenu
	//если href == false, то ссылка отключена
	//javascript - Вешает на блок вызов javascript
	private $firstStart = false;
	private $menuIterator = 0;
	public function menu ($name, $href, $final_div = false, $submenu = "", $javascript = false){
		$return = "";

		if ($this->firstStart == false) {
			if ($this->view_error) {
				if (! file_exists("{$this->separator}templates/{$this->global_template}/style/menu/structure.css")){
					echo "L_content: Нет подключаемого файла стилей меню {$this->separator}templates/{$this->global_template}/style/menu/structure.css";
					self::$error->add(2, "Нет подключаемого файла стилей меню style/menu/structure.css", 'L_content');
				}
			}
			$return .= "<link href='{$this->base_url}templates/{$this->global_template}/style/menu/structure.css' rel='stylesheet' type='text/css' />";
			$return .= "<div class='menu'>";
		}
		
		if ($href !== false){
			$return .= "<a href='{$this->base_url}{$href}";
			if ($href != "/") $return .= "/";
			if ($this->is_language == true) $return .= "{$this->language}/";
			$return .= "'>";
		}
		$return .= "<div class='blocks block{$this->menuIterator}";
		if ($href == $this->page && $href !== false) $return .= " active";
		$return .= "'";
		if ($javascript !== false) $return .= " {$javascript}";
		$return .= "><div id='buttonmenu{$this->menuIterator}' class='texts'><div class='name'>{$name}</div></div>{$submenu}</div>";
		if ($href !== false){
			$return .= "</a>";
		}
		
		if ($final_div == true) {
			$return .= "<div class='cle'></div></div>";
		}
		
		$this->firstStart = true;
		if ($href !== false) {
			$this->createArray($href, $name, "menu");
		}
		$this->menuIterator++;
		return $return;
	}
	public function getMenuIterator(){
		return $this->menuIterator;
	}
	
	//создание массива для дальнейшей обработки (наполнение массива данными)
	private function createArray ($key, $val, $level) {
		$this->menuArray[$key] = array(
			"name" => $val,
			"level"=> $level
		);
	}
	//отдает наполненный массив
	public function getMenuArray(){
		return $this->menuArray;
	}
	
	//2014-02-10
	//меняет местами впереди идущие sub с теми уровнями 1го уровня, которым они принадлежат
	// т.е. выстраивается "нормальная" структура меню
	private function rotateArray() {
		$array = $this->menuArray;
		$a_new = array();
		$a_sub = array();//отдел для накапливания подменю
		foreach($array as $key=>$val) {
			
			if ($val['level'] == "sub") {
				$a_sub[$key] = $val;
			}
			elseif ($val['level'] == "menu") {
				$a_new[$key] = $val;
				$a_new[$key]['sub'] = $a_sub;
				$a_sub = array();
			}
		}
		$this->menuArrayRotate = $a_new;
	}
	//отдает наполненный массив
	public function getMenuRotArray(){
		if (empty ($this->menuArrayRotate) ) {
			$this->rotateArray();
			return $this->menuArrayRotate;
		}
		else {
			return $this->menuArrayRotate;
		}
	}
	
	//submenu ---
	//после очередного присвоения к основному блоку меню, необходимо обнулять триггер - setSubStart
	private $firstSubStart = false;
	private $submenuIterator = 0;
	public function submenu ($name, $href, $final_div = false){
		$return = "";
		
		if ($this->firstSubStart == false) {
			$return .= "<div class='submenu'>";
		}
		
		if ($href !== false){
			$return .= "<a href='{$this->base_url}{$href}";
			if ($href != "") $return .= "/";
			if ($this->is_language == true) $return .= "{$this->language}/";
			$return .= "'>";
		}
		$return .= "<div class='blocks";
		if ($href == $this->page) $return .= " active";
		$return .= "'><div id='buttonsubmenu{$this->submenuIterator}' class='texts'><div>{$name}</div></div></div>";
		if ($href !== false){
			$return .= "</a>";
		}
		
		if ($final_div == true) {
			$return .= "<div class='cle'></div></div>";
		}
		$this->firstSubStart = true;
		$this->createArray($href, $name, "sub");
		$this->submenuIterator++;
		return $return;
	}
	public function getSubmenuIterator(){
		return $this->submenuIterator;
	}
	
	public function setSubStart($bool = false){
		$this->firstSubStart = $bool;
	}
	
	//2014-02-10
	//собираем scrubs - панель, состоящая из какого-нибудь одного раздела подменю
	//page - та страница, которая сейчас открыта
	//
	public function scrubs($a_menu, $page, $uri = "") {
		if ($a_menu[$page]['level'] == "sub") {

			$a_sub = array();//собираем сюда
			$is_href = false;
			foreach ($a_menu as $k_href=>$val){
				if ($val['level'] == "sub") {
					$a_sub[$k_href] = $val;

					if ($k_href == $page) {
						$is_href = true;
					}
				}
				else {
					if ($is_href) break;
					else $a_sub = array();
				}
			}

			if ($is_href) {
				$in .= "<div class='scrubs'>";
				
				foreach ($a_sub as $k_href=>$val) {
					$in .= "<div class='element";
					if ($k_href == $page) {
						$in .= " active";
					}
					$in .= "'><a href='{$this->base_url}{$k_href}/{$uri}'>{$val['name']}</a></div>";
				}
				$in .= "<div class='cle'></div>";
				$in .= "</div>";
			}
			else $in = "";
		}
		else $in = "";
		
		return $in;
	}
	
	
	
	//2014-04-08
	private $oper_system = false;
	private $browser = false;
	public function setServer(){
		$server = $_SERVER['HTTP_USER_AGENT'];
		$oper_system = false;
		$browser = false;

		//операционная система
		
		//iPhone
		$s_server = strpos($server, "iPhone");
		if ($s_server) {
			$oper_system = "iPhone";
		}

		//браузер
		
		//Chrome
		$s_server = strpos($server, "Chrome");
		if ($s_server) {
			$browser = "Chrome";
		}
		
		
		$this->oper_system = $oper_system;
		$this->browser = $browser;
	}
	public function getSystem(){
		return $this->oper_system;
	}
	public function getBrowser(){
		return $this->browser;
	}
}
?>
