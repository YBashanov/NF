<?php
//показывает новую или отредактированную запись
$separator = "../../../../../";
include "{$separator}ajax/_ScriptControl.php";
include "{$separator}modules/includesToAjax.php";

$thisFile = "m_mailing/show";

$template->setPath("modules/m_mailing/index_save.html");
$in = $template->parse();

echo "1|".$in."|";

include "{$separator}error/index.php";
?>