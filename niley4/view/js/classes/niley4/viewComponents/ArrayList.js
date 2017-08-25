/**
 * Объект типа {@link ArrayList}
 *
 * Хранит в себе ассоциативный массив объектов заданной длины.
 * Если число объектов переваливает за максимальное, то самый старый элемент удаляется (настраиваемо)
 *
 * <b>Методы</b>
 * {@link ArrayList.getElement} - получить элемент по имени
 * {@link ArrayList.setElement} - записать элемент в массив
 */
var _arrayList_;
if (! ArrayList) {

    /**
     * <b>Конструктор</b>. Создает js-объект {@link _arrayList_}
     *
     * <b>Параметры</b>
     * <b>params</b> объект:
     *    {
     *      - length - задаем длину массива. default=0.
     *      Если не задан, то массив не имеет ограничений и наполняется бесконечно
     *    }
     *
     * <b>Возвращает</b>
     * {@link _arrayList_}
     */
    var ArrayList = function (params) {
        var me = this;

        if (params == undefined) params = {};
        if (params.length == undefined) params.length = 0;

        /*@private*/ var lengthSet = params.length; //заданная длина массива
        /*@private*/ var lengthMe = 0; //текущая длина массива
        /*@private*/ var array = {};


        /**
         * Записать элемент в массив
         *
         * <b>Возвращает</b>
         * - true, если элемент записан
         * */
        me.setElement = function(key, value) {
            if (lengthSet > 0 && lengthMe >= lengthSet) {
                deleteOldest();
            }

            if (! array[key]) {
                lengthMe++;
            }

            array[key] = {
                time: getTime(),
                value: value
            };
            return true;
        };

        /**
         * Получить элемент по ключу
         *
         * <b>Возвращает</b>
         * элемент, сохраненный в массиве по данному ключу, либо null
         * */
        me.getElement = function(key) {
            if (array[key]) {
                return array[key].value;
            }
            else {
                return null;
            }
        };

        /**
         * @private
         * Удалить самый старый элемент (= который был записан первым)
         * -
         * <b>Возвращает</b>
         * true, если операция завершена
         * false, если нет
         */
        function deleteOldest(){
            var oldestTime = getTime();
            var oldestKey = null;

            for (var key in array) {
                if (oldestTime > array[key].time) {
                    oldestTime = array[key].time;
                    oldestKey = key;
                }
            }

            if (oldestKey) {
                array[oldestKey] = null;
                delete array[oldestKey];
                lengthMe--;
                return true;
            }
            return false;
        }

        /**
         * @private
         * Получить количество миллисекунд настоящего времени
         */
        function getTime(){
            return (new Date()).getTime();
        }
    }
}