<?php
$separator = "../../../";
include "{$separator}ajax/_ScriptControl.php";
include "{$separator}modules/includesToAjax.php";
include "{$separator}data/personal/d_mailing_arrays.php";

$thisFile = "personal/mailing_deleted";

if ($global["authorization"]) {
	
	$id = $regular->num($_POST["id"]);
	if (
		$id !== false
	){
		$where = "NOT(`deleted`) AND `id`=\"{$id}\"";
		$val = $db->select_line("{$config["prefix"]}mailing", $where, "*", $thisFile);
		
		if ($val) {
			
			$data = array(
				"deleted"=>1,
				"time_update"=>$time,
				"user_update"=>$global["user"]["id"]
			);
			if ($db->update("{$config["prefix"]}mailing", $data, $where, $thisFile)) {
				echo "1|Удалено|";
			}
			else {
				echo "2|Не вышло. Попробуйте позднее|";
				$wrap->add (3, "insert: не удалось добавить строку в таблицу", $thisFile);
			}
		}
		else {
			echo "2|Не вышло";
			$wrap->add (0, "Взлом javascript: Нет такой позиции", $thisFile);
		}
	}
	else {
		echo "2|Не вышло";
		$wrap->add (3, "regular не пропустила", $thisFile);
	}
}
else {
	echo "9|Не вышло";
	$wrap->add (0, "Попытка совершить выход неавторизованным пользователем", $thisFile);
}

include "{$separator}error/index.php";
?>