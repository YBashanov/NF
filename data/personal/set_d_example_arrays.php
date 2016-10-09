<?php if ( ! defined('andromed')) exit('');

//include "{$separator}data/personal/d_example_arrays_set.php";

$arrays['orders_year'] = array();
$arrays['orders_year_list'] = array();
$key=0;
for ($i=2008; $i<=$getdate['year']; $i++) {
	$arrays['orders_year'][$i] = $i;
	$arrays['orders_year_list'][$key]['orders_year'] = $i;
	$arrays['orders_year_list'][$key]['href'] = $i;
	$key++;
}


$arrays['orders_status'] = array(
	"-не выбрано-",
	"Отказано на основании Устава",
	"Заявка одобрена, ожидается собеседование",
	"Принят в КАД",
	"Заявка получена, ожидает рассмотрения",
	"Зарезервировано 	по просьбе вступающего",
	"Заявка аннулирована"
);
$arrays['orders_status_color'] = array(
	"#000",
	"#f44",
	"#c90",
	"#0a0",
	"#00a",
	"#fff",
	"#f00"
);


?>