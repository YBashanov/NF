<?php if ( ! defined("andromed")) exit("");

$in = "";
$in .= "
<link href='{$base_url}templates/personal/style/s_mailing.css' rel='stylesheet' type='text/css' />
<script src='{$base_url}js/personal/mailing.js'></script>
<div id='news'></div>
<script>
var callback = function(){};
		
News.sec_open(false, false, callback); 
var pathToDataLib;
</script>";

$data['content'] = $in;
?>