<?php
$separator = "../../../../../";
include "{$separator}ajax/_ScriptControl.php";
include "{$separator}modules/includesToAjax.php";


$thisFile = "personal/fotos_open";

if (true) {
	if ($dbTrue) {
        $table = "{$config["prefix"]}fotos";
        $where = "NOT(`deleted`) AND `user_online_create`>0 AND `user_online_create`='{$global['user_online']['id']}'";
        $where .= " ORDER BY `id` ASC";
        $what = "id";
        $fotos = $db->select ($table, $where, $what, "id", $thisFile);
        
        if ($fotos) {
            module_(INDEX_DYNAMIC, array(), "m_upload_menu/index_dynamic");
            module__dynamic(INDEX_DYNAMIC, "m_upload_menu/index_dynamic", $fotos, false, 1);
            module_(INDEX_BLOCK, array(), "m_upload_menu/index_block");
            $in = $template->getParse(INDEX_BLOCK);
            
            
            $length = count($fotos);
            echo '{"success":"true","length":"'.$length.'", "data":"'.$in.'"}';
        }
        else {
            echo '{"success":"false","message":"Нет данных"}';
        }
    }
    else {
        echo '{"success":"false","message":"Нет доступа к базе"}';
    }
}
else {
	echo '{"success":"false","message":"Не вышло"}';
	$wrap->add (0, "Попытка совершить выход неавторизованным пользователем", $thisFile);
}

include "{$separator}error/index.php";
?>