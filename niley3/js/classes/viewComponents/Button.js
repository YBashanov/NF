/**
 * Объект типа {@link Button}
 * Наследуется от {@link _parent}
 *
 * <b>Методы</b>
 * {@link Button.wait} - включить прелоадер
 * {@link Button.stopWait} - выключить прелоадер
 *
 * <b>События</b>. Для расширения логики используй {@link _Parent.events}
 *
 * */
var button;
if (! Button) {
    /**
     * <b>Конструктор</b>. Создает js-объект {@link button}
     *
     * <b>Связанные параметры родительского</b> конструктора:
     * {@link _Parent}
     *
     * <b>Параметры</b>
     * <b>params</b> объект:
     *    {
     *    - [nameLabel] - надпись мелкими буквами поверх button-a (заголовок). default=''
     *    - [text] - Текст кнопки. default='Button'
     *    }
     *
     * <b>Возвращает</b>
     * {@link button}
     *
     * <b>Пример</b>
     * var {@link button} = new Button();
     *
     * <b>Дополнительные файлы</b>
     * - css/services/button.css
     *
     * @return button
     */
    var Button = function (params) {
        var me = this;
        _Parent.apply(me, arguments);

        if (params == undefined) params = {};
        if (params.nameLabel == undefined)  params.nameLabel = "";
        if (params.text == undefined)       params.text = "Button";

        /*@private*/ var nameLabel  = params.nameLabel;
        /*@private*/ var text       = params.text;


        /**
         * Инициирует событие клик по {@link button}-у
         *
         * <b>Параметры</b>
         * - e - event
         *
         * <b>Параметры</b> расширяющего метода через {@link _Parent.events}
         * - event
         * - объект Button
         * */
        me.click = function (e) {
            if (me.customEvents && me.customEvents.click) {
                me.customEvents.click(e, me);
            }
        };


        /**
         * Создание html-элемента button
         * */
        me.createHTML = function(){
            return '<div class="button '+me.getClassName()+'" id="'+me.getId()+'">'
                + '<div class="nameLabel">'+nameLabel+'</div>'
                + '<div class="_button" onclick="_Parent.prototype._get(\''+me.getId()+'\').click(event)">'+text+'</div>'
                + '<div class="cle"></div>'
                + '</div>';
        };


        /**
         * Прелоадер загрузки при запросе
         */
        me.wait = function (){
            var root = me.getRoot();
            var button = root.find("._button");

            button.html('<img class="wait" src="img/services/button/wait2.gif" />');
        };


        /**
         * Убрать прелоадер загрузки
         * */
        me.stopWait = function(){
            var root = me.getRoot();
            var button = root.find("._button");

            button.html(text);
        }
    };
}