<?php if ( ! defined('andromed')) exit('');

if (
	$inDB 
	|| $page == "index"
) {
	$template->setPath("index.html");
	$template->parse(HTML);
	$template->prints(HTML);
}
else {
	if ($errorTrue) {
		$wrap->add(2, "Для данной страницы нет настроек в data/ ({$page})", "_content_management");
		$wrap->write();
		header ('Location: /', '', 301);
		exit();
	}
}
