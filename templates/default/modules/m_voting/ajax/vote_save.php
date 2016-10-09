<?php
$separator = "../../../../../";
include "{$separator}ajax/_ScriptControl.php";
include "{$separator}modules/includesToAjax.php";
$thisFile = "modules//vote_save";


$vote = $regular->eng_num_sol($_POST["vote"]);
$id = $regular->num($_POST["id"]);

if (
	$vote !== false 
	&& $id !== false
){
	$where = "NOT(`deleted`) AND `id`=\"{$id}\"";
	$val = $db->select_line("{$config["prefix"]}fotos", $where, "*", $thisFile);
	
	if ($val) {
        $result = 0;
        if ($vote == "vote_plus") {
            $result = ++$val['vote_plus'];
        }
        else if ($vote == "vote_minus"){
            $result = ++$val['vote_minus'];
        }
		$data = array(
			"vote_plus"=>$val['vote_plus'],
			"vote_minus"=>$val['vote_minus'],
			"time_update"=>$time,
			"user_update"=>$global["user_online"]["id"]
		);
		if ($db->update("{$config["prefix"]}fotos", $data, $where, $thisFile)) {
			echo '{"status":"1","message":"Ок","result":"'.$result.'","vote_plus":"'.$val['vote_plus'].'","vote_minus":"'.$val['vote_minus'].'"}';
		}
		else {
			echo '{"status":"2","message":"Не вышло"}';
			$wrap->add (3, "update: не удалось добавить строку в таблицу", $thisFile);
		}
	}
	else {
		echo '{"status":"2","message":"Не вышло"}';
		$wrap->add (0, "Взлом javascript: Нет такой позиции", $thisFile);
	}
}
else {
	echo '{"status":"2","message":"Не вышло"}';
	$wrap->add (3, "regular не пропустила", $thisFile);
}
include "{$separator}error/index.php";
?>