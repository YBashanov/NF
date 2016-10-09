<?php if ( ! defined('andromed')) exit('');
//historyClick 1.0 (2014-07-30)
//получение массива с новыми url - с url-историей хождения по разделам-подразделам
//получение get-блока для подстановки в url



if ($_POST["historyClicks"]) $historyClicks = $regular->ext($_POST["historyClicks"]);
else $historyClicks = "";

$a_hclicks = explode("//", $historyClicks);
$str_hclicks = "";
$url_hclicks = false; //для исключения этих переменных (historyClicks)
if ($a_hclicks) {
	$i = 0;
	foreach($a_hclicks as $val) {
		$a_url_hclicks = explode("=", $val);
		if ($a_url_hclicks[0]) {
			$url_hclicks[$i] = $a_url_hclicks[0];
			$str_hclicks .= $val . "&";
			$i++;
		}
	}
	$str_hclicks = substr($str_hclicks, 0, strlen($str_hclicks)-1);
}
?>