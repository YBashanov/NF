/**
 * Объект типа {@link StatisticPanel}
 * Наследуется от {@link _parent}
 *
 * <b>Методы</b>
 * {@link StatisticPanel.setLabel} - установить надпись
 * {@link StatisticPanel.setBigNum} - установить большое число
 * {@link StatisticPanel.setThreeNums} - установить три числа
 * {@link StatisticPanel.setColorPanel} - установить цвета цветовой панели
 *
 * <b>Фотография</b>
 * {@link http://be-in-info.ru/files/lux/templates/statisticPanel.jpg}
 * */
var statisticPanel;
if (! StatisticPanel) {
    /**
     * <b>Конструктор</b>. Создает js-объект {@link statisticPanel}
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
     * {@link statisticPanel}
     *
     * <b>Пример</b>
     * var {@link statisticPanel} = new StatisticPanel({});
     *
     * <b>Дополнительные файлы</b>
     * - css/services/statisticPanel.css
     * - img/services/statisticPanel/[all]
     *
     * @return statisticPanel
     */
    var StatisticPanel = function (params) {
        var me = this;
        _Parent.apply(me, arguments);

        if (params == undefined) params = {};


        /**
         * Установить надпись
         * */
        me.setLabel = function(label){
            var root = me.getRoot();
            var div = root.find("#info");

            div.html(label);
        };


        /**
         * Установить большое число
         *
         * <b>Параметры</b> (принимает Целые числа)
         * - num - большое число
         * - delta - дельта большого числа
         * Если delta ниже нуля, то выводится без минуса (модуль).
         * Стрелка становится красной и смотрит вниз
         *
         * <b>Фотография</b>
         * {@link http://be-in-info.ru/files/lux/templates/statisticPanel.setBigNum.jpg}
         * */
        me.setBigNum = function(num, delta){
            if (num == undefined) num = 0;
            if (delta == undefined) delta = 0;

            num = parseInt(num);
            delta = parseInt(delta);

            var root = me.getRoot();
            var numDiv = root.find("#bigDelta .num");
            var numDelta = root.find("#bigDelta .numDelta");
            var triagnle = root.find("#bigDelta .triangle");


            var color = "green";
            if (delta >= 0) {
                numDelta.removeClass("red").addClass("green");
                triagnle.removeClass("red").addClass("green");
            }
            else if (delta < 0) {
                numDelta.removeClass("green").addClass("red");
                triagnle.removeClass("green").addClass("red");
            }

            numDiv.html(num);
            numDelta.html(Math.abs(delta));
        };


        /**
         * Установить три числа
         *
         * <b>Параметры</b> (принимает массивы Целых чисел)
         * - numArray - числа. Если = null, то блок целиком скрывается
         * - deltaArray - дельты чисел
         * Если delta ниже нуля, то выводится без минуса (модуль).
         * Стрелка становится красной и смотрит вниз
         *
         * <b>Реализация</b>
         * Внутренний массив проходит по количеству элементов в numArray. На количество deltaArray не обращает внимания.
         *
         * <b>Фотография</b>
         * {@link http://be-in-info.ru/files/lux/templates/statisticPanel.setThreeNums.jpg}
         * */
        me.setThreeNums = function(numArray, deltaArray){
            if (numArray === undefined) numArray = [];
            if (deltaArray === undefined) deltaArray = [];

            var root = me.getRoot();

            if (numArray === null) {
                root.find(".rightblock").hide();
                return;
            }
            else {
                root.find(".rightblock").show();
            }

            var length = numArray.length;
            var num = 0;
            var delta = 0;
            for (var i=0; i<length; i++) {
                if (! deltaArray[i]) deltaArray[i] = 0;

                num = parseInt(numArray[i]);
                delta = parseInt(deltaArray[i]);

                var block_i = root.find("#point_min_"+i);
                var image_i = root.find("#image_"+i);

                var numDiv = block_i.find(".num");
                var numDelta = block_i.find(".numDelta");
                var triagnle = block_i.find(".triangle");

                if (delta >= 0) {
                    numDelta.removeClass("red").addClass("green");
                    triagnle.removeClass("red").addClass("green");
                }
                else if (delta < 0) {
                    numDelta.removeClass("green").addClass("red");
                    triagnle.removeClass("green").addClass("red");
                }

                if (! num) {
                    block_i.hide();
                    image_i.hide();
                }
                else {
                    block_i.show();
                    image_i.show();
                }

                numDiv.html(num);
                numDelta.html(Math.abs(delta));
            }
        };


        /**
         * Установить цвета цветовой панели
         *
         * <b>Параметры</b>
         * - params - Объект вида:
         * {
         *   highcritical : 0,
         *   critical : 0,
         *   high : 0,
         *   medium : 0,
         *   low : 0,
         *   total : 0
         * }
         *
         * */
        me.setColorPanel = function(params){
            var root = me.getRoot();
            var gradients = root.find(".gradients");

            var totalWidth = parseInt(gradients.css("width"));
            gradients.find(".gradient").css({width : 0});

            var result = [];
            result.push( (params.highcritical / params.total) * totalWidth );
            result.push( (params.critical / params.total) * totalWidth );
            result.push( (params.high / params.total) * totalWidth );
            result.push( (params.medium / params.total) * totalWidth );
            result.push( (params.low / params.total) * totalWidth );
            var length = result.length;

            for (var i=0; i<length; i++) {
                gradients.find(".gradient"+i).css({width : result[i] + "px"});
            }
        };


        /**
         * Собрать html-код
         * */
        me.createHTML = function(){
            var html = '<div class="statisticPanel" id="'+me.getId()+'">' +
                    '<div class="background"></div>' +
                    '<div id="info">Макрорегионы</div>' +
                    '<div class="statisticOne">' +
                        '<div class="flo">' +
                            '<div class="big flo" id="bigDelta">' +
                                '<div class="background">&nbsp;</div>' +
                                '<div class="content">' +
                                    '<div class="num flo">0</div>' +
                                    '<div class="percent flo">%</div>' +
                                    '<div class="delta flo">' +
                                        '<div class="triangle green"></div>' +
                                        '<div class="numDelta green">0</div>' +
                                    '</div>' +
                                    '<div class="cle"></div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="flo_r rightblock">' +
                            '<div class="flo images">' +
                                '<div class="image" id="image_0"><img src="img/map/arrow_right.png"/></div>' +
                                '<div class="image" id="image_1"><img src="img/map/arrow_left.png"/></div>' +
                                '<div class="image" id="image_2"><img src="img/map/arrow_direction.png"/></div>' +
                            '</div>' +
                            '<div class="flo points">' +
                                '<div class="background">&nbsp;</div>' +
                                '<div class="content">' +
                                    '<div class="point min" id="point_min_0">' +
                                        '<div class="delta flo_r">' +
                                            '<div class="triangle green"></div>' +
                                            '<div class="numDelta green">0</div>' +
                                        '</div>' +
                                        '<div class="num flo_r">0</div>' +
                                        '<div class="cle_r"></div>' +
                                    '</div>' +
                                    '<div class="point min" id="point_min_1">' +
                                        '<div class="delta flo_r">' +
                                            '<div class="triangle green"></div>' +
                                            '<div class="numDelta green">0</div>' +
                                        '</div>' +
                                        '<div class="num flo_r">0</div>' +
                                        '<div class="cle_r"></div>' +
                                    '</div>' +
                                    '<div class="point min" id="point_min_2">' +
                                        '<div class="delta flo_r">' +
                                            '<div class="triangle green"></div>' +
                                            '<div class="numDelta green">0</div>' +
                                        '</div>' +
                                        '<div class="num flo_r">0</div>' +
                                        '<div class="cle_r"></div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="cle"></div>' +
                        '</div>' +
                        '<div class="cle"></div>' +
                        '<div class="cle_r"></div>' +

                        '<div class="gradients">' +
                            '<div class="gradient gradient0"></div>' +
                            '<div class="gradient gradient1"></div>' +
                            '<div class="gradient gradient2"></div>' +
                            '<div class="gradient gradient3"></div>' +
                            '<div class="gradient gradient4"></div>' +
                        '</div>' +
                    '</div>' +
                '</div>';

            return html;
        }
    };
}