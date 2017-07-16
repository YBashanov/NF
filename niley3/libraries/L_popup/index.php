<?php if ( ! defined('andromed')) exit('');
/*
всплывающие окна
расширена 16.04.12
*/
class L_popup{
	
	private $path_jquery = '../js/jquery-1.3.2.js';
	private $view_error = true;//разрешение/запрещение генерации ошибок-отчетов классом (true/false)
	private static $thisObject = null;
	private static $error;
	public static function init($error){
		if ( self::$thisObject == null ){
			self::$thisObject = new L_popup();
			self::$error = $error;
			return self::$thisObject;
		}
		else {
			echo "L_popup: Объект L_popup уже создан ранее!";
			self::$error->add(2, "Ошибка. Один объект уже существует", 'L_popup');
			exit();
		}
	}

	
//старый способ - открытие нового окна (редко применяется) - при клике
	//желательно указывать в url(link) параметр &popup_close=контроллер/метод
	//..для того, чтобы в случае отказа сессий, opener корректно перезагрузилось
	public function window_open($link, $width=300, $height=200){
		return "onClick=\"window.open('{$link}','','width={$width},height={$height},scrollbars=yes,toolbar=no,menubar=no')\"";
	}
	
//старый способ - открытие окна при появлении данного сценария в окне браузера
	public function windowOpenLoad($link, $width=300, $height=200, $top=0, $left=0){
		$echo = "<script>
			window.open('{$link}','','width={$width},height={$height},top={$top},left={$left},scrollbars=yes,toolbar=no,menubar=no');
		</script>";
		return $echo;
	}
	
	
	
	
//всплывающий div - самый простой способ. Необходимо настройть путь до jquery (стр 8)
	//id 		- индикатор div
	//x 		- расположение окна по оси х
	//y 		- расположение окна оп оси y
	//width		- ширина
	//height	- высота
	//background- цвет фона
	//border 	- свойство border
	
	//Как задействовать:
	//$popup->popup_open()
	//onclick='javascript:popup_open(\"id\")
	private $call_popup_open = false; //была ли уже вызвана эта функция
	private $zIndex = 100;	//для смены слоя при новом клике
	public function popup_open($id, $display='none', $x=100, $y=100, $width=240, $height=240, $background='', $border='', $path_jquery=''){
		
		//определяем путь до jquery
		$path_jquery = $path_jquery.$this->path_jquery;
			
		//проверка пути до jquery
		if (file_exists($path_jquery)){
			$echo = "<style>#{$id} {
				width:{$width}px;height:{$height}px;
				position:absolute;
				left:{$x}px;top:{$y}px;z-index:10000;
				display:{$display};
				{$background};
				{$border};}</style>";

			//предотвращение очередного определения замыкания функции javascript
			if ( $this->call_popup_open == false ) {
				$echo .= "<script type='text/javascript' src='{$this->path_jquery}'></script>";
				$echo .= "<script type='text/javascript'>
					var blocked = new Array;
					var zIndex = {$this->zIndex};
				</script>";
			}
			if ( $display == 'none' ) $blocked_id = 'false';
			else $blocked_id = 'true';
			$echo .= "<script type='text/javascript'>
					blocked['{$id}'] = {$blocked_id};//показать и скрыть конкретное окно
					//чтобы клик цеплялся, обертку надо делать ниже div'a
					$('#".$id."').click(function(){
						zIndex++;
						$('#".$id."').css('z-index',zIndex);//конкретное окно на передний план
					});
				</script>";
			if ( $this->call_popup_open == false ) {
				$echo .= "<script type='text/javascript'>
					function popup_open(id) {
						if ( blocked[''+id+''] == false ) {
							zIndex++;
							$('#'+id+'').css('z-index',zIndex);
							$('#'+id+'').css('display','block');
							blocked[''+id+''] = true;
						}
						else if ( blocked[''+id+''] == true ) {
							$('#'+id+'').css('display','none');
							blocked[''+id+''] = false;
						}
					}
					</script>";
				$this->call_popup_open = true;
			}
			
			return $echo;
		}
		else{
			if ( $this->view_error ) {
				self::$error->add(2, 'popup_open: Не верный путь до jquery!', 'l_popup');
				die('Неверный путь до jquery');
			}
			return false;
		}
	}
	
	
	
//всплывающий div - вторая версия. от 17.02.2012 -
//в отличие от предыдущего, работает через ajax
	//id 		- индикатор div
	//x 		- расположение окна по оси х
	//y 		- расположение окна оп оси y
	//width		- ширина
	//height	- высота
	//isAjax	- передавался ли сюда запрос (формируется с помощью местного javascript)
	//style		- стиль для блока

	//Как задействовать:
	//$popup->popupOpen_ajax()
	//onclick='javascript:popupOpen_ajax(\"id\")
	public function popupOpen_ajax($id, $display='none', $x=100, $y=100, $width=240, $height=240, $isAjax=0, $style='', $path_jquery=''){
		
		//определяем путь до jquery
		$path_jquery = $path_jquery.$this->path_jquery;
			
		//проверка пути до jquery
		if (file_exists($path_jquery)){
			$echo = "<style>#{$id} {
				width:{$width}px;height:{$height}px;
				position:absolute;
				left:{$x}px;top:{$y}px;z-index:10000;
				display:{$display};
				{$style}}</style>";
			//предотвращение очередного определения замыкания функции javascript
			if ( $isAjax == 0 ) {
				//$echo .= "<script type='text/javascript' src='{$this->path_jquery}'></script>";
				$echo .= "<script type='text/javascript'>
					var blocked = new Array();
					var zIndex = 100;
				</script>";
			}
			if ( $display == 'none' ) $blocked_id = 'false';
			else $blocked_id = 'true';
			$echo .= "<script type='text/javascript'>
					blocked['{$id}'] = {$blocked_id};//показать и скрыть конкретное окно
					//чтобы клик цеплялся, обертку надо делать ниже div'a
					zIndex++;
					$('#{$id}').css('z-index',zIndex);//конкретное окно на передний план
				</script>";
			if ( $isAjax == 0 ) {
				$echo .= "<script type='text/javascript'>
					function popupOpen_ajax (id) {
						if ( blocked[''+id+''] == false ) {
							zIndex++;
							$('#'+id+'').css('z-index',zIndex);
							$('#'+id+'').css('display','block');
							blocked[''+id+''] = true;
						}
						else if ( blocked[''+id+''] == true ) {
							$('#'+id+'').css('display','none');
							blocked[''+id+''] = false;
						}
					}
					function popupFocus_ajax (id) {
						if ( blocked[''+id+''] == true ) {
							zIndex++;
							$('#'+id+'').css('z-index',zIndex);
						}
					}
					</script>";
			}
			
			return $echo;
		}
		else{
			if ( $this->view_error ) {
				self::$error->add(2, 'popup_open: Не верный путь до jquery!', 'l_popup');
				die('Неверный путь до jquery');
			}
			return false;
		}
	}
	
	
//всплывающий div - третья версия. от 16.05.2012 - остались только стили.
//в отличие от предыдущего
//- работает без jquery
//- вызывается по умолчанию открытым (в вызове закрытого окна нет необходимости)
//в нем отпала надобность (17.05.12) т.к. стили abslolute для IE вообще выгодно прописывать в самом теге.
	//id 		- индикатор div
	//x 		- расположение окна по оси х
	//y 		- расположение окна оп оси y
	//style		- стиль для блока

	//Как задействовать:
	//необходимо наличие пользовательской библиотеки-javascript - Popup.js
	//1. $popup->popupOpen_style("id", 10, 10)
	//2. onclick='Popup.openClose("id")
	//3. из вызываемого скрипта вызвать функцию Popup.open (id, true), иначе блоку не присвоится текущий zIndex. 
	// (НО - не из возвращаемого текста. Из текста script не вызовется)
	// поэтому из php_ajax возвращаем строку, разделенную |
	//в div-е можно реализовать две функции - Popup.openClose, Popup.focus
	public function popupOpen_style($id, $x=100, $y=100, $style='', $display = "block"){
		$echo = "<style>#{$id}{
			position:absolute;
			left:{$x}px;top:{$y}px;z-index:100000;
			display:{$display};
			{$style}}
		</style>";
		return $echo;
	}
	
	
	//закрытие окна с перезагрузкой того окна, из которого было открыто popup
	//$path_main_ajax - путь до ajax-папки (относительно которой открывающий файл)
	//$line - адрес после index.php, контроллера, из которого открыт файл
	//$reload - с перезагрузкой предыдущего окна (opener'a)
	public function popup_close($path_main_ajax, $line, $reload='1'){
		
		//стили ко всему файлу окна
		$button_closeWindow = "<style>
			body{font-family:Palatino Linotype;}
			.button_closeWindow{
				float:right;
				text-align:right;
			}</style>
			
			<script>
			close_window = function(){";
				if ( $reload == '1' ) { $button_closeWindow .= "window.opener.location = '{$path_main_ajax}index.php/{$line}';"; }
				$button_closeWindow .= "window.close();
			}</script>
			
			<div><div class='button_closeWindow'>
				<a href='#' onclick='close_window()'>Закрыть окно</a>
			</div></div>";
		return $button_closeWindow;
	}
}
?>