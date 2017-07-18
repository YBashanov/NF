<?php 
$separator = "../../../../../";
include "{$separator}ajax/_ScriptControl.php";
include "{$separator}modules/includesToAjax.php";

$thisFile = "tpl//modules/rating//vote.php";

$val 	= $regular->ext($_POST["val"]);		//среднее значение по очкам, так сосчитал js
$votes 	= $regular->num($_POST["votes"]);	//сколько всего голосов (голосовавших) - так сосчитал js
$id 	= $regular->num($_POST["vote-id"]);	//уникальный id
$score 	= $regular->ext($_POST["score"]);	//очки сейчас

if (
	$val 	!== false &&
	$votes 	!== false &&
	$id 	!== false &&
	$score 	!== false
){
	$where = "NOT(`deleted`) AND `id`='{$id}'";
	$menu_sub2_line = $db->select_line("{$config["prefix"]}menu_sub2", $where, "*", $thisFile);

	if (($votes-1) == $menu_sub2_line['sum_voters']) {
		$result = ($menu_sub2_line['sum_vote'] * $menu_sub2_line['sum_voters'] + $score) / $votes;
		$result = round($result * 100) / 100;

		if ($val == $result) {
			
			//куки
			$cookie_name = "vote" . $id;
			if ($cookie->get_cookie($cookie_name)) {
				echo "2|Вы уже голосовали здесь.";
			}
			else {
				$cookie->set_cookie($cookie_name, "1", 86400*24);
				
				$data = array(
					"sum_voters"	=>$votes,
					"sum_vote"		=>$result
				);
				if ($db->update("{$config["prefix"]}menu_sub2", $data, $where, $thisFile)) {
					echo "1|Спасибо. Ваш голос учтен.";
				}
				else {
					echo "2|Не вышло. Ошибка.";
					$wrap->add (2, "Ошибка базы данных", $thisFile);
				}
			}
		}
		else {
			echo "2|Не вышло. Ошибка.";
			$wrap->add (1, "Значение js не совпадает с базой. Нас пытались обмануть, подмена среднего значения по очкам", $thisFile);
		}
	}
	else {
		echo "2|Не вышло. Ошибка.";
		$wrap->add (1, "Значение js не совпадает с базой. Нас пытались обмануть, подмена числа голосов", $thisFile);
	}
}
else {
	echo "2|Не вышло. Ошибка.";
	$wrap->add (3, "regular не пропустила", $thisFile);
}
include "{$separator}error/index.php";
?>