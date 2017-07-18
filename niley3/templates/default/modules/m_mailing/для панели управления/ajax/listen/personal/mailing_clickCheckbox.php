<?php
$separator = "../../../";
include "{$separator}ajax/_ScriptControl.php";
include "{$separator}modules/includesToAjax.php";
include "{$separator}data/personal/d_mailing_arrays.php";

$thisFile = "personal/mailing_clickCheckbox";

if ($global["authorization"]) {
	
	$id = $regular->num($_POST["id"]);
	$cell = $regular->ext($_POST["cell"]);
	if (
		$id !== false &&
		$cell !== false
	){
		$where = "NOT(`deleted`) AND `id`=\"{$id}\"";
		$val = $db->select_line("{$config["prefix"]}mailing", $where, "*", $thisFile);
		
		if ($val) {
			if ($val[$cell] == 1) $thisCell = 0;
			else $thisCell = 1;
		
			$data = array(
				"{$cell}"=>$thisCell,
				"time_update"=>$time,
				"user_update"=>$global["user"]["id"]
			);
			if ($db->update("{$config[prefix]}mailing", $data, $where, $thisFile)) {
				$val[$cell] = $thisCell;
				include "{$separator}templates/personal/html/t_mailing_clickCheckbox.php";
				
				echo "1|{$in3}|";
			}
			else {
				echo "2|Нет|";
				$wrap->add (3, "insert: не удалось добавить строку в таблицу", $thisFile);
			}
		}
		else {
			echo "2|Нет|";
			$wrap->add (0, "Взлом javascript: Нет такой позиции", $thisFile);
		}
	}
	else {
		echo "2|Нет|";
		$wrap->add (3, "regular не пропустила", $thisFile);
	}
}
else {
	echo "9|Нет|";
	$wrap->add (0, "Попытка совершить выход неавторизованным пользователем", $thisFile);
}
include "{$separator}error/index.php";
?>