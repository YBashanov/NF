<?php


/**
 * Сбор ошибок действия скриптов
 *
 * <b>Методы</b>
 * - {@link Error::add} - Добавить ошибку в список
 * - {@link Error::getErrors} - Получить лист ошибок
 * - {@link Error::clear} - Очистить лист ошибок
 */
class Error{
    /**singleton*/
    private static $thisObject = null;
    /**лист ошибок*/
    public $errors = array();


    /**
     * Получить объект singleton
     * */
    public static function init(){
        if ( self::$thisObject == null ){
            self::$thisObject = new Error();
            return self::$thisObject;
        }
        else {
            return self::$thisObject;
        }
    }


    /**
     * Добавить ошибку в список
     */
    public function add($message, $classWhichCalled=''){
        $this->errors[] = array(
            'message'=>$message,
            'date'=>date("Y.m.d H:i:s", time()),
            'classWhichCalled'=>$classWhichCalled
        );
    }


    /**
     * Получить лист ошибок
     */
    public function getErrors(){
        return $this->errors;
    }


    /**
     * Очистить лист ошибок
     */
    public function clear(){
        $this->errors = [];
    }
}