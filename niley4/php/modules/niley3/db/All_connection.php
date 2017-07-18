<?php if ( ! defined('andromed')) exit('');

//модуль, выполняющий подключение к базе данных и последующей работе с ней.
//используется: класс Error
//подключен массив с неустановленными таблицами
//12.08.2012 - mysqli и mysql вызываются одинаково

$db_tables = array();
$libraries = array();

include "{$separator}config/db/config.php";
include "{$separator}php/modules/{$global['style_modules']}/db/Database.php";
include "{$separator}php/modules/{$global['style_modules']}/db/Database_i.php";
include "{$separator}config/db/NewTables.php";

if ($config['syntax'] == "mysqli") {
	$db = new Database_i(
		$config['host'], 
		$config['user'], 
		$config['pass'], 
		$config['db'], 
		$config['character_set'],
		$error
	);
}
else {
	$db = new Database(
		$config['host'], 
		$config['user'], 
		$config['pass'], 
		$config['db'], 
		$config['character_set'],
		$error
	);
}

if ($global['dbCreateTables']){
    $db->createTables($db_tables);
}
