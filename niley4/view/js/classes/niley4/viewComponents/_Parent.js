/**
 * Объект типа {@link _Parent}
 * Расширяет функционал текущих объектов.
 *
 * <b>Методы</b>
 * - Внешнее использование
 * <b>{@link _Parent.draw}</b> - вставить объект в jquery-элемент. Главный метод
 * {@link _Parent.getHTML} - Возвращает html-код для ручной вставки в объект
 * {@link _Parent.getVariables} - получить объект значений: {key1 : value1...}
 * {@link _Parent.show} - отобразить
 * {@link _Parent.hide} - скрыть
 * {@link _Parent.events} - добавляет к событиям дополнительную логику
 *
 * - Внутреннее использование
 * {@link _Parent.getId} - получить уникальное имя объекта
 * {@link _Parent.getValue} - получить значение этого объекта
 * {@link _Parent.setValue} - установить значение объекта (используется внутри)
 * {@link _Parent.getClassName} - получить имя класса
 * {@link _Parent.getElement} - получить элемент jquery - контейнер
 * {@link _Parent.getMe} - получить объект по имени
 * {@link _Parent.getRoot} - получить элемент jquery - корневой html-элемент объекта
 * {@link _Parent.getRootElement} - Получить первый элемент после корневого (первый элемент в верстке)
 * */
var _parent;
if (! _Parent) {
    /**
     * <b>Конструктор</b>. Создает js-объект {@link _parent}
     * Не вызывается напрямую. Используется для расширения функционала текущих объектов
     *
     * <b>Параметры</b>
     * <b>params</b> объект:
     *    {
     *      - jQueryElement - элемент, в который будет вставлен текущий объект для отображения
     *      - [key] - ключ (имя переменной), в который запишется значение этого объекта (имя переменной при отправке на сервер)
     *      Если не указан, значение запишется в id
     *      - [className] - имя класса, если требуется изменить визуализацию объекта для определенных условий (подвязать стили)
     *    }
     *
     * <b>Возвращает</b>
     * {@link _parent}
     *
     * <b>Пример</b>
     * Не вызывается напрямую
     */
    var _Parent = function (params) {
        var me = this;
        if (params == undefined) params = {};
        if (params.jQueryElement == undefined)  params.jQueryElement = null;
        if (params.className == undefined)      params.className = "";

        /*@private*/ var element = null;
        /*@private*/ var id = _Parent.prototype._getId();
        if (params.key == undefined)  params.key = id;
        /*@private*/ var key        = params.key;
        /*@private*/ var value;//нельзя задать извне параметром params
        /*@private*/ var className  = params.className;

        setElement(params.jQueryElement);
        saveMe(id);



        /**
         * Получить jquery-элемент, контейнер
         * в котором располагается html-код объекта
         *
         * <b>Возвращает</b>
         * jquery Element
         * */
        me.getElement = function(){
            return element;
        };


        /**
         * @private
         * Установить jquery-контейнер
         * */
        function setElement(jQueryElement){
            element = jQueryElement;
        }


        /**
         * Получить уникальное имя объекта {@link _parent}
         *
         * <b>Возвращает</b>
         * String
         */
        me.getId = function(){
            return id;
        };


        /**
         * Получить ключ объекта {@link _parent}
         *
         * <b>Возвращает</b>
         * String
         */
        me.getKey = function(){
            return key;
        };


        /**
         * Получить значение объекта {@link _parent}
         *
         * <b>Возвращает</b>
         * String
         */
        me.getValue = function(){
            return value;
        };


        /**
         * Установить значение объекта {@link _parent}
         * */
        me.setValue = function(_value){
            value = _value;
        };


        /**
         * Получить объект {@link _parent} по уникальному имени
         *
         * <b>Параметры</b>
         * - String id - уникальное имя объекта
         *
         * <b>Возвращает</b>
         * {@link _parent}
         * */
        me.getMe = function(id){
            if (_Parent.prototype._objects && _Parent.prototype._objects[id]) {
                return _Parent.prototype._objects[id];
            }
            else return null;
        };


        /**
         * Получить имя класса стилей
         *
         * <b>Возвращает</b>
         * String
         */
        me.getClassName = function(){
            return className;
        };


        /**
         * @private
         * Сохранить объект в списке объектов
         * */
        function saveMe(id){
            if (! _Parent.prototype._objects) {
                _Parent.prototype._objects = {};
            }
            _Parent.prototype._objects[id] = me;
        }


        /**
         * Получить корневой jquery-элемент html-блока.
         * На него нельзя нанести свойства (например, анимации), т.к. и стилей у него нет
         *
         * <b>Возвращает</b>
         * jquery-элемент
         * */
        me.getRoot = function(){
            return $("#" + me.getId());
        };


        /**
         * Получить первый элемент после корневого (первый элемент в верстке)
         * Удобно для анимации и добавления динамических стилей
         *
         * <b>Возвращает</b>
         * jquery-элемент
         */
        me.getRootElement = function(){
            var root = me.getRoot();

            return root.find("div:first");
        };


        /**
         * Отобразить элемент jquery вместе с находящимся в нём текущим объектом
         * */
        me.show = function(){
            if (element) element.show();
        };


        /**
         * Скрыть элемент jquery вместе с находящимся в нём текущим объектом
         * */
        me.hide = function(){
            if (element) element.hide();
        };


        /**
         * Создает html-элемент и помещает его в juery-элемент.
         * -
         * <b>Реализация</b>
         * В каждом дочернем элементе должен быть метод createHTML(), который вернет html-код
         * -
         * <b>Возвращает</b>
         * Объект контекст (объект, у которого вызван данный метод)
         *
         * @return me
         * */
        me.draw = function () {
            var jQueryElement = me.getElement();

            if (! jQueryElement) {
                c('draw: Не задан элемент для вставки объекта.');
            }
            else {
                jQueryElement.append('<div id="'+me.getId()+'">' + me.createHTML() + '</div>');
            }
            return me;
        };


        /**
         * Возвращает html-код для ручной вставки в объект
         * (без добавления в jquery-объект)
         */
        me.getHTML = function (){
            return '<div id="'+me.getId()+'">' + me.createHTML() + '</div>';
        };


        /**
         * Получить объект, содержащий ключи и значения всех элементов, созданных через данный конструктор
         *
         * Вызывается из любого дочернего объекта
         * -
         * <b>Возвращает</b>
         * Объект с парами key-значение
         * Если ключ key у объекта не был задан, то его пара будет: id-значение
         *
         * <b>Далее</b>
         * необходимо определить группу объектов, чьи значения хотим получать
         *
         * @return Object
         * */
        me.getVariables = function(){
            var newObject = {};
            var objects = _Parent.prototype._objects;
            if (objects) {
                for (var val in objects) {
                    newObject[objects[val].getKey()] = objects[val].getValue();
                }
            }
            return newObject;
        };


        /**
         * Добавляет к событиям объекта дополнительную логику
         *
         * <b>Параметры</b>
         * - events - объект
         * перечисление всех расширяющих методов с реализацией расширения
         * Набор параметров расширяющего метода смотри в описании расширяемого метода.
         *
         * <b>Пример</b>
         * {@link _select_}.events({
         *      clickOption : function(e, me, value) {
         *          alert(value);
         *     }
         * });
         *
         *
         * */
        me.events = function(events){
            if (! me.customEvents) {
                me.customEvents = events;
            }
            //если этот объект уже есть
            else {
                for (var key in events) {
                    me.customEvents[key] = events[key];
                }
            }
        };
    };
}
/**
 * Класс: {@link _Parent}
 * Авто-создание уникального id (имени объекта)
 */
_Parent.prototype._getId = function(){
    var prefix = "_elementName_";
    var id = -1;
    return function(){
        id++;
        return prefix + id;
    }
}();
/**
 * Класс: {@link _Parent}
 * Используется в html-коде для получения объекта
 * чтобы повесить событие в html-шаблоне
 *
 * <b>Параметры</b>
 * - String id - уникальное имя объекта
 * */
_Parent.prototype._get = function(id){
    if (_Parent.prototype._objects && _Parent.prototype._objects[id]) {
        return _Parent.prototype._objects[id];
    }
    else return null;
};

