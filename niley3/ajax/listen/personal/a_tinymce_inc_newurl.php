<?php if ( ! defined('andromed')) exit('');
//historyClick 1.0 (2014-07-30)
//исключаем лишние get-ключи из url, чтобы не было переполнения массива
//сюда же подключен массив из historyClicks

$get = $line->getArray($url);
if ($get) {
	$newUrl = "";
	foreach($get as $key=>$val){
		if ($key !== "open" && $key !== "m_ok" && $key !== "m_err" && $key !== "cell" && $key !== "auto" && $key !== "lang")
		if ($val != "") {
			//выясняем, есть ли данный ключ в массиве historyClicks
			$is_url_hclicks = false;
			if ($url_hclicks) {
				for ($i=0; $i<count($url_hclicks); $i++) {
					if ($key == $url_hclicks[$i]) {
						$is_url_hclicks = true;
						break;
					}
				}
			}
			
			if ($is_url_hclicks == false) {
				$newUrl .= $key."=".$val."&";
			}
		}
	}
}
?>