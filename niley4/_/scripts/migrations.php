<?php
/**
 * Файл наката миграций
 *
 * Если get-параметр 'file' содержит номер файла, накатываем поверх, без анализа таблицы migrations
 */

$separator = "../../";
include "{$separator}php/ajax/niley4/_ScriptControl.php";
include "{$separator}config/site/includes.php";

$dir = "migrations/";
$isTrue = false;


if ($_GET['file']) {
    $regular->search($_GET, ['num']);

    if ($regular->isTrue()) {
        $get = $regular->getResult();
        $files = [$get['file'] . '.sql'];
        $isTrue = true;
    }
    else {
        echo "<pre style='color:red'>";
        echo "Неверное имя файла. Ожидает только цифры";
        echo "</pre>";
    }
}
// обычное сканирование директории с файлами миграций
else {
    $files = scandir($dir);
    $isTrue = true;
}


if ($isTrue) {
    //проверяем таблицу migrations
    $table = $config['prefix'] . 'migrations';
    $migrations = $db->select($table, 'true', '*');

    if ($files) {
        $count = count($files);
        for ($i = 0; $i < $count; $i++) {
            $file = $files[$i];

            if (
                $file != "."
                && $file != ".."
            ) {
                $a_file = explode(".", $file);

                if ($a_file[1] == "sql") {

                    $isFile = false;

                    //сравнение с таблицей миграций
                    if ($migrations) {
                        foreach ($migrations as $val) {
                            //сравниваем имена файлов
                            if ($val['file_name'] == $a_file[0]) {
                                $isFile = true;
                                break;
                            }
                        }
                    }

                    if (!$isFile) {
                        $fullfile = "{$dir}{$a_file[0]}.sql";
                        fireQuery($fullfile, $a_file[0], $db, $error, $config);
                    }
                }
            }
        }
    }
}


/**
 * Выполнить query-запрос для конкретного файла
 */
function fireQuery($file, $fileName, $db, $error, $config){
    if (@file_exists($file)) {
        $query = file_get_contents($file);

        if ($db->query($query)){
            echo "<pre style='color:blue'>";
            echo "Файл {$file}";
            echo "</pre>";
            echo "<pre style='color:gray'>";
            echo $query;
            echo "</pre>";
            echo "<pre style='color:green'>";
            echo "Выполнено";
            echo "</pre>";


            //Запись данных о миграции в БД
            $table = $config['prefix'] . 'migrations';
            $data = [
                'file_name' => $fileName,
                'date' => date("Y.m.d, H:i:s", time())
            ];
            if ($db->insert($table, $data)) {
                echo "<pre style='color:blue'>";
                echo "+ Запись данных о миграции {$file}";
                echo "</pre>";
                echo "<pre style='color:green'>";
                echo "Выполнено";
                echo "</pre>";
            }
            else {
                echo "<pre style='color:blue'>";
                echo "+ Запись данных о миграции {$file}";
                echo "</pre>";
                echo "<pre style='color:red'>";
                echo $error->getErrors()[0]['message'];
                echo "</pre>";
                $error->clear();
            }
        }
        else {
            echo "<pre style='color:blue'>";
            echo "Файл {$file}";
            echo "</pre>";
            echo "<pre style='color:gray'>";
            echo $query;
            echo "</pre>";
            echo "<pre style='color:red'>";
            echo $error->getErrors()[0]['message'];
            echo "</pre>";
            $error->clear();
        }
    }
    else {
        echo "Файл миграций {$file} не найден";
    }
}