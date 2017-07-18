<?php if ( ! defined('andromed')) exit(''); 

if (! $arrays) $arrays = array();

$arrays['template_vars'] = array(
	PAGE		=> $page,
    TPL         => $global['template'],
    BASE_TPL    => $global['base_tpl'],
    PATH_TPL    => $global['path_tpl'],
	LANGUAGE	=> $global['language'],
	
	
	TITLE 		=> $data['title'],
	KEYWORDS 	=> $data['keywords'],
	DESCRIPTION => $data['description'],
	CSS			=> $data['css'],
	JS_TOP		=> $data['js_top'],


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