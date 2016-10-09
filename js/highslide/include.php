<?php if ( ! defined('andromed')) exit(''); 

$highslide = "<link rel='stylesheet' type='text/css' href='{$base_url}js/highslide/highslide.css'/>
<script type='text/javascript' src='{$base_url}js/highslide/highslide.js'></script>
<script type='text/javascript'>
	hs.graphicsDir = '{$base_url}js/highslide/graphics/';
	hs.wrapperClassName = 'wide-border';
</script>";

$data['inc_highslide'] = $highslide;
?>