<?php  if ( ! defined('andromed')) exit('');

if (! isset ($global['server_js'])) $global['server_js'] = "";

$data['js_top'] = $global['server_js'];

$data['js_top'] .= "
<script src='{$base_url}view/js/classes/Debug.js'></script>
<script src='{$base_url}view/js/classes/Wait.js'></script>
<script src='{$base_url}view/js/classes/Line.js'></script>
<script src='{$base_url}view/js/classes/IE.js'></script>
<script src='{$base_url}view/js/classes/Handler.js'></script>
<script src='{$base_url}view/js/classes/HandlerOnload.js'></script>
<script src='{$base_url}view/js/classes/HTTP.js'></script>
<script src='{$base_url}view/js/classes/Popup.js'></script>
<script src='{$base_url}view/js/classes/Regular.js'></script>
<script src='{$base_url}view/js/classes/Language.js'></script>
<script src='{$base_url}view/js/classes/Universal.js'></script>
<script src='{$base_url}view/js/classes/Error.js'></script>

<script src='{$base_url}view/js/lib/jquery/jquery1.11.js'></script>

<script src='{$base_url}view/js/classes/Functs.js'></script>
<script>
	var get = Line.toArray(s_get);
	var language = \"{$language}\";
	var page = \"{$page}\";
	//var ieTrue = false;
	//var browser = browserDetectNav();
	//if (browser[0] == \"MSIE\") ieTrue = true;
</script>
";

