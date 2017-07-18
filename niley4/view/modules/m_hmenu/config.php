<?php if ( ! defined('andromed')) exit(''); 

/*
Горизонтальное меню

1. в _content_management - прописать
include "{$separator}templates/{$global['template']}/modules/m_hmenu/config.php";

2. в html прописать:
{M_HMENU}

3. добавить данных (двумерный вложенный массив) $merger['array']
*/
/*
//массив данных для примера
$merger['array'] = array(
	1=>array(
		"name"=>"Металлические двери",
		"id"=>"metal/",
		"sub"=>array(
			1=>array(
				"name"=>"Металлические двери (Россия)",
				"id"=>"metal2/",
			),
			2=>array(
				"name"=>"Металлические двери (Россия)",
				"id"=>"metal2/",
			),
			3=>array(
				"name"=>"Металлические двери (Россия)",
				"id"=>"metal2/",
			)
		)
	),
	2=>array(
		"name"=>"Металлические двери",
		"id"=>"metal/",
		"sub"=>array()
	),
);
*/

module_(INDEX_DYNAMIC, array(), "m_hmenu/index_dynamic");
module_(INDEX_DYNAMIC_SUB, array(), "m_hmenu/index_dynamic_sub");
module__dynamic(INDEX_DYNAMIC, "m_hmenu/index_dynamic", $merger['array']);
module_(M_HMENU);
?>
