<?php  if ( ! defined('andromed')) exit('');

if ($dbTrue && $global['user_online_is']) {
	$data['js_bottom'] = "<script src='{$base_url}modules/session/cookies.js'></script>";
}

$data['js_bottom'] .= "
<script src='{$base_js}runonload_L.js'></script>
<script src='{$base_js}runonload.js'></script>
<script type='text/javascript'>document.close();</script>
";
?>