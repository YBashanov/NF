<?php if ( ! defined('andromed')) exit(''); 
$db_adverse_table = array(
	
//mailing

	"{$config['prefix']}mailing"=>"CREATE TABLE `{$config['prefix']}mailing` (
		`id`			int(11) NOT NULL auto_increment,
		`deleted`		int(1) default 0,
		`mail`	varchar(255) default '',
		`time_create`	int(11) default 0,
		`time_update`	int(11) default 0,
		`user_create` 	int(11) default 0,
		`user_update` 	int(11) default 0,
		PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8"
);
?>