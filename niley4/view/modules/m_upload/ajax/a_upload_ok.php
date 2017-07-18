<?php if ( ! defined('andromed')) exit(''); 
/*
//адаптер
if ($tableName == "fotos" && $cell == "file" && $type == "triple") {
    $f_a_upload_ok = "{$separator}{$global['path_modules']}m_upload/ajax/a_upload_ok.php";
    if (file_exists($f_a_upload_ok)){
        include $f_a_upload_ok;
    }
}

Возвращает еще и добавленное id фотографии, чтобы быстро бросить его в поиск
*/
$newId = $db->id();
echo "1|Загружено|{$newId}|";
?>