<?php if ( ! defined('andromed')) exit(''); 
/*
Обновляет список фотографий, загруженных пользователем
Обновляет без перезагрузки страницы


1. Подключение модуля
include "{$separator}templates/{$global['template']}/modules/m_upload_menu/config.php";

2. Подключение шаблона в страницу
{M_UPLOAD_MENU}
*/

module_(INDEX_DYNAMIC, array(), "m_upload_menu/index_dynamic");
module__dynamic(INDEX_DYNAMIC, "m_upload_menu/index_dynamic", $fotos, false, 1);
module_(M_UPLOAD_MENU);
?>