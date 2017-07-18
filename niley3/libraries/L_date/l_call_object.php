<?php
/*
работает как ajax - необходим для вызова методов объектов библиотек
связан с методом date->calendar (добавляет интерактивности)
изменен 10.06.12
изменен 12.06.12 вместе с l_date. Исключаем зависимость от ajax
изменен 2013-06-07 - под общий вид начала стандартных ajax-файлов
*/

//путь определяется только для AJAX-файла
$separator = '../../';
include "{$separator}ajax/_ScriptControl.php";
include "{$separator}modules/includesToAjax.php";


$date->separator = $separator;
echo $date->_calendar($_POST['FormName'], $_POST['InputName'], $_POST['id'], $_POST['windowid'], true, $_POST['outside_div']);
include "{$separator}error/index.php";
?>