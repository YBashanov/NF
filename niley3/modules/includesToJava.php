<?php  if ( ! defined('andromed')) exit('');

include "{$separator}libraries/L_debug/index.php";
include "{$separator}modules/global.php";

if ($dbTrue) {
	include "{$separator}modules/db/All_connection.php";
}
include "{$separator}libraries/L_wrap/index.php";
include "{$separator}libraries/L_numGenerator/index.php";
include "{$separator}libraries/L_regular/index.php";

$generator = L_numGenerator::init($wrap);
$global['libraries']['generator'] = $generator;

$regular = L_regular::init ($wrap);
$global['libraries']['regular'] = $regular;

if ($dbTrue) {
	include "{$separator}modules/authorization/authorization_java.php";
}
$time = time();

if ($dbTrue && $global['user_online_is']) {
	include "{$separator}modules/session/cookies.php";
}
include "{$separator}data/variables.php";
?>