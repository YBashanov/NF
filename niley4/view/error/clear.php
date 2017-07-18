<?php
//после тестирования данный файл желательно отключить, т.к. данные, попадаемые через POST, не фильтруются
$separator = "../";
include "{$separator}ajax/_ScriptControl.php";
include "{$separator}modules/includesToAjax.php";

$thisFile = "error/clear";

if ($noFilter) {
	if ($error->clearErrors($db)) echo "1|";
	else echo "2|";
}
else {
	echo "Нет разрешения! Смотри config (noFilter)";
}
include "{$separator}error/index.php";
?>