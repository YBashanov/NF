<?php if ( ! defined('andromed')) exit(''); 

//основополагающий массив!!! все разделы идут от него
$a_menu = array(
	0=>array(
		"sub"=>array(
			"anons"=>"Анонс главных событий",
			"obyavleniya"=>"Объявления",
			"lenta_novostey"=>"Лента новостей",
			"bannery"=>"Баннеры"
		),
		""=>"Главная",
	),
	1=>array(
		"sub"=>array(
			"otraslevie_sobitiya"=>"Отраслевые события",
			"gosorgani"=>"Госорганы, общественные организации",
			"kadri"=>"Кадры",
			"documenti"=>"Документы",
			"projekti"=>"Проекты"
		),
		"zhizhn_otrasli"=>"Жизнь отрасли",
	),
	2=>array(
		"sub"=>array(
			"kamni"=>"Камни",
			"proizvodstvennie_texnologii"=>"Производственные технологии",
			"torgovie_texnologii"=>"Торговые технологии",
			"dizain"=>"Дизайн",
			"literatura"=>"Литература",
		),
		"yuvelirnoe_obozrenie"=>"Ювелирное обозрение",
	),
	3=>array(
		"sub"=>array(
			"diamonds_web_version"=>"Web-версия журнала",
			"proizvodstvo"=>"Производство",
			"torgovlya"=>"Торговля",
			"regulyatsiya_rinka"=>"Регуляция рынка",
		),
		"russian_diamonds"=>"Журнал <br>Russian Diamonds",
	),
	4=>array(
		"sub"=>array(
			"diadema_web_version"=>"Web-версия журнала",
		),
		"diadema"=>"Журнал <br>Diadema",
	),	
	5=>array(
		"sub"=>array(
			"konkurs_russkaya_liniya_2013"=>"Конкурс «Русская линия-2013»",
			"obzor_rossiyskix_konkursov"=>"Обзор российских конкурсов",
			"obzor_zarubezhnix_konkursov"=>"Обзор зарубежных конкурсов",
		),
		"russkaya_liniya"=>"Конкурс <br>Русская линия",
	),
	6=>array(
		"sub"=>array(
			"zolotoe_koltso_rossii"=>"Фестиваль «Золотое кольцо России»",
			"lutshie_brendi_rossii"=>"Выставки «Лучшие бренды России»",
			"kalendar_vistavok_rossii"=>"Календарь выставок. Россия, СНГ",
			"kalendar_vistavok_zarubezhnix"=>"Календарь зарубежных выставок",
		),
		"festivali_vistavki"=>"Фестиваль, выставки",
	),
	7=>array(
		"sub"=>array(
			"novinki"=>"Новинки, акции, события, ссылки",
			"almaz_holding"=>"Алмаз-Холдинг",
			"hika"=>"Ника",
			"almaz"=>"Алмаз",
		),
		"partneri"=>"Партнеры",
	),
	8=>array(
		"sub"=>array(
			"predpriyatiya_pomezcheniya"=>"Предприятия, помещения, оборудование",
			"magaziny_torgovie_plozchadki"=>"Мазагины, торговые площадки, оборудование, кадры",
			"kamni_torg_plozhadka"=>"Камни",
		),
		"torgovaya_plozchadka"=>"Торговая площадка",
	),
	9=>array(
		"kontakti"=>"Контакты",
	),
);

//преобразовать массив в одномерный
$a_new = $array->MixedToOne($a_menu, "->> ");
$a_merge = array("search"=>"->> Поиск");

$a_new = array_merge($a_new, $a_merge);
?>