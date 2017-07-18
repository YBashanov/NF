<?php
$separator = "../../../";
include "{$separator}ajax/_ScriptControl.php";
include "{$separator}modules/includesToAjax.php";
include "{$separator}data/personal/d_mailing_arrays.php";

$thisFile = "personal/mailing_save";

if ($global["authorization"]) {

	$parent_id = $regular->num($_POST["parent_id"]);
	$id = $regular->num($_POST["id"]);
	if (
		$parent_id !== false &&
		$id !== false
	){
		//insert
		if ($id == 0) {
			$data = array(
				"time_create"=>$time,
				"user_create"=>$global["user"]["id"]
			);
			if ($db->insert("{$config["prefix"]}mailing", $data, $thisFile)) {
				echo "1|Успешно|";
			}
			else {
				echo "2|Не вышло. Попробуйте позднее|";
				$wrap->add (3, "insert: не удалось добавить строку в таблицу", $thisFile);
			}
		}
		
		//update
		else {
			$where = "NOT(`deleted`) AND `id`=\"{$id}\"";
			$val = $db->select_line("{$config["prefix"]}mailing", $where, "*", $thisFile);
			
			if ($val) {
				$data = array(
					"time_update"=>$time,
					"user_update"=>$global["user"]["id"]
				);
				if ($db->update("{$config["prefix"]}mailing", $data, $where, $thisFile)) {
					include "{$separator}templates/personal/html/t_mailing_Tr.php";
					echo "1|".$in2;
				}
				else {
					echo "2|Не вышло. Попробуйте позднее|";
					$wrap->add (3, "insert: не удалось добавить строку в таблицу", $thisFile);
				}
			}
			else {
				echo "2|Не вышло. Ошибка сервера.";
				$wrap->add (0, "Взлом javascript: Нет такой позиции", $thisFile);
			}
		}
	}
	else {
		echo "2|Не вышло. Ошибка сервера.";
		$wrap->add (3, "regular не пропустила", $thisFile);
	}
}
else {
	echo "9|Не вышло";
	$wrap->add (0, "Попытка совершить выход неавторизованным пользователем", $thisFile);
}
include "{$separator}error/index.php";
?>