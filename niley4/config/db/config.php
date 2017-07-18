<?php if ( ! defined('andromed')) exit('');

if ($global['server'] == "local") {
	$config['host'] = 'localhost';
	$config['user'] = 'root';
	$config['pass'] = '0000';
	$config['db'] = 'we-not';
	$config['prefix'] = '';
	$config['syntax'] = 'mysqli';
}
else {
/*
	$config['host'] = 'localhost';
	$config['user'] = '045334246_koll';
	$config['pass'] = '448asfzjbnw';
	$config['db'] = 'bjorksskitchen_kollege33';
	$config['prefix'] = 'prefix_';
	$config['syntax'] = 'mysql';
	*/
}

//если не установить кодировку вручную, база данных будет вести себя неуверенно!
$config['character_set'] = 'utf8';
//$config['character_set'] = 'cp1251';

$prefix = $config['prefix'];
?>