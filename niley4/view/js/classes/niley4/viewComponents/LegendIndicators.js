/**
 * Объект типа {@link LegendIndicators}
 * Наследуется от {@link _parent}
 *
 * <b>Методы</b>
 * {@link LegendIndicators.setBlock} - Установить значения в каком-либо из блоков
 * {@link LegendIndicators.hideBlock} - Скрыть какой-либо из блоков
 * */
var _legendIndicators_;
if (! LegendIndicators) {
    /**
     * <b>Конструктор</b>. Создает js-объект {@link _legendIndicators_}
     *
     *
     * <b>Связанные параметры родительского</b> конструктора:
     * {@link _Parent}
     *
     * <b>Параметры</b>
     * <b>params</b> объект:
     *    {
     *    }
     *
     * <b>Возвращает</b>
     * {@link _legendIndicators_}
     *
     * <b>Дополнительные файлы</b>
     * - css/services/legendIndicators.css
     */
    var LegendIndicators = function (params) {
        var me = this;
        _Parent.apply(me, arguments);

        if (params == undefined) params = {};


        /**
         * Установить значения в каком-либо из блоков
         *
         * <b>Параметры</b>
         * num - номер блока начиная с 0
         * name - верхний текст блока (мелкий)
         * text - текст блока (информация)
         */
        me.setBlock = function(num, name, value) {
            var root = me.getRoot();
            var item = root.find(".item" + num);
            if (value) {
                item.show();

                var _name = item.find(".title");
                var _text = item.find(".text");
                _name.html(name);
                _text.html(value);
            }
            else {
                item.hide();
            }
        };


        /**
         * Скрыть какой-либо из блоков
         *
         * <b>Параметры</b>
         * num - номер блока начиная с 0
         */
        me.hideBlock = function(num) {
            var root = me.getRoot();
            var item = root.find(".item" + num);

            item.hide();
        };


        /**
         * Собрать html-код
         * */
        me.createHTML = function(){
            return '<div class="legendIndicators">' +
                '<div class="underTopPanel">' +
                    //'<div class="border">&nbsp;</div>' +
                    '<div class="info">' +
                        '<div class="flo">' +
                            '<div class="block item0 mar_0">' +
                                '<div class="title"></div>' +
                                '<div class="name">' +
                                    '<div class="border">&nbsp;</div>' +
                                    '<div class="text"></div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="block item1">' +
                                '<div class="title"></div>' +
                                '<div class="name">' +
                                    '<div class="border">&nbsp;</div>' +
                                    '<div class="text"></div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="block item2">' +
                                '<div class="title"></div>' +
                                '<div class="name">' +
                                    '<div class="border">&nbsp;</div>' +
                                    '<div class="text"></div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="block item3">' +
                                '<div class="title"></div>' +
                                '<div class="name">' +
                                    '<div class="border">&nbsp;</div>' +
                                    '<div class="text"></div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="block item4">' +
                                '<div class="title"></div>' +
                                '<div class="name">' +
                                    '<div class="border">&nbsp;</div>' +
                                    '<div class="text"></div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="cle"></div>' +
                    '</div>' +
                '</div>' +
            '</div>';
        }
    };
}