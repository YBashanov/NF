<?php if ( ! defined('andromed')) exit('');

//модуль, выполняющий подключение к базе данных и последующей работе с ней.
//используется: класс Error
//подключен массив с неустановленными таблицами
//12.08.2012 - mysqli и mysql вызываются одинаково

$db_tables = array();
$libraries = array();

include "{$separator}modules/db/config.php";
include "{$separator}modules/db/Database.php";
include "{$separator}modules/db/Database_i.php";
include "{$separator}modules/db/Error.php";
include "{$separator}modules/db/NewTables.php";
$error = new Error($config['prefix']);

if ($config['syntax'] == "mysqli") {
	$db = new Database_i(
		$config['host'], 
		$config['user'], 
		$config['pass'], 
		$config['db'], 
		$config['character_set'], 
		$db_tables, 
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
		$db_tables, 
		$error
	);
}
?>