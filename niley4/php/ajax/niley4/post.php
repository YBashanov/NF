<?php
include "_ScriptControl.php";
include "_ScriptList.php";
include "{$separator}config/site/includesToAjax.php";

/**
 * Формат входящего запроса (POST)
 * Изменение данных
 *
 * [
 *  script => 'games/spy' - путь до файла сценариев
 *  name => 'createTable' - действие в этом файле
 *  data => [] - данные на insert. Если есть id - это update
 * ]
 */

/***/
$regular->search($_POST);
$post = array();


if ($regular->isTrue()) {
    $post = $regular->getResult();

    if ($post['script']) {
        //загрузка файла сценария
        if (@file_exists($scriptList[$post['script']])) {
            include $scriptList[$post['script']];
        }
    }
}
else {
    $response->setError($regular->getErrors(), 'post.php');
}


echo $response->getResponse();