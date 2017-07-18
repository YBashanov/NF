<?php  if ( ! defined('andromed')) exit('');


$data['css'] = "
<style>v\: * { behavior:url(#default#VML); display:inline-block; position:absolute;}</style>
<link href='{$base_url}templates/{$global['template']}/style/reset.css' rel='stylesheet' type='text/css' />
<link href='{$base_url}templates/{$global['template']}/style/universal.css' rel='stylesheet' type='text/css' />
<link href='{$base_url}templates/{$global['template']}/style/structure.css' rel='stylesheet' type='text/css' />
<!--[if lte IE 8]>
<link href='{$base_url}templates/{$global['template']}/style/structure_IE.css' rel='stylesheet' type='text/css' />
<![endif]-->
<link href='{$base_url}templates/{$global['template']}/style/font.css' rel='stylesheet' type='text/css' />";

if ($browser_info['os'] == "iPhone") {
	$data['css'] .= "<link rel='stylesheet' href='{$base_url}templates/{$global['template']}/style/browser_iphone.css' media='only screen and (max-device-width: 480px)' />";
}
elseif ($browser_info['os'] == "Safari") {
	$data['css'] .= "<link rel='stylesheet' href='{$base_url}templates/{$global['template']}/style/browser_safari.css' />";
}
?>