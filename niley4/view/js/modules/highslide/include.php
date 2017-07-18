<?php if ( ! defined('andromed')) exit(''); 

$data['inc_highslide'] = "<link rel='stylesheet' type='text/css' href='{$base_url}view/js/modules/highslide/highslide.css'/>
<script type='text/javascript' src='{$base_url}view/js/modules/highslide/highslide.js'></script>
<script type='text/javascript'>
	hs.graphicsDir = '{$base_url}view/js/modules/highslide/graphics/';
	hs.wrapperClassName = 'wide-border';
</script>";

$data['inc_highslide'] = $highslide;

$template->setVars(['INC_HIGHSLIDE'=>$highslide], "a");
?>