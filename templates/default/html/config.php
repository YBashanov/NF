<?php if ( ! defined('andromed')) exit(''); 

//для мобильного приложения
if ($browser_info['os'] == "iPhone") {
	html(META_MOBILE);
}
elseif ($browser_info['os'] == "Safari") {
	html(META_MOBILE, array(), "meta_safari");
}
else {
	$template->add(array(META_MOBILE=>""));
}

html(T_HEAD);
html(T_FOOTER);
html(T_COPYRIGHT);
?>