<?php if ( ! defined('andromed')) exit(''); 

if (! $arrays) $arrays = array();

$arrays['template_vars'] = array(
	PAGE		=> $page,
	TEMPLATE	=> $global['template'],
	BASE_URL	=> $global['base_url'],
	BASE_IMAGE	=> $global['base_image'],
	BASE_IMG	=> $global['base_image'],
	BASE_JS		=> "{$global['base_url']}templates/{$global['template']}/js/",
	BASE_CLASS_JS=> "{$global['base_url']}js/",
	BASE_STYLE	=> "{$global['base_url']}templates/{$global['template']}/style/",
	BASE_UPLOADS=> "{$global['base_url']}uploads/",
	BASE_MODULES=> "{$global['base_url']}templates/{$global['template']}/modules/",
	BASE_TPL	=> "{$global['base_url']}templates/{$global['template']}/tpl/",
	LANGUAGE	=> $global['language'],
	
	
	TITLE 		=> $data['title'],
	KEYWORDS 	=> $data['keywords'],
	DESCRIPTION => $data['description'],
	CSS			=> $data['css'],
	JS_TOP		=> $data['js_top'],
	INC_HIGHSLIDE			=> "<link rel='stylesheet' type='text/css' href='{$base_url}js/highslide/highslide.css'/>
<script type='text/javascript' src='{$base_url}js/highslide/highslide.js'></script>
<script type='text/javascript'>
	hs.graphicsDir = '{$base_url}js/highslide/graphics/';
	hs.wrapperClassName = 'wide-border';
</script>",
	COUNTER_GOOGLE			=> $data['counter_google'],
	COUNTER_YANDEXMETRIKA	=> $data['counter_yandexmetrika'],
	JS_BOTTOM	=> $data['js_bottom'],
	HEAD		=> $data['head'],
	FOOTER		=> $data['footer'],
	SCRUBS		=> $data['scrubs'],
	
	
	YEAR		=> $getdate['year'],
	TODAY		=> date("d.m.Y", $time)
);
$template->setVars($arrays['template_vars'], "a");
?>