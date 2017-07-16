<?php if ( ! defined('andromed')) exit('');

//1.0 - 2013-12-13

class L_socket{
	private $view_error = true;
	private static $thisObject = null;
	private static $error;
	public static function init($error){
		if ( self::$thisObject == null ){
			self::$thisObject = new L_socket();
			self::$error = $error;
			return self::$thisObject;
		}
		else {
			echo "L_socket: Объект L_socket уже создан ранее!";
			self::$error->add(2, "Ошибка. Один объект уже существует", 'L_socket');
			exit();
		}
	}

	//возвращает контент страницы 
	public function getContent ($tree, $code) {
		//global $separator;
		//include "{$separator}config/config_udk.php";

		//$url = "http://legacy.uspu.ru/udc/tree/show.html?tree={$get['tree']}&code={$get['code']}";
		
		
		$url = "http://legacy.uspu.ru/udc/tree/show.html?tree={$tree}&code={$code}";
		//$url = "http://directscan/examples/controller.php?tree={$tree}&code={$code}";
		
		//формирование
		//Сookie - я взял из плагина, возвращающего заголовки. Кроме - PHPSESSID
		$options = array("http"=>array(
			"method"=>"GET",
			//"header"=>$header_udk,
			"content"=>""
		));
		$context = stream_context_create($options);
		$content = @file_get_contents($url, false, $context);
		
		if ($content) { 
			return $content;
		}
		else {
			self::$error->add(2, "Ошибка! Не открылся файл {$url}", 'L_socket');
			return false;
		}
	}
	
	
	//посылает запрос POST и ожидает отклика стороннего сервера (возвращает контент, если отклик есть)
	//get и post должны быть массивами
	public function sendPOST ($url, $a_get = "", $a_post = "") {
		//формирование GET
		if (is_array ($a_get)) {
			$get = '';
			foreach ( $a_get as $key=>$val ){
				$get .= "{$key}={$val}&";
			}
		}
		
		if (is_array ($a_post)){
			//формирование POST
			$payment_parameters = http_build_query($a_post);
			$options = array("http"=>array(
				"method"=>"POST",
				"header"=>"Content-type: application/x-www-form-urlencoded",
				"content"=>$payment_parameters
			));
			$context = stream_context_create($options);
		}

		return file_get_contents("{$url}?{$get}", false, $context);
	}
}
?>