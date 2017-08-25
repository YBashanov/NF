/**
 * Объект типа {@link ArrayUnique}
 *
 * Ассоциативный массив, хранящий уникальные элементы и запоминающий количество записей уникальных элементов
 * т.е. если мы записываем 3 раза число 555, то это число будет храниться в 1 экземпляре, но счетчик по нему = 3
 *
 * <b>Методы</b>
 * {@link ArrayUnique.setElement} - Записать элемент в массив
 * {@link ArrayUnique.isElement} - Узнать, есть ли элемент в массиве
 * {@link ArrayUnique.getLength} - Получить количество одинаковых элементов
 * {@link ArrayUnique.getAll} - Получить массив элементов
 * {@link ArrayUnique.getValues} - Получить все значения одного ключа
 * {@link ArrayUnique.getUniqueValues} - Получить массив с уникальными значениями
 * {@link ArrayUnique.getUnUniqueValues} - Получить массив с неуникальными значениями
 *
 */
var _arrayUnique_;
if (! ArrayUnique) {

    /**
     * <b>Конструктор</b>. Создает js-объект {@link _arrayUnique_}
     *
     * <b>Параметры</b>
     * params : {}
     * - mode : length (default) - создает массив, в котором сохраняется пара ключ-значение + счетчик повторений этой пары
     *
     * <b>Возвращает</b>
     * {@link _arrayUnique_}
     */
    var ArrayUnique = function (params) {
        var me = this;

        if (params == undefined) params = {};
        if (params.mode == undefined) params.mode = "length";

        /*@private*/ var array = {
            length : 0
        };


        /**
         * Записать элемент в массив.
         * - Увеличивает счетчик, если ключ уже есть, и значение совпадает с новым
         * */
        me.setElement = function(key, value) {
            if (array[key]) {
                array[key].length++;

                if (array[key][value]) {
                    array[key][value].length++;
                }
                else {
                    array[key][value] = {
                        length : 1
                    };
                }
            }
            else {
                array.length++;

                array[key] = {
                    length : 1
                };
                array[key][value] = {
                    length : 1
                };
            }
        };

        /**
         * Узнать, есть ли элемент в массиве
         *
         * <b>Возвращает</b>
         * true/false
         * */
        me.isElement = function(key, value) {
            if (array[key]) {
                if (array[key][value]) {
                    return true;
                }
                else {
                    return false;
                }
            }
            else {
                return false;
            }
        };

        /**
         * Получить количество одинаковых элементов
         *
         * <b>Возвращает</b>
         * число или null
         * */
        me.getLength = function(key, value) {
            if (value == undefined) value = false;

            if (array[key]) {
                if (value) {
                    if (array[key][value]) {
                        return array[key][value].length;
                    }
                    else {
                        return null;
                    }
                }
                else {
                    return array[key].length;
                }
            }
            else {
                return null;
            }
        };


        /**
         * Получить массив элементов
         *
         * <b>Возвращает</b>
         * Array
         */
        me.getAll = function(){
            return array;
        };


        /**
         * Получить все значения одного ключа
         */
        me.getValues = function (key) {
            if (array[key]) {
                return array[key];
            }
            else {
                return null;
            }
        };


        /**
         * Получить массив с уникальными значениями
         */
        me.getUniqueValues = function(){
            var newArray = {
                length : 0
            };
            for (var val in array) {
                if (array[val].length == 1) {
                    newArray[val] = array[val];
                    newArray.length++;
                }
            }
            return newArray;
        };


        /**
         * Получить массив с неуникальными значениями
         */
        me.getUnUniqueValues = function(){
            var newArray = {
                length : 0
            };
            for (var val in array) {
                if (array[val].length > 1) {
                    newArray[val] = array[val];
                    newArray.length++;
                }
            }
            return newArray;
        };
    }
}