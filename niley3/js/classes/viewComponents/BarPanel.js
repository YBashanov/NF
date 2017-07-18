/**
 * Объект типа {@link BarPanel}
 * Наследуется от {@link _parent}
 *
 * <b>Методы</b>
 * */
var barPanel;
if (! BarPanel) {
    /**
     * <b>Конструктор</b>. Создает js-объект {@link barPanel}
     *
     * <b>Связанные параметры родительского</b> конструктора:
     * {@link _Parent}
     *
     * <b>Параметры</b>
     * <b>params</b> объект:
     *    {
     *
     *    }
     *
     *
     *
     *
     *



 * -------------------
     * -- Панель с цветными прямоугольниками - статистика --
 * Файлы:
 * + css/barPanel.css
 * + img/barPanel/*
 *
 * Создание первого (единственного) объекта
 * var barPanel = new BarPanel({
 *      [colors] : Array - массив цветов.
 *          Цвета можно задать также при создании HTML и/или в json-e с данными
 *          Цвета сохраняются в объекте.
 * });
 *
 * Создание HTML
 * barPanel.draw(
 *      element, - элемент jQuery, в который помещается результат выполнения операций с html-кодом
 *      {
 *          [id] - уникальный идентификатор.
 *              Требуется для того, чтобы перерисовать элемент (draw)
 *          [url] - путь до локального или удаленного json
 *              Json может содержать в себе 2 параметра: colors, data (те, что ниже)
 *              Если url есть, используются данные, возвращенные по запросу на этот url
 *          [colors] : Array - массив цветов
 *              Цвета можно задать также при создании объекта (new BarPanel) и/или в json-e с данными
 *              Цвета сохраняются в объекте.
 *          data - данные для отображения сразу, при создании html
 *          {
 *              number : 0, - большой номер на зеленом фоне
 *              delta  : 0, - маленькая цифра вместе со стрелочкой (зеленой или красной)
 *              label  : 0, - надпись
 *              bar : Array - цветные линии под надписью
 *              [
 *                  {
 *                      value : 0, - значение, длина цветной полоски.
 *                      [color] : "#ffffff" - цвет полоски можно указать в массиве.
 *                          Этот цвет НЕ сохранится в объекте, его не нужно будет затирать.
 *                          Используй, если именно в этом месте один раз хочешь добавить необычный цвет
 *                  },
 *                  {...}
 *              ]
 *          }
 *      }
 * );
 *
 * */
    var BarPanel = function (params) {
        var me = this;
        _Parent.apply(me, arguments);


        if (params == undefined) params = {};
        if (params.colors == undefined) params.colors = ["#000", "#FC324A", "#FD6577", "#FD98A4", "#ffcacd"];

        /*@private*/ var colors = params.colors;

        /**
         * Проверяет {@link barPanel} на заполненность
         * -
         * <b>Возвращает</b>
         * - true, если barPanel пустой
         * - false, если barPanel заполнен (если был успешный запрос json-a)
         *
         * @return boolean
         */
        me.isEmpty = function(){
            //return true;
        };

        /**
         * Очищает {@link barPanel}
         * Устанавливает стартовые значения
         *
         * <b>Возвращает</b>
         * - true - если элементы удалены
         * - false - если элементы не удалены, т.к. их и не было
         *
         * @return boolean
         * */
        me.clean = function(){
            //return true;
        };


        /**
         * Создание html-элемента
         * */
        me.createHTML = function(){

        };




        var _colors = [];

        function _getColor(i) {
            var colors = _colors;
            if (colors[i]) {
                return colors[i];
            }
            else {
                c("BarPanel._getColor: нет цвета в данной позиции. Позиция: " + (i + 1));
                return "#ffff00";
            }
        }

        function _setColors(colors) {
            _colors = colors;
        }

        if (params.colors.length > 0) {
            _setColors(params.colors);
        }

        var id = BarPanel.prototype._getId();

        return {
            draw: function (element, params) {
                if (params == undefined) params = {};
                if (params.id == undefined) params.id = id;
                if (params.url == undefined) params.url = "";
                if (params.colors == undefined) params.colors = [];
                if (params.data == undefined) params.data = {};
                if (params.data.number == undefined) params.data.number = 0;
                if (params.data.delta == undefined) params.data.delta = 0;
                if (params.data.label == undefined) params.data.label = "";
                if (params.data.bar == undefined) params.data.bar = [];

                if (params.url) {
                    HTTP.get(params.url, function (html) {
                        var data = [];
                        try {
                            data = JSON.parse(html);
                        }
                        catch (e) {
                            c("BarPanel: json parse error");
                        }
                        if (data) {
                            element.html(_setHTML(data));
                        }
                        else {
                            c("BarPanel: нет данных");
                        }
                    });
                }
                else {
                    if (params.colors.length > 0) {
                        _setColors(params.colors);
                    }
                    element.html(_setHTML(params));
                }
            }
        };

        function _setHTML(params) {
            var color = "green";
            if (params.data.delta < 0) {
                color = "red";
            }
            var deltaModule = Math.abs(params.data.delta);

            var barHTML = _setBarHTML(params.data.bar);

            return '<div class="barPanel" id="' + params.id + '">' +
                '<div class="big flo">' +
                '<div class="background">&nbsp;</div>' +
                '<div class="content">' +
                '<div class="delta flo_r">' +
                '<div class="triangle ' + color + '"></div>' +
                '<div class="numDelta ' + color + '">' + deltaModule + '</div>' +
                '</div>' +
                '<div class="num flo_r">' + params.data.number + '</div>' +
                '<div class="cle_r"></div>' +
                '</div>' +
                '</div>' +
                '<div class="flo label mar_l15">' + params.data.label + '</div>' +
                '<div class="cle"></div>' +
                '<div class="gradients">' + barHTML + '</div>' +
                '</div>';
        }

        function _setBarHTML(bar) {
            var barHTML = "";

            var barValue_total = 0;
            var px_total = 250; //длина div-а в пикселях (в стилях)
            var ratio = 0;
            var px_width = 0;
            var color = "";

            var barLength = bar.length;
            if (barLength > 0) {
                //сначала - подсчет
                for (var i = 0; i < barLength; i++) {
                    barValue_total += parseInt(bar[i].value);
                }

                ratio = barValue_total / px_total;

                //затем - отображение
                for (i = 0; i < barLength; i++) {
                    if (bar[i].color) {
                        color = bar[i].color;
                    }
                    else {
                        color = _getColor(i);
                    }
                    px_width = Math.floor(bar[i].value / ratio);
                    barHTML += '<div class="gradient" style="background-color:' + color + ';width:' + px_width + 'px;"></div>';
                }
            }
            else {
                c("BarPanel: нет элементов для построения цветной линии. Пустой {data.bar : []}");
            }

            return barHTML;
        }
    };
}



