<?php  if ( ! defined('andromed')) exit('');
//версия 2.44.263
include "{$separator}config/site/config.php.php";
include "{$separator}php/libraries/{$global['style_libraries']}/L_debug/index.php";
include "{$separator}php/libraries/niley4/Error.php";

$global['error'] = Error::init();
/**{@link Error}*/
$error = $global['error'];

if ($dbTrue) {
    include "{$separator}php/modules/{$global['style_modules']}/db/All_connection.php";
    include "{$separator}php/modules/{$global['style_modules']}/authorization/authorization.php";
    include "{$separator}php/libraries/{$global['style_libraries']}/L_cookie/index.php";
}
include "{$separator}php/libraries/{$global['style_libraries']}/L_wrap/index.php";
include "{$separator}php/libraries/niley4/Regular.php";
include "{$separator}php/libraries/niley4/Response.php";

$time = time();


$global['regular'] = Regular::init();
/**{@link Regular}*/
$regular = $global['regular'];


$global['response'] = Response::init();
/**{@link Response}*/
$response = $global['response'];