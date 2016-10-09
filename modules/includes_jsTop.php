<?php  if ( ! defined('andromed')) exit('');

if (! isset ($global['server_js'])) $global['server_js'] = "";

$data['js_top'] = $global['server_js'];
$data['js_top'] .= "
<script src='{$base_url}js/classes/Debug.js'></script>
<script src='{$base_url}js/classes/Wait.js'></script>
<script src='{$base_url}js/classes/Line.js'></script>
<script src='{$base_url}js/classes/IE.js'></script>
<script src='{$base_url}js/classes/Handler.js'></script>
<script src='{$base_url}js/classes/HandlerOnload.js'></script>
<script src='{$base_url}js/classes/HTTP.js'></script>
<script src='{$base_url}js/classes/Popup.js'></script>
<script src='{$base_url}js/classes/Regular.js'></script>
<script src='{$base_url}js/classes/Language.js'></script>
<script src='{$base_url}js/classes/Universal.js'></script>
<script src='{$base_url}js/classes/Error.js'></script>
<script src='{$base_url}js/modules/jquery.js'></script>

<script src='{$base_url}js/classes/Functs.js'></script>
<script>
	var get = Line.toArray(s_get);
	var language = \"{$language}\";
	var page = \"{$page}\";
	//var ieTrue = false;
	//var browser = browserDetectNav();
	//if (browser[0] == \"MSIE\") ieTrue = true;
</script>
";
?>