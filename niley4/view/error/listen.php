<?php
//после тестирования данный файл желательно отключить, т.к. данные, попадаемые через POST, не фильтруются
$separator = "../";
include "{$separator}ajax/_ScriptControl.php";
include "{$separator}modules/includesToAjax.php";

$thisFile = "error/listen";

if ($noFilter) {
	$fileName = $thisFile . " -- " . $_POST['file'];
	$wrap->add(2, $_POST['text'], $fileName);
}
include "{$separator}error/index.php";
?>