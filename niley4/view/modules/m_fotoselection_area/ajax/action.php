<?php
$separator = "../../../../../";
include "{$separator}ajax/_ScriptControl.php";
include "{$separator}modules/includesToAjax.php";
include('resize_crop.php');

$thisFile = "m_fotoselection_area//action";

function prov($per){
	if (isset($per)){
		$per = stripslashes($per);
		$per = htmlspecialchars($per);
		$per = addslashes($per);		 
	}
	return $per;
}


if(isset($_POST)){
    $id = $regular->num($_POST['id']);

    if ($id !== false){

        $table = "{$config["prefix"]}fotos";
		$where = "NOT(`deleted`) AND `id`='{$id}'";
		$what = "*";
		$fotos = $db->select_line ($table, $where, $what, $thisFile);
        
        if ($fotos) {
            $f_orig = $separator . 'uploads/fotos/' . $fotos['file'] . '.' . $fotos['file_ext'];
            $f_d = $separator . 'uploads/fotos/d_' . $fotos['file'] . '.' . $fotos['file_ext'];
            
            $arr_f_orig = getimagesize($f_orig);
            $arr_f_d = getimagesize($f_d);
            
            $filenew = time().rand(100,999).'.'.$fotos['file_ext'];
            $f_filenew = $separator . 'uploads/fotos_resize/' . $filenew;
            
            $x1 = prov($_POST['x1']);
            $x2 = prov($_POST['x2']);
            $y1 = prov($_POST['y1']);
            $y2 = prov($_POST['y2']);

            //нужно высчитать отношение рисунков - оригинального и d_
            //а потом преобразовать x, y
            
            $dx = $arr_f_orig[0]/$arr_f_d[0];
            $dy = $arr_f_orig[1]/$arr_f_d[1];
            
            $dx1 = round($x1 * $dx);
            $dx2 = round($x2 * $dx);
            $dy1 = round($y1 * $dy);
            $dy2 = round($y2 * $dy);
            
            //crop($f_d, $f_filenew, array($x1, $y1, $x2, $y2));
            crop($f_orig, $f_filenew, array($dx1, $dy1, $dx2, $dy2));
            
            $data = array(
                "user_online_id"=>$global['user_online']['id'],
                "pre_foto"=>$filenew
            );
            if ($db->insert("{$config['prefix']}orders_pre", $data, $thisFile)) {
                echo '{"success" : "true", "file" : "'.$f_filenew.'"}';
            }
            else {
                echo '{"success" : "false", "message" : "Ошибка сервера"}';
                $wrap->add (3, "insert: Не удалось добавить строку в таблицу", $thisFile);
            } 
        }
        else {
            echo '{"success" : "false", "message" : "Ошибка сервера***"}';
        }
    }
    else {
        echo '{"success" : "false", "message" : "Ошибка сервера**"}';
    }
}
else {
    echo '{"success" : "false", "message" : "Ошибка сервера*"}';
}
?>