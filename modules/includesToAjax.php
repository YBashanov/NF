<?php  if ( ! defined('andromed')) exit('');
//версия 2.44.263
include "{$separator}libraries/L_debug/index.php";
include "{$separator}modules/global.php";
if ($dbTrue) {
	include "{$separator}modules/db/All_connection.php";
	include "{$separator}modules/authorization/authorization.php";
	include "{$separator}libraries/L_cookie/index.php";
}
include "{$separator}libraries/L_wrap/index.php";
include "{$separator}libraries/L_numGenerator/index.php";
include "{$separator}libraries/L_line/index.php";
include "{$separator}libraries/L_regular/index.php";
include "{$separator}libraries/L_select/index.php";
include "{$separator}libraries/L_image/index.php";
include "{$separator}libraries/L_date/index.php";
include "{$separator}libraries/L_string/index.php";
include "{$separator}libraries/L_pages/index.php";
include "{$separator}libraries/L_input/index.php";
include "{$separator}libraries/L_array/index.php";
include "{$separator}libraries/L_popup/index.php";
include "{$separator}libraries/L_mimeType/index.php";
//include "{$separator}libraries/L_content/index.php";
include "{$separator}libraries/L_template/index.php";

$generator = L_numGenerator::init($wrap);
$global['libraries']['generator'] = $generator;

$line = Line_inquiryFast::init($wrap);
$global['libraries']['line'] = $line;

if ($dbTrue) {
	$cookie = L_cookie::init($wrap);
	$global['libraries']['cookie'] = $cookie;
}

$regular = L_regular::init ($wrap);
$global['libraries']['regular'] = $regular;

$select = L_select::init($wrap);
$global['libraries']['select'] = $select;

$image = L_image::init($wrap);
$global['libraries']['image'] = $image;

$date = L_date::init ($wrap);
$date->separator = $separator;
$global['libraries']['date'] = $date;

$string = L_string::init($wrap);
$global['libraries']['string'] = $string;

$pages = L_pages::init($wrap);
$global['libraries']['pages'] = $pages;

$input = L_input::init($wrap);
$global['libraries']['input'] = $input;

$array = L_array::init($wrap);
$global['libraries']['array'] = $array;

$popup = L_popup::init($wrap);
$global['libraries']['popup'] = $popup;

$mime = L_mimeType::init($wrap);
$global['libraries']['mime'] = $mime;

/*
$content = L_content::init($wrap);
$global['libraries']['content'] = $content;
$content->separator 		= $separator;
$content->global_template 	= $global['template'];
$content->base_url 			= $base_url;
*/

$time = time();

//добавление рабочих модулей
if ($dbTrue && $global['user_online_is']) {
	include "{$separator}modules/session/cookies.php";
}
include "{$separator}data/variables.php";
include "{$separator}data/variables_array.php";

$template = new L_template();
$template->setGlobal($global);
$global['libraries']['template'] = $template;
include "{$separator}modules/functions/templateF.php";
include "{$separator}modules/functions/universal.php";

include "{$separator}data/variables_array_template_vars.php";
?>