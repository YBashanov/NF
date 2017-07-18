<?php if ( ! defined('andromed')) exit(''); 

//необходимо, чтобы данные параметры предшествовали формированию personalmenu, т.к.
// тут выполняются дополнительные запросы для отображения количества штук (заказов, отзывов, и т.д)


$in = "";
if ($page == "personal") {
	//пользователь
	if ($global['user']['status'] == 1) {
		//$in .= menuPersonal($get['p'], "Залы", "zaly", "&inPage=20&");

		$in .= menuPersonal($get['p'], "Меню", "menu");
		$in .= "<br>";
		$in .= menuPersonal($get['p'], "На сайт", false);
	}
	
	//модератор
	if ($global['user']['status'] == 3) {
		//$in .= menuPersonal($get['p'], "Залы", "zaly", "&inPage=20&");

		$in .= menuPersonal($get['p'], "Меню", "menu");
		$in .= "<br>";
		$in .= menuPersonal($get['p'], "На сайт", false);
	}
	
	//администратор
	if ($global['user']['status'] == 5) {
		//$in .= menuPersonal($get['p'], "Залы", "zaly", "&inPage=20&");

		$in .= menuPersonal($get['p'], "Меню", "menu");
		$in .= "<br>";
		$in .= menuPersonal($get['p'], "На сайт", false);
	}
	
	//супер-администратор
	if ($global['user']['status'] == 9) {
		$in .= menuPersonal($get['p'], "Меню", "menu");
		$in .= "<br>";
		$in .= menuPersonal($get['p'], "Пользователи", "users");
		$in .= menuPersonal($get['p'], "Редиректы и Title", "seo_lynks");
		$in .= "<br>";
		$in .= menuPersonal($get['p'], "На сайт", false);
	}
}

$data['menuPersonal'] = $in;
?>