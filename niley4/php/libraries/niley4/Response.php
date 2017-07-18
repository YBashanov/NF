<?php
/*
Пример использования

$resp = new Response(); //создание экземпляра, а не статическое обращение (чтобы не было перетирания при
                        //одновременном обращении к объекту
$resp->clearResponse(); //чистим от предыдущих запросов

$resp->setSuccess(true); //при очистке - false, поэтому надо success задать явно (в случае норм.ответа)
$resp->setData($table->attributes); //сюда массив объектов или объект

$resp->setError("текст", __CLASS__); //если ошибка - пишем ее

return $resp->getResponse(); // самое последнее действие - возвращаем клиенту ответ
*/





/**
 * Формирует ответ определенного формата (ответ сервера клиенту)
 *
 * <b>Методы</b>
 * - {@link Response::clearResponse} - Очистить массив ответа
 * - {@link Response::setSuccess} - Установить успешность/неуспешность формирования ответа
 * - {@link Response::setData} - Записать в ответ данные
 * - {@link Response::setError} - Добавить ошибку в массив ошибок ответа
 * - {@link Response::getResponse} - Получить сформированный json
 *
 * <b>Формат ответа</b>
 * - success - true/false
 * - data - Array
 * - error - Array
 * - errorToDb - Array
 * - place - Array
 */
class Response
{
    /**singleton*/
    private static $thisObject = null;


    /**
     * Массив ответа серверу
     */
    private $response = [
        //успешный-неуспешный ответ
        "success"=>false,
        //данные
        "data"=>[],

        "error"=>[],
        "place"=>[]
    ];


    /**
     * Получить объект singleton
     * */
    public static function init(){
        if ( self::$thisObject == null ){
            self::$thisObject = new Response();
            return self::$thisObject;
        }
        else {
            return self::$thisObject;
        }
    }


    /**
     * Очистить массив ответа
     */
    public function clearResponse(){
        $this->response['success'] = false;
        $this->response['data'] = [];
        $this->response['error'] = [];
        $this->response['place'] = [];
    }


    /**
     * Установить успешность/неуспешность формирования ответа
     */
    public function setSuccess($bool = true){
        $this->response['success'] = $bool;
    }


    /**
     * Записать в ответ данные
     */
    public function setData($data){
        $this->response['data'] = $data;
    }


    /**
     * Добавить ошибку в массив ошибок ответа
     *
     * <b>Параметры</b>
     * - errorToUser - (массив) текст ошибки для вывода пользователю
     * - place - __CLASS__ - место где произошла ошибка
     * - errorToDb - текст ошибки для записи в базу
     *
     * <b>Пример</b>
     *
     * $response->setError($error->getErrors(), $post['script'] . '/' . $post['name']);
     */
    public function setError($errorToUser, $place = ""){
        array_push($this->response['error'], $errorToUser);
        array_push($this->response['place'], $place);
    }


    /**
     * Получить сформированный json
     */
    public function getResponse(){
        return json_encode($this->response);
    }
}
