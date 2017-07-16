<?php if ( ! defined('andromed')) exit(''); 
$width = 160;
$height = 150;

$data['bell1'] = "
	<!--[if !IE]>-->
		<object type='image/svg+xml' name='mybutterfly' id='mySVGObject' 
			data='{$base_url}modules/svg/bell1.xml' width='{$width}' height='{$height}'>
		</object>
	<!--<![endif]-->
	<!--[if IE 8]>
		<object type='image/svg+xml' name='mybutterfly' id='mySVGObject' 
			data='{$base_url}modules/svg/bell1.xml' width='{$width}' height='{$height}'>
		</object>
	<![endif]-->
	<!--[if gt IE 8]>
		<object type='image/svg+xml' name='mybutterfly' id='mySVGObject' 
			data='{$base_url}modules/svg/bell1.xml' width='{$width}' height='{$height}'>
		</object>
	<![endif]-->
	<!--[if lte IE 7]>
		<v:image 
			id='backg' src='{$base_url}@/img/clock2/backg.png'
			style='height:{$width}px; width:{$width}px; rotation:0'
		/>
		<v:image 
			id='hour' src='{$base_url}@/img/clock2/hour.png'
			style='height:{$width}px; width:{$width}px; rotation:0'
		/>
		<v:image 
			id='minute' src='{$base_url}@/img/clock2/minute.png'
			style='height:{$width}px; width:{$width}px; rotation:0'
		/>
		<v:image 
			id='second' src='{$base_url}@/img/clock2/second.png'
			style='height:{$width}px; width:{$width}px; rotation:0'
		/>
		<script src='{$separator}js/classes/Drag.js' language='JavaScript'></script>
		<script src='{$separator}modules/svg/js/clock.js' language='JavaScript'></script>
		<script language='JavaScript'>
			Clock.setIE('ie');
			Clock.start();
		</script>
	<![endif]-->
	";
//$data['bell1'] = "333333333333333333333";
?>