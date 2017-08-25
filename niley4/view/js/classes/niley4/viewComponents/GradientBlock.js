/**
 * Объект типа {@link GradientBlock}
 * Наследуется от {@link _parent}
 *
 * <b>Методы</b>
 * {@link GradientBlock.getGradient} - Получить div, в котором рисуется градиент
 * */
var _gradientBlock_;
if (! GradientBlock) {
    /**
     * <b>Конструктор</b>. Создает js-объект {@link _gradientBlock_}
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
     * {@link _gradientBlock_}
     *
     * <b>Дополнительные файлы</b>
     * - css/services/gradientBlock.css
     *
     * @return _gradientBlock_
     */
    var GradientBlock = function (params) {
        var me = this;
        _Parent.apply(me, arguments);

        if (params == undefined) params = {};


        /**
         * Получить div, в котором рисуется градиент
         */
        me.getGradient = function(){
            var root = me.getRoot();
            return root.find(".gradient");
        };

        /**
         * Собрать html-код
         * */
        me.createHTML = function(){
            var html = '<div class="gradientBlock">' +
                '<div class="gradient"></div>' +
                '<div class="ticks">' +
                    '<div class="tick">0</div>' +
                    '<div class="tick">20</div>' +
                    '<div class="tick">40</div>' +
                    '<div class="tick">60</div>' +
                    '<div class="tick">80</div>' +
                    '<div class="tick">100%</div>' +
                    '<div class="cle"></div>' +
                '</div>' +
            '</div>';

            return html;
        }
    };
}