<?php if ( ! defined('andromed')) exit('');
$in = "";


if ($news){
	$in .= "<div class=''>{$num_pages[2]}</div>";
	
	$in .= "<div class='secTable'>";
		$in .= "<div class='tr Head'>";
		include "{$separator}templates/personal/html/t_mailing_Head.php";
		$in .= $in2;
		$in .= "</div>";
	
	foreach($news as $val){
		$in .= "<div id='trSec{$val['id']}' class='tr Def'>";
		include "{$separator}templates/personal/html/t_mailing_Tr.php";
		$in .= $in2;
		$in .= "</div>";
	}

	$in .= "</div>";
}
else {
	$in .= "<div class='secTable'><div class='tr'>
		Нет ни одной строки
	</div></div>";
}
?>