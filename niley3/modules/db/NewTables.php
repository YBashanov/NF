<?php if ( ! defined('andromed')) exit(''); 
$db_tables = array(
	
//errors - ���� ������������ ������
//����������� ������� ������

	"{$config['prefix']}errors"=>"CREATE TABLE `{$config['prefix']}errors` (
		`id`				int(11) NOT NULL auto_increment,
		`internalCode` 		int(2) default 0,
		`code`				int(11) default 0,
		`time`				char(22) default 0,
		`message`			text,
		`classWhichCalled`	char(50) default 0,
		`ip`				char(21) default 0,
		`browser`			varchar(99) default 0,
		`counter_number` 	int(11) default 0,
		PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8",
		
	

/*users_online
		id - �� �������������, �.� �� ����� �������������� ������ ���� ����� (� ����������� �� �����)
		sid - ������������� ������
		secret_number - ������������ ��������� �� ������ ����� ������, �� � ����
			"������������" ��������� ��� ��� ������������� � ��������� ������
		user_info - �������
		time_create - ����� ������ �������� ������ ������. ��������� � time_update, ���� ��� - �� ����������
		time_update - ������ �����, ����� ������������ ������ �������� �����
		time_online - ���������� ���������� ����� ��������� ������� � ���, ��� ������������ � �������
*/
	"{$config['prefix']}users_online"=>"CREATE TABLE `{$config['prefix']}users_online` (
		`id`		int(11) NOT NULL,
		`deleted` 	int(1) default 0,
		
		`sid`			varchar(50) default '',
		`secret_number` char(6) default '',
		`user_info`		varchar(50) default '',
		`time_create`	int(11) default 0,
		`time_update`	int(11) default 0,
		`time_online`	int(11) default 0,
		PRIMARY KEY (id))ENGINE=InnoDB DEFAULT CHARSET=utf8",
		
/*
users
	��� ����������� ���������� ������ � users_online
		status 
			0 - ������ �����������������, �� ���������� ���� �������. ������ - �� �������� ����, �������� ������
			1 - ���������� �������, ������� ������������. ��������� ������������ ����������. 
			3 - ��������� (������� ���������)
			5 - ������������� 
		hash - ��������� 20������� ����� (������), ������� ������������ 1 ���
			������������ � ���������.
		login - ����������!
		pass - ������
			������������ � ���������.
	������ ����������:
		name
		surname
		address
		phone - ����������� � �����
		mail
		foto	- ���� ����, ��� ���� ��������
	�������������� ����������:
		time_create
		time_update - ��� �����������
		time_last - ���������������� �� time_update
		user_id_update - � ���� ������� ���� ���������� ����������
		ip_create
		ip_update - ������������ ��� �����������
		ip_last - ���������������� �� now_ip
		u_online_id	- id �� users_online, ��� ������� ��������������� ������ ������������
		remark 		����������� � ������������
	
	������������� ���������
		language - ���� ����������
*/
	"{$config['prefix']}users"=>"CREATE TABLE `{$config['prefix']}users` (
		`id`		int(11) NOT NULL auto_increment,
		`deleted` 	int(1) default 0,
		`status`	int(1) default 0,
		
		`hash`		char(20) default '',
		`login`		varchar(50) default '',
		`pass`		char(32) default '',
		`name`		varchar(50) default '',
		`surname`	varchar(50) default '',
		`address`	varchar(255) default '',
		`phone`		varchar(50) default '',
		`mail`		varchar(70) default '',
		`foto`		int(1) default 0,
		
		`time_create`	int(11) default 0,
		`time_update`	int(11) default 0,
		`time_last`		int(11) default 0,
		`user_create` 	int(11) default 0,
		`user_update` 	int(11) default 0,
		`ip_create`		char(16) default '',
		`ip_update`		char(16) default '',
		`ip_last`		char(16) default '',
		`u_online_id`	int(11) default 0,
		`remark`		mediumtext,
		
		`language`		char(5) default 'ru',
		PRIMARY KEY (id))ENGINE=InnoDB DEFAULT CHARSET=utf8",
		
		
/*
users_log
	���� �������� ������������������ ������������� (����� �������)
		user_id
		user_online_id - ��� ������ � ������-�������� users_online, ������� � ���, ����� ������������ �� ������ ����� �����
		
		status. 1-����, 9-�����, 2-���������� ����������
		user_info - ����������� ���������� 
			(�������!) - ���� ������������ ����� ������ �������
		time_create - ����� �������� ������
		ip	- ����
*/
	"{$config['prefix']}users_log"=>"CREATE TABLE `{$config['prefix']}users_log` (
		`id`		int(11) NOT NULL auto_increment,
		`deleted` 	int(1) default 0,
		
		`user_id`			int(11) default 0,
		`user_online_id`	int(11) default 0,
		`status` 			int(1) default 0,
		`user_info`			varchar(255) default '',
		`time_create`		int(11) default 0,
		`ip`				char(16) default '',
		PRIMARY KEY (id))ENGINE=InnoDB DEFAULT CHARSET=utf8",

/*
seo_lynks
		site_url		������ � ����� �������� ���������� url
						���� /uslugi/?id=22&�at=57
						����� ������ ��� get-��������� �������� ���������� ����� redirect_cpu.php + L_line
		site_redirect 	���� /uslugi/category/ ��� ������ �������, 
						�.�. ��� ���������������� �������� ������ ������� ����������
						get-��������� ����� ������������, ������ � ������ ����������, get � site_uri ����� ������� ���������, 
						��� � site_redirect
		is_replace_page	1/0, ����������, ����� �� ������ ��������� page �� site_url (�.�. �������� ������)
						���� = 1, �� � page ������������ ��� ��������, ������� ����� � site_url ������ � �������� ����
						������, �������������� ����� �������� � $page - �� 1�� �������
						(����� �� ����� ���������, �������������� � ������� site_uri ������������ ����� ���������)
				
			�� ������������ ����� �������� ��������� ��������: - 2014-06-11
						��� ������ page ���� � ����� ��������� ������� ����� �� ������ �������. ������ �������� get-���������� �� ����� 
						���������, �.�. ��� ������ ����� get (��� ���� ����������)
						�.� ���� � url ����� 2 � ����� ����� array_line, ������ ������ ����� ������ ���� ������ �� ��������
						��� ��� ����?
						
			�� ������������� (����): - 2014-06-11
		is_softly_replace	������ �� ������? �� ��������� - ���, �������
						������� ������ - ������ ������ ������ �� site_uri �� ������ site_redirect
							��� ���� ���� � ������ uri ���� �������������� ���������, ����� ����� ������ �� �������� ������. 
							��������� ������ ���� ������
						������ ������ - ��� ����� ������, ��� ������� ������������ ������ ����� ������ uri, ��������� � site_uri.
							���� ����� ������ ���������, ���������� ������. ��� ���� ���������� ����� (�����) ����������� � ����� uri, ���� 
							���������� ������ "�������� �����"
			� ����������: - 2014-06-11
		paste_tail		�������� �����. �� ��������� - ���
		is_softly_check	������ �� ��������? �� ��������� - ���, �������
						������� �������� - ���������, ����� ������� �� �������� ��� (�.�. �� ��� � �������), � ���������� ������ ������� 
							������� - site_redirect.
							
*/
		"{$config['prefix']}seo_lynks"=>"CREATE TABLE `{$config['prefix']}seo_lynks` (
		`id`			int(11) NOT NULL auto_increment,
		`deleted` 		int(1) default 0,
		
		`time_create`	int(11) default 0,
		`time_update`	int(11) default 0,
		`user_create`	int(11) default 0,
		`user_update`	int(11) default 0,
		
		`site_uri`			varchar(255) default '',
		`site_redirect`		varchar(255) default '',
		`is_replace_page`	tinyint(1) default 0,
		`is_softly_replace`	tinyint(1) default 0,
		`priority`			int(6) default 1,
		`remark`			varchar(255) default '',
		`title_ru`			varchar(255) default '',
		`keywords_ru`		varchar(255) default '',
		`description_ru` 	varchar(255) default '',
		`title_en`			varchar(255) default '',
		`keywords_en`		varchar(255) default '',
		`description_en` 	varchar(255) default '',
		PRIMARY KEY (id))ENGINE=InnoDB DEFAULT CHARSET=utf8",
		
		
/*
files
	
*/
/*
		"{$config['prefix']}files"=>"CREATE TABLE `{$config['prefix']}files` (
		`id`			int(11) NOT NULL auto_increment,
		`deleted` 		int(1) default 0,
		
		`time_create`	int(11) default 0,
		`time_update`	int(11) default 0,
		`user_create`	int(11) default 0,
		`user_update`	int(11) default 0,
		`file`			varchar(40) default '',
		`file_ext` 		varchar(80) default '',
		`file_is` 		tinyint(1) default 0,
		PRIMARY KEY (id))ENGINE=InnoDB DEFAULT CHARSET=utf8",
*/
		
);
?>