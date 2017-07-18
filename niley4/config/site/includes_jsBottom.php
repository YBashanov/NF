<?php  if ( ! defined('andromed')) exit('');

if ($dbTrue && $global['user_online_is']) {
	$data['js_bottom'] = "<script src='{$base_url}php/modules/session/cookies.js'></script>";
}

$data['js_bottom'] .= "
<script src='{$path_tpl}js/runonload_L.js'></script>
<script src='{$path_tpl}js/runonload.js'></script>
<script type='text/javascript'>document.close();</script>
";
