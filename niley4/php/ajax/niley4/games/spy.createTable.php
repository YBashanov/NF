<?php

//insert
if (!$post['data']['id']) {
    $table = "spy_tables";
    $data = [
        "name" => "123"
    ];
    if ($db->insert($table, $data)) {
//        echo 1;
        $response->setSuccess();
    }
    else {
        $response->setError($error->getErrors(), $post['script'] . '/' . $post['name']);
    }
}
//update
else {

}