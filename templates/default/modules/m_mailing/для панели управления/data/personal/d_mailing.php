<?php if ( ! defined("andromed")) exit("");
if (! $thisFile) $thisFile = "data//d_mailing";
else $thisFile .= " + data//d_mailing";

if ($dbTrue && $global["user"]["status"] >= 5 && $isAjax){
	
	//результаты сортировки
	$whereAnd = "";
	$whereSort = "";

	$simpleSort = "";
	if ($simpleSort) {
		$simpleSort = substr($simpleSort, 0, strlen($simpleSort)-1);
		$whereSort = " ORDER BY".$simpleSort;
	}
	
	$table = "{$config["prefix"]}mailing";
	$where = "NOT(`deleted`)";
	$where .= $whereAnd;
	$where .= " GROUP BY `deleted`";
	$where .= $whereSort;
	$what = "id, COUNT(`id`) AS count";
	$newsCount = $db->select ($table, $where, $what, "id", $thisFile);
	if ($newsCount)
	$newsCount = array_shift($newsCount);

	//inPage - длина (количество штук)
	//npage - номер блока по счету (в каждом блоке определенное количество штук),т.е. номер страницы, начинается с 1
	if ($_POST["inPage"]) $get["inPage"] = $_POST["inPage"];
	if ($_POST["npage"])  $get["npage"]  = $_POST["npage"];
	if (! $get["inPage"]) $inPage = 20;
	else $inPage = $get["inPage"];

	if (! $get["npage"]) $npage = 1;
	else $npage = $get["npage"];

	$class = array(
		0=>"pageOpen",
		1=>"pageClose"
	);

	//коррекция url
	//переменные inPage и npage добавляются в массив после прохождения данной функции.
	//Значит, при внедрении в данную функцию их нужно изъять
	if ($get) {
		$newUrl = "";
		foreach($get as $key=>$val){
			if ($key !== "npage" && $key !== "inPage")
			if ($val != "") //удаление всех пустых переменных
				$newUrl .= $key."=".$val."&";
		}
	}
	$num_pages = $pages->num_pages($newsCount["count"], $inPage, $npage, $class, $base_url."personal/", "", "javascript", "News.sec_open(false, \"{$parent_id}\", ");
	$table = "{$config["prefix"]}mailing";
	$where = "NOT(`deleted`)";
	$where .= $whereAnd;
	$where .= $whereSort;
	$where .= " LIMIT {$num_pages[0]},{$num_pages[1]}";
	$what = "*";
	$news = $db->select ($table, $where, $what, "id", $thisFile);
}
?>