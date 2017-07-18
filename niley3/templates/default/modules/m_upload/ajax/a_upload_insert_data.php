<?php if ( ! defined('andromed')) exit(''); 
/*
//адаптер
if ($tableName == "fotos" && $cell == "file" && $type == "triple") {
    $f_a_upload_insert = "{$separator}{$global['path_modules']}m_upload/ajax/a_upload_insert_data.php";
    if (file_exists($f_a_upload_insert)){
        include $f_a_upload_insert;
        
    }
}

записывает еще и user_online_create, чтобы можно было разделить пользователей, кто загрузил из админки и пользователей-левых
*/
$data = array(
	"time_create"=>$time,
	"user_create"=>$global['user_online']['id'],
	"user_online_create"=>$global['user_online']['id'],
	"{$cell}"=>$newName,
	"{$cell}_ext"=>$expan,
	"{$cell}_is"=>1
);
?>