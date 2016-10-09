<?php
$separator = "../../../";
include "{$separator}ajax/_ScriptControl.php";
include "{$separator}modules/includesToAjax.php";
include "{$separator}modules/db/personal/table_mailing.php";
include "{$separator}modules/db/personal/table_mailing_falsestart.php";
include "{$separator}data/personal/d_mailing_arrays.php";

$thisFile = "personal/mailing_open";

if ($global["authorization"]) {
	if ($_POST["npage"]) $npage = $regular->num($_POST["npage"]);
	else $npage = 1;
	if ($_POST["inPage"])$inPage = $regular->num($_POST["inPage"]);
	else $inPage = 0;
	$parent_id = $regular->num($_POST["parent_id"]);
	if ($_POST["url"]) $url = $regular->xstr($_POST["url"]);
	else $url = "";
	
	if (
		$parent_id 	!== false 
		&& $url 	!== false
		&& $npage 	!== false
		&& $inPage 	!== false
	) {
		$get = $line->getArray($url);

		$in = "";
		
		include "{$separator}data/personal/d_mailing.php";
		include "{$separator}templates/personal/html/t_mailing_Foreach.php";
		
		echo "1|".$in."|";
	}
	else {
		echo "2|Ошибка сервера|";
		$wrap->add (0, "Url с запрещенными символами", $thisFile);
	}
}
else {
	echo "9|Не вышло";
	$wrap->add (0, "Попытка совершить выход неавторизованным пользователем", $thisFile);
};

include "{$separator}error/index.php";
?>