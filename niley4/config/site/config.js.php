<?php if ( ! defined('andromed')) exit(''); 

if ($global['server'] == "local") {
	$global['server_js'] = "<script>
		var server= 'we-not';
        var template= '{$global['template']}';
		var base_url = '{$base_url}';
		var base_js = '{$base_js}';
		var base_image = '{$base_image}';
		var url = '{$global['noindex_line']}';
        var language = '{$global['defaultLanguage']}';
		var s_get = '{$s_get}';
	</script>";
}
else {
	$global['server_js'] = "<script>
		var server= 'www.bjorksskitchen.myjino.ru/siteboundary';
        var template= '{$global['template']}';
		var base_url = '{$base_url}';
		var base_js = '{$base_js}';
		var base_image = '{$base_image}';
		var url = '{$global['noindex_line']}';
        var language = '{$global['defaultLanguage']}';
		var s_get = '{$s_get}';
	</script>";
}
?>