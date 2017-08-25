/**
 * Объект типа {@link StatisticPanel}
 * Наследуется от {@link _parent}
 *
 * <b>Методы</b>
 * {@link StatisticPanel.setLabel} - установить главную надпись
 * {@link StatisticPanel.setTitle} - установить надпись над большим числом
 * {@link StatisticPanel.setBigNum} - установить большое число
 * {@link StatisticPanel.setThreeNums} - установить три числа
 * {@link StatisticPanel.setColorPanel} - установить цвета цветовой панели
 * {@link StatisticPanel.setAll} @dependent - установить сразу все параметры
 * {@link StatisticPanel.showNumerals} - Показать блок со всеми числами
 * {@link StatisticPanel.hideNumerals} - Скрыть блок со всеми числами
 *
 * <b>Фотография</b>
 * {@link http://be-in-info.ru/files/lux/templates/_statisticPanel_.jpg}
 * */
var _statisticPanel_;
if (! StatisticPanel) {
    /**
     * <b>Конструктор</b>. Создает js-объект {@link _statisticPanel_}
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
     * {@link _statisticPanel_}
     *
     * <b>Пример</b>
     * var {@link _statisticPanel_} = new StatisticPanel({});
     *
     * <b>Дополнительные файлы</b>
     * - css/services/_statisticPanel_.css
     * - img/services/_statisticPanel_/[all]
     *
     * @return _statisticPanel_
     */
    var StatisticPanel = function (params) {
        var me = this;
        _Parent.apply(me, arguments);

        if (params == undefined) params = {};


        /**
         * Установить главную надпись
         * */
        me.setLabel = function(label){
            var root = me.getRoot();
            var div = root.find("#info");

            div.html(label);
        };


        /**
         * Установить надпись над большим числом
         */
        me.setTitle = function(text){
            var root = me.getRoot();
            var div = root.find(".numName");

            div.html(text);
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
         * {@link http://be-in-info.ru/files/lux/templates/_statisticPanel_.setBigNum.jpg}
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
         * {@link http://be-in-info.ru/files/lux/templates/_statisticPanel_.setThreeNums.jpg}
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
            var result = [];
            var length;

            var root = me.getRoot();
            var gradients = root.find(".gradients");
            var i;

            if (params) {
                var totalWidth = parseInt(gradients.css("width"));
                gradients.find(".gradient").css({width: 0});
                gradients.show();

                result.push((params.radius1 / params.total) * totalWidth);
                result.push((params.radius2 / params.total) * totalWidth);
                result.push((params.radius3 / params.total) * totalWidth);
                result.push((params.radius4 / params.total) * totalWidth);
                result.push((params.radius5 / params.total) * totalWidth);
                length = result.length;

                for (i = 0; i < length; i++) {
                    gradients.find(".gradient" + i).css({width: result[i] + "px"});
                }
            }
            else {
                result = [0, 0, 0, 0, 0];
                length = result.length;

                for (i = 0; i < length; i++) {
                    gradients.find(".gradient" + i).css({width: 0});
                }
            }
        };


        /**
         * Сбросить цвета цветовой панели (Спрятать)
         * */
        me.resetColorPanel = function(){
            var root = me.getRoot();
            var gradients = root.find(".gradients");
            gradients.hide();
        };


        /**
         * @dependent
         * Установить сразу все параметры.
         * (метод, зависящий от структуры json-ов)
         *
         * <b>Параметры</b>
         * - nameRegion - имя региона (рус.яз), по нему происходит везде сравнение
         * - statisticFillActive - статистика для заполнения фона
         * - statisticCityActive - статистика для кружков городов
         * */
        me.setAll = function(nameRegion, statisticFillActive, statisticCityActive){
            var i, item, isSet;
            if (statisticFillActive) {
                var length = statisticFillActive.length;
                isSet = false;
                for (i = 0; i < length; i++) {
                    item = statisticFillActive[i];
                    if (item.id == nameRegion) {
                        me.setBigNum(item.average);
                        me.setThreeNums([item.input, item.output, item.internal]);
                        isSet = true;
                        break;
                    }
                }
                if (!isSet) {
                    me.setBigNum(0);
                    me.setThreeNums(null);
                }
            }
            else {
                me.setBigNum(0);
                me.setThreeNums(null);
            }


            if (statisticCityActive) {
                var length2 = statisticCityActive.length;
                isSet = false;
                for (i = 0; i < length2; i++) {
                    item = statisticCityActive[i];

                    if (item && item.id && item.id.toLowerCase() == nameRegion.toLowerCase()) {
                        me.setColorPanel(item);
                        isSet = true;
                        break;
                    }
                }
                if (!isSet) {
                    me.setColorPanel(null);
                }
            }
            else {
                me.setColorPanel(null);
            }

            me.setLabel(nameRegion);
        };


        /**
         * Показать блок со всеми числами
         */
        me.showNumerals = function(){
            var root = me.getRoot();
            var block = root.find(".statisticOne");
            block.show();
        };


        /**
         * Скрыть блок со всеми числами
         */
        me.hideNumerals = function(){
            var root = me.getRoot();
            var block = root.find(".statisticOne");
            block.hide();
        };


        /**
         * Собрать html-код
         * */
        me.createHTML = function(){
            var html = '<div class="statisticPanel">' +
                    '<div class="background"></div>' +
                    '<div id="info">Макрорегионы</div>' +
                    '<div class="numName">Общий KPI</div>' +
                    '<div class="statisticOne">' +
                        '<div class="flo">' +
                            '<div class="big flo" id="bigDelta">' +
                                '<div class="background">&nbsp;</div>' +
                                '<div class="content">' +
                                    '<div class="num flo">0</div>' +
                                    '<div class="percent flo">%</div>' +
                                    '<div class="delta flo">' +
                                        '<div class="triangle green"></div>' +
                                        //'<div class="numDelta green">0</div>' +
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
                                            //'<div class="numDelta green">0</div>' +
                                        '</div>' +
                                        '<div class="num flo_r">0</div>' +
                                        '<div class="cle_r"></div>' +
                                    '</div>' +
                                    '<div class="point min" id="point_min_1">' +
                                        '<div class="delta flo_r">' +
                                            '<div class="triangle green"></div>' +
                                            //'<div class="numDelta green">0</div>' +
                                        '</div>' +
                                        '<div class="num flo_r">0</div>' +
                                        '<div class="cle_r"></div>' +
                                    '</div>' +
                                    '<div class="point min" id="point_min_2">' +
                                        '<div class="delta flo_r">' +
                                            '<div class="triangle green"></div>' +
                                            //'<div class="numDelta green">0</div>' +
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