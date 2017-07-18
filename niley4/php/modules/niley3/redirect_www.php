<?php
//$host = $_ENV['SERVER_NAME'];
$host = $_SERVER['SERVER_NAME'];

//$uri = $_ENV['REQUEST_URI'];
$uri = $_SERVER['REQUEST_URI'];



$act_host = 'http://'.$host;
$new_host = 'http://www.'.$host;
$index = $act_host.$uri;

//Перенаправляем
if ( strpos($act_host, 'www') ) 
{
	//все ОК
}
else
{
	$header = $new_host.$uri;
	header("Location:$header");
}
?>