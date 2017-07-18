<?php  if ( ! defined('andromed')) exit('');


$data['css'] = "
<style>v\: * { behavior:url(#default#VML); display:inline-block; position:absolute;}</style>
<link href='{$separator}view/css/reset.css' rel='stylesheet' type='text/css' />
<link href='{$separator}view/css/universal.css' rel='stylesheet' type='text/css' />
<link href='{$path_tpl}css/structure.css' rel='stylesheet' type='text/css' />
<!--[if lte IE 8]>
<link href='{$path_tpl}css/structure_IE.css' rel='stylesheet' type='text/css' />
<![endif]-->
<link href='{$path_tpl}css/font.css' rel='stylesheet' type='text/css' />";

if ($browser_info['os'] == "iPhone") {
	$data['css'] .= "<link rel='stylesheet' href='{$path_tpl}css/browser_iphone.css' media='only screen and (max-device-width: 480px)' />";
}
elseif ($browser_info['os'] == "Safari") {
	$data['css'] .= "<link rel='stylesheet' href='{$path_tpl}css/browser_safari.css' />";
}
?>