<?php if ( ! defined('andromed')) exit('');

function dump ($var, $color = "#000") {
	echo "<pre style='font-family:Tahoma;font-weight:400;font-size:11px;font-style:normal;color:{$color};'>";
	var_dump ($var);
	echo "</pre>";
}
function dump_echo_line(){
	echo "-----------------------------------------------------------------------------------------------------------";
}
function v ($var, $color = "#000") {
	echo "<pre>";
	var_dump ($var);
	echo "</pre>";
}
function vv ($var, $color = "#000") {
	dump($var, $color);
	dump_echo_line();
} 
function a ($var, $color = "#000") {
	echo "<pre>";
	print_r ($var);
	echo "</pre>";
}
function p ($var, $color = "#000") {
	a($var, $color);
}
function aa ($var, $color = "#000") {
	echo "<pre style='font-family:Tahoma;font-weight:400;font-size:11px;font-style:normal;color:{$color};'>";
	print_r ($var);
	echo "</pre>";
	dump_echo_line();
}
function pp ($var, $color = "#000") {
	aa($var, $color);
}
?>