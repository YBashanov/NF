<?php if ( ! defined("andromed")) exit("");
$in2 = "";
if (! $val['id']) $val['id'] = "0";



if ($val["time_create"]) $val["time_create"] = date("d-m-Y", $val["time_create"]);
else $val["time_create"] = "";

$in2 .= "<div class='td Def1'>{$val['time_create']}</div>";
$in2 .= "<div class='td Def2'>{$val['mail']}</div>";
$in2 .= "<div class='td td1' onclick='News.sec_save(\"{$val['id']}\", \"{$parent_id}\")'>
	<div class='dButton'>Сохранить</div>
</div>";
$in2 .= "<div class='cle'></div>";
?>