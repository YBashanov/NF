/**
 * Объект типа {@link Calendar}
 * Наследуется от {@link _parent}
 * Требуется {@link Select}
 *
 * <b>Методы</b>
 * {@link Calendar.load} - отрисовка вспомогательных компонентов
 * {@link Calendar.reload} - перерисовка тела календаря
 *
 * <b>События</b>. Для расширения логики используй {@link _Parent.events}
 * {@link Calendar.click} - клик по инпуту для показа календаря
 * {@link Calendar.changeYear} - выбор года
 * {@link Calendar.changeMonth} - выбор месяца
 * {@link Calendar.changeDay} - выбор дня в сетке календаря
 *
 * <b>Фотография</b>
 * {@link http://be-in-info.ru/files/lux/templates/calendar.jpg}
 * */
var _calendar_;
/**
 * Объект типа {@link Object}
 * Это может быть любой объект (в том числе, наследованый от {@link _parent}), в котором реализовано два метода:
 *
 * <b>Методы</b>
 * load - наполнить объект данными, обновить данные в объекте
 * getHTML - создать html
 * */
var _auxilElement_;
if (! Calendar) {
    /**
     * <b>Конструктор</b>. Создает js-объект {@link _calendar_}
     *
     * <b>Связанные параметры родительского</b> конструктора:
     * {@link _Parent}
     *
     * <b>Параметры</b>
     * <b>params</b> объект:
     *    {
     *      - [label] - надпись над input-ом календаря
     *      - [month] default = now
     *      - [year] default = now
     *      - [day] default = now
     *      - [format] default = Y-m-d
     *    }
     *
     * <b>Возвращает</b>
     * {@link _calendar_}
     *
     * <b>Пример</b>
     *
     * <b>Дополнительные файлы</b>
     * - css/services/calendar.css
     * - img/services/calendar/**
     *
     * @return _calendar_
     */
    var Calendar = function (params) {
        var me = this;
        _Parent.apply(me, arguments);

        if (params == undefined) params = {};
        if (params.label == undefined) params.label = "";
        if (params.month == undefined) params.month = null;
        if (params.year == undefined) params.year = null;
        if (params.day == undefined) params.day = null;
        if (params.format == undefined) params.format = "Y-m-d";

        /*@private*/ var label  =   params.label;
        /*@private*/ var activeCalendarHtml = ""; //сформированный календарь с управляющими элементами
        /*@private*/ var monthJson = [
            {"label" : "Январь",    "value" : "0"},
            {"label" : "Февраль",   "value" : "1"},
            {"label" : "Март",      "value" : "2"},
            {"label" : "Апрель",    "value" : "3"},
            {"label" : "Май",       "value" : "4"},
            {"label" : "Июнь",      "value" : "5"},
            {"label" : "Июль",      "value" : "6"},
            {"label" : "Август",    "value" : "7"},
            {"label" : "Сентябрь",  "value" : "8"},
            {"label" : "Октябрь",   "value" : "9"},
            {"label" : "Ноябрь",    "value" : "10"},
            {"label" : "Декабрь",   "value" : "11"}
        ];
        /*@private
        * Набор вспомогательных компонентов.
        * Все типы - {@link _auxilElement_}
        * Управляющие кнопки на календаре (месяц, год), вывод даты (input)
        * */
        var auxil = {
            input : null,
            monthPanel : null,
            yearPanel : null
        };
        var getdate_Now = new Date();

        /*@private, месяц начинается с 0*/
        var month = params.month;
        /*@private*/
        var year = params.year;
        /*@private*/
        var day = params.day;
        if (! month) {
            month = getdate_Now.getMonth();
        }
        if (! year) {
            year = getdate_Now.getFullYear();
        }
        if (! day) {
            day = getdate_Now.getDate();
        }


        /**
         * Инициирует событие клик по {@link _calendar_}- для показа/скрытия активного календаря
         *
         * <b>Параметры</b>
         * - event
         *
         * <b>Параметры</b> расширяющего метода через {@link _Parent.events}
         * - event
         * - {@link _calendar_}
         * */
        me.click = function (e) {
            var root = me.getRoot();
            var _hide = root.find("._hide");

            var isHide = _hide.is(":hidden");
            if (isHide) {
                var html = createCalendar();
                if (html) {
                    _hide.html(html);
                }
            }
            _hide.toggle();

            if (me.customEvents && me.customEvents.click) {
                me.customEvents.click(e, me);
            }
        };


        /**
         * Закрыть календарь
         * */
        me.close = function(){
            var root = me.getRoot();
            var _hide = root.find("._hide");
            _hide.hide();
        };


        /**
         * Клик по стрелке выбора года. Смена года календаря.
         * Перерисовывает календарь
         *
         * <b>Параметры</b>
         * - event
         * - deltaYear - -1 или +1 к году
         *
         * <b>Параметры</b> расширяющего метода через {@link _Parent.events}
         * - event
         * - deltaYear
         * - {@link _calendar_}
         * - {@link _auxilElement_} year
         * */
        me.changeYear = function(e, deltaYear){
            year = year + parseInt(deltaYear);

            me.reload();

            if (auxil.years) {
                auxil.years.load();
            }

            if (me.customEvents && me.customEvents.changeYear) {
                me.customEvents.changeYear(e, deltaYear, me, auxil.years);
            }
        };


        /**
         * Выбор месяца.
         * Перерисовывает календарь
         *
         * <b>Параметры</b>
         * - event
         * - value (month)
         *
         * <b>Параметры</b> расширяющего метода через {@link _Parent.events}
         * - event
         * - month
         * - {@link _calendar_}
         * - {@link _auxilElement_} month
         * */
        me.changeMonth = function(e, value){
            month = parseInt(value);

            me.reload();

            if (me.customEvents && me.customEvents.changeMonth) {
                me.customEvents.changeMonth(e, month, me, auxil.months);
            }
        };


        /**
         * Клик по дню календаря
         *
         * <b>Параметры</b>
         * - event
         *
         * <b>Параметры</b> расширяющего метода через {@link _Parent.events}
         * - event
         * - day
         * - {@link _calendar_}
         * - {@link _auxilElement_} day
         * */
        me.changeDay = function(e, _day){
            var root = me.getRoot();
            var input = root.find(".input");

            day = _day;

            if (auxil.input) {
                auxil.input.load();
            }
            me.click();

            if (me.customEvents && me.customEvents.changeDay) {
                me.customEvents.changeDay(e, _day);
            }
        };


        /**
         * Отрисовать вспомогательные компоненты без клика по ним (в фоновом режиме).
         * */
        me.load = function(){
            var root = me.getRoot();
            var _hide = root.find("._hide");

            var html = createCalendar();
            if (html) {
                _hide.html(html);

                if (auxil.input) {
                    auxil.input.load();
                }
                if (auxil.months) {
                    auxil.months.load();
                }
                if (auxil.years) {
                    auxil.years.load();
                }

                clickOutsideListener();
            }

            return me;
        };


        /**
         * Перерисовать тело календаря с новыми установками месяца и года
         * */
        me.reload = function(){
            var root = me.getRoot();
            var _grid = root.find('._grid');
            _grid.html(getCalendarGrid());
        };


        /**
         * Устанавливает слушатель на клик вовне компонента, чтобы закрыть календарь.
         * */
        function clickOutsideListener(){
            var id = me.getId();
            $(document).on('click', function(e){
                var container = $("#"+id);

                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    me.close();
                }
            })
        }


        /**
         * Создание html-элемента
         * -
         * <b>Возвращает</b>
         * {@link html}-представление
         * */
        me.createHTML = function(){
            var firstOption = "";

            auxil.input = createAuxilInput();

            return '<div class="calendar '+me.getClassName()+'">' +
                '<div class="label">'+label+'</div>' +
                '<div class="workplace">' +
                    auxil.input.getHTML() +
                    '<div class="_hide"></div>' +
                '</div>' +
            '</div>';
        };


        /**
         * @private
         * Создает активный календарь с управляющими элементами (либо возвращает уже созданный календарь)
         * Выполняется один раз.
         * -
         * <b>Возвращает</b>
         * {@link html}, если еще календарь не создавался.
         * null, если уже создавался
         * */
        function createCalendar(){
            if (! activeCalendarHtml) {
                var html = "";

                var _grid = getCalendarGrid();

                auxil.months = createAuxilMonths();
                auxil.years = createAuxilYears();

                html += '<div class="_calendar">' +
                    '<div class="block_1 flo">' +
                        '<div class="auxilMonths">' +
                            auxil.months.getHTML() +
                            '<div class="cle"></div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="block_2 flo">' +
                        '<div class="auxilYears">' +
                            auxil.years.getHTML() +
                        '</div>' +
                    '</div>' +
                    '<div class="cle"></div>' +
                    '<div class="block_3 _grid">'+_grid+'</div>' +
                '</div>';

                activeCalendarHtml = html;
                return html;
            }
            else {
                return null;
            }
        }


        /**
         * Рисует сетку (тело) календаря
         *
         * <b>Параметры</b>
         * params = {}
         * - [month]
         * - [year]
         *
         * <b>Возвращает</b>
         * html
         * */
        function getCalendarGrid(params){
            if (params == undefined) params = {};
            if (params.month == undefined) params.month = null;
            if (params.year == undefined) params.year = null;

            if (params.month) {
                month = params.month;
            }
            if (params.year) {
                year = params.year;
            }

            //date для 1го числа текущего месяца
            var getdate_1 = new Date(year, month, 1);
            //time для 1го числа текущего месяца
            var time_1 = getdate_1.getTime();

            var day = 86400*1000;


            //вычисляем количество дней в выбранном месяце
            //date для 1го числа следующего месяца
            var getdate_NextMonth = new Date(year, month + 1, 1);
            //time для 1го числа следующего месяца
            var time_NextMonth = getdate_NextMonth.getTime();

            var daysInMonth = (time_NextMonth - time_1)/day;
            //округляем до целого (март и октябрь выводятся не целыми числами)
            daysInMonth = Math.round(daysInMonth);

            //предварительное количество ячеек
            var total_cells = daysInMonth;
            //отступ в количествах ячеек перед первым числом месяца
            var indent = 0;

            //вычисляем размеры месячной сетки
            //если 1 день недели - не понедельник, то перед ним должны быть пустые ячейки
            //если последний день недели - не воскресенье, то после него должны быть пустые ячейки
            var wday = getdate_1.getDay();
            if ( wday != 1 ) {
                //также рассчитаем, насколько необходимо отступить ячеек перед первым числом месяца
                if ( wday == 0 ) {
                    indent = 6;
                }
                else {
                    indent = wday - 1;
                }
                total_cells += indent;
            }

            var wday_NextMonth = getdate_NextMonth.getDay();
            if ( wday_NextMonth != 1 ) {
                if ( wday_NextMonth == 0 ) {
                    total_cells += 1;
                }
                else {
                    total_cells += 7 - wday_NextMonth + 1;
                }
            }

            //всего недель (строк в сетке)
            var total_tr = total_cells/7;
            var printDay = 1; //начинаем с первого дня


            var html = "";
            html += '<table class="c_table" border="0" cellpadding="0" cellspacing="0" align="center">' +
                '<tr class="c_head">' +
                    '<td class="cell _head">Пн</td>' +
                    '<td class="cell _head">Вт</td>' +
                    '<td class="cell _head">Ср</td>' +
                    '<td class="cell _head">Чт</td>' +
                    '<td class="cell _head">Пт</td>' +
                    '<td class="cell _head">Сб</td>' +
                    '<td class="cell _head">Вс</td>' +
                '</tr>';

            //цикл по неделям
            for (var i=0; i<total_tr; i++ ) {
                html += '<tr class="c_tr">';

                for (var j = 0; j < 7; j++) {
                    //цвет выходного дня (суббота и воскресенье)
                    var tdClass = "";
                    if ((j == 5) || (j == 6)) {
                        tdClass = " weekend";
                    }
                    //цвет остальных дней и пустых ячеек
                    else {
                        tdClass = " others";
                    }


                    //indent, если он есть - действует по накопительной схеме
                    if ( indent > 0 ) {
                        html += '<td class="cell empty'+tdClass+'">&nbsp;</td>';
                        indent--;
                    }
                    else {
                        if ( printDay <= daysInMonth ) {
                            html += '<td class="cell'+tdClass+'" onclick="_Parent.prototype._get(\''+me.getId()+'\').changeDay(event, '+printDay+')">'+printDay+'</td>';
                            printDay++;
                        }
                        else {
                            html += '<td class="cell empty'+tdClass+'">&nbsp;</td>';
                        }
                    }
                }
                html += '</tr>';
            }
            html += '</table>';

            return html;
        }


        /**
         * Создать вспомогательные элементы (управляющие компоненты) для календаря - месяцы
         *
         * <b>Возвращает</b>
         * {@link _select_}
         * */
        function createAuxilMonths(){
            var select = new Select({
                url : monthJson,
                className : "inCalendar",
                changeValueI : month
            });
            select.events({
                clickOption : function(e, mySelect, value){
                    me.changeMonth(e, value);
                }
            });
            return select;
        }


        /**
         * Создать вспомогательные элементы (управляющие компоненты) для календаря - выбор года
         *
         * <b>Возвращает</b>
         * {@link _auxilElement_}
         * */
        function createAuxilYears(){
            var html = '<div class="flo arrows_block" onclick="_Parent.prototype._get(\''+me.getId()+'\').changeYear(event, \'-1\')">' +
                    '<div class="arrows arrow_left"></div>' +
                '</div>' +
                '<div class="flo">' +
                    '<div class="year"></div>' +
                '</div>' +
                '<div class="flo arrows_block" onclick="_Parent.prototype._get(\''+me.getId()+'\').changeYear(event, \'1\')">' +
                    '<div class="arrows arrow_right"></div>' +
                '</div>';

            return {
                load : function(){
                    var root = me.getRoot();
                    var _year = root.find("._calendar .year");
                    _year.html(year);
                },
                getHTML : function(){
                    return html;
                }
            };
        }


        /**
         * Создать input для клика по нему, и для вывода в него конечных данных из календаря
         *
         * <b>Возвращает</b>
         * {@link _auxilElement_}
         * */
        function createAuxilInput(){
            var html = '<div class="input" onclick="_Parent.prototype._get(\''+me.getId()+'\').click(event)"></div>';

            return {
                load : function() {
                    //формат даты запихнуть сюда
                    var date = new Date(year, month, day);
                    var _value = date.date("Y-m-d");

                    me.setValue(_value);

                    var root = me.getRoot();
                    var input = root.find(".input");
                    input.html(_value);
                },
                getHTML : function(){
                    return html;
                }
            };
        }
    };
}