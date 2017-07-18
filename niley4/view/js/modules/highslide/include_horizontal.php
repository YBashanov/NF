<?php if ( ! defined('andromed')) exit(''); 

$highslide = "
<link rel='stylesheet' type='text/css' href='{$base_url}js/highslide/highslide.css'/>
<!--[if lt IE 7]>
<link rel='stylesheet' type='text/css' href='{$base_url}js/highslide/highslide-ie6.css' />
<![endif]-->
<script type='text/javascript' src='{$base_url}js/highslide/highslide-full.js'></script>

<script type='text/javascript'>
hs.graphicsDir = '{$base_url}js/highslide/graphics/';
hs.align = 'center';
hs.transitions = ['expand', 'crossfade'];
hs.fadeInOut = true;
hs.dimmingOpacity = 0.8;
hs.outlineType = 'rounded-white';
hs.captionEval = 'this.thumb.alt';
hs.marginBottom = 105; // make room for the thumbstrip and the controls
hs.numberPosition = 'caption';

// Add the slideshow providing the controlbar and the thumbstrip
hs.addSlideshow({
	//slideshowGroup: 'group1',
	interval: 5000,
	repeat: false,
	useControls: true,
	overlayOptions: {
		className: 'text-controls',
		position: 'bottom center',
		relativeTo: 'viewport',
		offsetY: -60
	},
	thumbstrip: {
		position: 'bottom center',
		mode: 'horizontal',
		relativeTo: 'viewport'
	}
});
</script>";

$data['inc_highslide'] = $highslide;
?>