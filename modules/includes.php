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
include "{$separator}libraries/L_browser/index.php";
include "{$separator}libraries/L_numGenerator/index.php";
include "{$separator}libraries/L_line/index.php";
include "{$separator}libraries/L_regular/index.php";
include "{$separator}libraries/L_select/index.php";
include "{$separator}libraries/L_image/index.php";
include "{$separator}libraries/L_date/index.php";
include "{$separator}libraries/L_string/index.php";
include "{$separator}libraries/L_pages/index.php";
//include "{$separator}libraries/L_content/index.php";
include "{$separator}libraries/L_template/index.php";
include "{$separator}libraries/L_mimeType/index.php";
include "{$separator}libraries/L_files/index.php";


$generator = L_numGenerator::init($wrap);
$global['libraries']['generator'] = $generator;

$browser = L_browser::init($wrap);
$global['libraries']['browser'] = $browser;
$browser_info = $browser->get();

$line = L_line::init($pagePrefix, $wrap); $global['libraries']['line'] = $line;
$line->setIsLanguage($global['is_language']);
$line->setDefaultLanguage($global['defaultLanguage']);
$line->prog_created_line();
$global['page'] 		= $line->page; 		 $page 		 	= $global['page'];
$global['array_line'] 	= $line->array_line; $array_line 	= $global['array_line'];
$global['request_uri'] 	= $line->request_uri;$request_uri	= $global['request_uri'];
$global['noindex_line'] = $line->line;	 	 $noindex_line	= $global['noindex_line'];
$global['language'] 	= $line->getLanguageName();	$language	= $global['language'];


if ($dbTrue) {
	$cookie = L_cookie::init($wrap);
	$global['libraries']['cookie'] = $cookie;
}

$date = L_date::init ($wrap);
$global['libraries']['date'] = $date;

$select = L_select::init($wrap);
$global['libraries']['select'] = $select;

$string = L_string::init($wrap);
$string->setSeparator ($separator);
$string->setLibraries ($global['libraries']);
$global['libraries']['string'] = $string;

$pages = L_pages::init($wrap);
$global['libraries']['pages'] = $pages;

$regular = L_regular::init ($wrap);
$global['libraries']['regular'] = $regular;

$image = L_image::init($wrap);
$image->setSeparator($base_url);

$mime = L_mimeType::init($wrap);
$global['libraries']['mime'] = $mime;

$file = L_files::init($wrap);
$global['libraries']['file'] = $file;

$time = time();

/*
$content = L_content::init($wrap);
$global['libraries']['content'] = $content;
$content->base_url 		= $base_url;
$content->separator 	= $separator;
$content->global_template = $global['template'];
$content->page 			= $page;
$content->is_language 	= $global['is_language'];
$content->language 		= $global['language'];
*/

//добавление рабочих модулей
if ( $global['is_topserver'] == false ) {include "{$separator}modules/redirect_www.php";}
include "{$separator}modules/redirect_cpu_progressive.php";

if ($dbTrue && $global['user_online_is']) {
	include "{$separator}modules/session/cookies.php";
}
include "{$separator}modules/config.js.php";
include "{$separator}modules/includes_css.php";
include "{$separator}modules/includes_jsTop.php";
include "{$separator}modules/includes_jsBottom.php";
include "{$separator}data/variables.php";
include "{$separator}data/variables_array.php";


$global['page'] = $page;
$template = new L_template();
$template->setGlobal($global);
$global['libraries']['template'] = $template;
include "{$separator}modules/functions/templateF.php";
include "{$separator}modules/functions/merger.php";
include "{$separator}modules/functions/universal.php";


if ($global['change_templates_from_addressstring']){
	if ($global['dbTrue']) {
		$wrap->add(1, "! Опасно! Включена смена шаблона через GET !", "config.php");
	}
	else {
		echo "<div style='position:absolute;font-family:Tahoma;font-size:12px;color:#f95;'>! Опасно ! Включена смена шаблона через GET !</div>";
	}
}
?>