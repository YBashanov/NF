<?php if ( ! defined('andromed')) exit(''); 
/*
Попытка создать кнопку загрузки отдельным модулем
- Требуется подключение jquery
- Смотри, чтобы в ajax/ была последняя версия файла mediaUpload_boundary_public


1. Подключение модуля
	include "{$separator}templates/{$global['template']}/modules/m_upload/config.php";

2. Подключение шаблона в страницу
	{M_UPLOAD}

3. d_temp_arrays.php
    - настройки той или иной таблицы (temp - просто один из вариантов названия таблицы)
    
4. Активация
    - в index.html
    <script>M_upload.start({table : "fotos", type : "triple"});</script>
    
    - в runonload.js
    M_upload.start({table : "fotos", type : "triple"});
    M_upload.start({table : "fotos", type : "triple", callback : function(){}}); //(можно также установить метод-callback иного модуля)
    
    метод start:
    [id]    - айди строки в одной из таблиц
    [table] - таблица 
    [cell]  - имя ячейки, например file 
    [type]  - тип загрузчика (pdf, cover)
    [idParent] - если загрузка идет в дочернюю таблицу, нам надо знать связь с родительской
    [callback] - callback-метод
    [error]    - этот callback-метод вызовется, если произошла ошибка
    
    
Настройка личного mediaUpload_boundary_public
    стр.33 - $file_include = "d_{$tableName}_arrays.php";
    
    !!! Но лучше использовать один из уже настроенных mediaUpload_boundary (т.к. готовый файл уже настроен на запись в тот или иной раздел базы)
    

Настройка самого mediaUpload_boundary_public - внутри метод include
    
    В том месте, где возвращается успешный результат, добавляем: 
    if ($tableName == "fotos" && $cell == "file" && $type == "triple") {
        $f_a_upload_ok = "{$separator}{$global['path_modules']}m_upload/ajax/a_upload_ok.php";
        if (file_exists($f_a_upload_ok)){
            include "{$f_a_upload_ok}";
        }
    }
    
    там, где хотим изменить запись в базу
    if ($tableName == "fotos" && $cell == "file" && $type == "triple") {
        $f_a_upload_insert = "{$separator}{$global['path_modules']}m_upload/ajax/a_upload_insert_data.php";
        if (file_exists($a_upload_insert_data.php)){
            include "{$a_upload_insert_data.php}";
        }
    }
*/
module_(M_UPLOAD);
?>