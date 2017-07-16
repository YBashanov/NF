/**
 * Объект типа {@link Select}
 * Наследуется от {@link _parent}
 *
 * <b>Методы</b>
 * {@link Select.isEmpty} - проверить на полноту
 * {@link Select.clean} - очистить select
 * {@link Select.load} - подгрузка json и отрисовка option-ов в фоновом режиме, отрисовка select-a "на лету"
 * {@link Select.createHTML} - возвращает html-код элемента без добавления в jquery-объект (для ручной вставки)
 *
 * <b>События</b>. Для расширения логики используй {@link _Parent.events}
 * {@link Select.click} - инициирует событие "клик" по select
 * {@link Select.clickOption} - инициирует событие "клик" по option
 * {@link Select.blur} - инициирует событие "потеря фокуса"
 * @reserved Select.focus - инициирует событие "получение фокуса"
 *
 * <b>Фотография</b>
 * {@link http://be-in-info.ru/files/lux/templates/select.jpg}
 * */
var _select_;
/**
 * Формат json-а для наполнения объекта {@link _select_}
 * [{
 *     "label" : "надпись 1",
 *     "value" : "значение 1"
 *   },
 *   ...
 * ]
 * */
var _selectJson_;
if (! Select) {
    /**
     * <b>Конструктор</b>. Создает js-объект {@link _select_}
     *
     * <b>Связанные параметры родительского</b> конструктора:
     * {@link _Parent}
     *
     * <b>Параметры</b>
     * <b>params</b> объект:
     *    {
     *      - url / json - урл или json, default=''
     *      урл ({@link String}) путь до локального или удаленного json
     *      json ({@link _selectJson_}) объект, который сохранится в {@link _select_}-e без запросов на сервер. Json можно также добавлять динамически с помощью {@link Select.load}
     *      - [nameLabel] - надпись мелкими буквами поверх select-a (заголовок). default=''
     *      - [startLabel] - стартовая надпись для нулевого select, default=''
     *      Если ее нету, то нету и лишнего (нулевого) элемента option
     *      - [startValue] - стартовое значение, default=''
     *      - [changeValue] - значение, если оно выбрано заранее (и мы хотим это показать).
     *      - [changeValueI] - номер значения, если мы хотим его сразу показать (отчет начинается с 0)
     *      - [convertReturnFormat] - метод, который преобразовывает возвращаемые данные в нужный формат для отрисовки select
     *      Параметр: data (возвращенные данные)
     *    }
     *
     * <b>Возвращает</b>
     * {@link _select_}
     *
     * <b>Пример</b>
     * var {@link _select_} = new Select({url : "json/select_macroregions.json"});
     *
     * <b>Дополнительные файлы</b>
     * - css/services/select.css
     * - img/services/select/[all]
     *
     * @return _select_
     */
    var Select = function (params) {
        var me = this;
        _Parent.apply(me, arguments);

        if (params == undefined) params = {};
        if (params.url == undefined) params.url = "";
        if (params.nameLabel == undefined)      params.nameLabel = "";
        if (params.startLabel == undefined)     params.startLabel = "";
        if (params.startValue == undefined)     params.startValue = "";
        if (params.changeValue == undefined)    params.changeValue = "";
        if (params.changeValueI == undefined)   params.changeValueI = -2;
        if (params.convertReturnFormat == undefined)  params.convertReturnFormat = null;


        /*@private*/ var url        =   params.url;
        /*@private*/ var nameLabel  =   params.nameLabel;
        /*@private*/ var startLabel =   params.startLabel;
        /*@private*/ var startValue =   params.startValue;
        /*@private*/ var changeValue=   params.changeValue;
        /*@private*/ var changeValueI=  params.changeValueI;
        /*@private*/ var convertReturnFormat  = params.convertReturnFormat;
        /*@private*/ var optionData  = {};//объект, из которого формируется option


        /**
         * Проверяет {@link _select_} на заполненность
         * -
         * <b>Возвращает</b>
         * - true, если select пустой
         * - false, если select заполнен (если был успешный запрос json-a)
         *
         * @return boolean
         */
        me.isEmpty = function(){
            var root = me.getRoot();
            var children = root.find(".children");
            var options = children.find(".option");

            // > 1, т.к. стартовый option должен быть всегда
            if (options.length && startLabel && options.length > 1) {
                return false;
            }
            else if (options.length && (! startLabel) && options.length > 0) {
                return false;
            }
            else {
                return true; //пустой
            }
        };


        /**
         * Очищает {@link _select_} от option-ов
         * Устанавливает стартовые label и value
         *
         * <b>Возвращает</b>
         * - true - если элементы удалены
         * - false - если элементы не удалены, т.к. их и не было
         *
         * @return boolean
         * */
        me.clean = function(){
            if (! me.isEmpty()) {
                var root = me.getRoot();
                var children = root.find(".children");

                if (startLabel) {
                    var divText = children.prev().find(".text");
                    divText.html(startLabel);
                }

                root.attr("value", startValue);

                var options = children.find(".option").not(':first');
                options.remove();

                return true;
            }
            return false;
        };


        /**
         * Вспомогательный метод объекта {@link _select_}
         * Подгрузить json и отрисовать option-ы без клика по select-у (в фоновом режиме). Наполнение select-a элементами "на лету".
         *
         * <b>Параметры</b>
         * - [myUrl / json] - урл или json
         * Переопределяет параметр url, заданный в конструкторе {@link Select}.
         * Url (String) используется для отправки серверу запроса с параметрами.
         * Json (Object) наполняет {@link _select_} без запросов на сервер.
         *
         * <b>Параметры</b> расширяющего метода через {@link _Parent.events}
         * - json
         *
         * <b>Возвращает</b>
         * {@link _select_}
         *
         * @return select
         */
        me.load = function(myUrl_or_Json){
            if (myUrl_or_Json == undefined) myUrl_or_Json = "";

            _loadJson_pushOptions(myUrl_or_Json, function(json){
                _arrowToggle();

                if (me.customEvents && me.customEvents.load) {
                    me.customEvents.load(json);
                }
            });

            return me;
        };


        /**
         * Инициирует событие клик по {@link _select_}-у для показа/скрытия выпадающего списка.
         * Если json для отрисовки option-ов не подгружен, подгружается.
         * При этом появляется значок прелоадера.
         *
         * <b>Параметры</b>
         * - e - event
         *
         * <b>Параметры</b> расширяющего метода через {@link _Parent.events}
         * - event
         * - объект Select
         * */
        me.click = function (e) {
            //если вызов происходит извне
            if (! e) {
                _focus();
            }

            var root = me.getRoot();
            var children = root.find(".children");

            if (me.customEvents && me.customEvents.click) {
                me.customEvents.click(e, me);
            }


            if (! me.isEmpty()) {
                var isHide = children.is(":hidden");
                if (isHide) {
                    _arrowToggle("up");
                }
                else {
                    _arrowToggle();
                }
                children.toggle();
            }
            else {
                _loadJson_pushOptions("", function(){
                    children.show();
                    _arrowToggle("up");
                });
            }
        };


        /**
         * Инициирует клик по элементу выпавшего списка {@link _select_} (по option)
         *
         * <b>Параметры</b>
         * - e - event
         * - value - значение option-a
         *
         * <b>Параметры</b> расширяющего метода через {@link _Parent.events}
         * - event
         * - объект Select
         * - value - значение option-a
         * */
        me.clickOption = function(e, value){
            var target;

            //если вызов происходит извне
            if (! e) {
                if (value == undefined) {
                    c('Select.clickOption: укажите value');
                    return;
                }
                var _root = me.getRoot();
                target = _root.find(".children .option[value='"+value+"']");
                _blur();
            }
            else {
                target = $(e.target);
            }

            var html = target.html();

            var children = target.parent();
            var root = children.parent();
            var nowValue = root.attr("value");

            if (me.customEvents && me.customEvents.clickOption) {
                me.customEvents.clickOption(e, me, value);
            }

            if (nowValue != value) {
                var divText = children.prev().find(".text");
                divText.html(html);
                root.attr("value", value);

                //перебор option-ов, чтобы присвоить нужный класс active
                children.find(".option").each(function (index, domElement) {
                    $(this).removeClass("active");
                });
                target.addClass("active");
            }
        };


        /**
         * @reserve
         * Инициирует получение select-ом фокуса. Зарезервировано (не используется)
         * */
        me.focus = function(e){};


        /**
         * Инициирует потерю select-ом фокуса. Скрытие выпадающего списка {@link _select_}
         * */
        me.blur = function(e) {
            var root = me.getRoot();
            var children = root.find(".children");
            setTimeout(function(){
                children.hide();

                _arrowToggle();
            }, 300);
        };


        /**
         * Принудительное получение фокуса (если событие click инициируется вне элемента select)
         */
        function _focus(){
            var root = me.getRoot();
            root.find("input.select").focus();
        }


        /**
         * @private
         * Принудительное снятие фокуса (если событие clickOption инициируется вне элемента select)
         */
        function _blur(){
            var root = me.getRoot();
            root.find("input.select").blur();
        }


        /**
         * @private
         * Первая загрузка json-а с отрисовкой option-ов
         *
         * <b>Параметры</b>
         * - [myUrl] - урл, который переопределяет заданный в конструкторе.
         * Используется для отправки изменяемых параметров удаленному серверу.
         * Возможно использование <b>объекта (json) вместо url</b>, который сразу подставится в select
         * - [callback] - функция обратного вызова.
         * Аргументы функции: возвращенный json
         * */
        function _loadJson_pushOptions(myUrl, callback){
            if (myUrl == undefined) myUrl = "";
            if (myUrl) url = myUrl;

            me.clean();
            _wait();

            if (url) {
                if (typeof url == "string") {

                    HTTP.get(url, function (html) {
                        var data = [];
                        try {
                            data = JSON.parse(html);
                        }
                        catch (e) {
                            c("Select: click (_loadJson): json parse error");
                        }
                        if (data) {
                            if (convertReturnFormat) {
                                data = convertReturnFormat(data);
                            }
                            optionData = data;
                            _childrenAppend(data);
                        }
                        else {
                            c("Select.click (_loadJson): нет данных для наполнения выпадающего списка");
                        }

                        if (callback) {
                            callback(data);
                        }
                    });
                }
                else if (typeof url == "object") {
                    optionData = url;
                    _childrenAppend(url);

                    if (callback) {
                        callback(url);
                    }
                }
            }
            else {
                c('Select.click: пустой url');
            }

            /**
             * Наполнить select данными
             * Параметры
             * - data/json
             * */
            function _childrenAppend(data){
                var root = me.getRoot();
                var children = root.find(".children");
                var nowValue = root.attr("value");

                var option = "";
                var length = data.length;
                if (length > 0) {
                    for (var i = 0; i < length; i++) {
                        var item = data[i];
                        option += _setOption(item.label, item.value, nowValue, i);
                    }
                    children.append(option);
                }
            }
        }


        /**
         * Создание html-элемента select
         * Возвращает html-код элемента без добавления в jquery-объект (для ручной вставки)
         *
         * <b>Замечание</b>
         * Если пользоваться этим методом напрямую, следует сначала нарисовать select, т.к. все методы, работающие со значениями, работают с dom-моделями
         * Т.е. выполнить аналог draw()
         * */
        me.createHTML = function(){
            var firstOption = "";
            if (startLabel) {
                firstOption = _setOption(startLabel, startValue, startValue, -1);
            }

            return '<div class="select '+me.getClassName()+'">'
                + '<div class="select_nameLabel">'+nameLabel+'</div>'
                + '<div class="select_body" id="'+me.getId()+'" value="'+startValue+'">'
                    + '<div>'
                        + '<div class="text flo">'+startLabel+'&nbsp;</div>'
                        + '<div class="imgArrow flo"></div>'
                        + '<input class="select" type="text" readonly'
                            + ' onclick="_Parent.prototype._get(\''+me.getId()+'\').click(event)"'
                            + ' onfocus="_Parent.prototype._get(\''+me.getId()+'\').focus(event)"'
                            + ' onblur="_Parent.prototype._get(\''+me.getId()+'\').blur(event)"'
                        + '/>'
                    + '</div>'
                    + '<div class="children">' + firstOption + '</div>'
                    + '<div class="cle"></div>'
                + '</div>'
            + '</div>';
        };


        /**
         * @private
         * Создание html-элемента option
         * */
        function _setOption(label, value, nowValue, _i){
            var active = "";
            var root = me.getRoot();

            if (changeValue == value) {
                nowValue = changeValue;
                root.attr("value", nowValue);

                var divText = root.find(".text");
                divText.html(label);
            }
            else if (changeValueI == _i) {
                nowValue = value;
                root.attr("value", nowValue);
                var divText = root.find(".text");
                divText.html(label);
            }


            if (value == nowValue){
                active = " active";
            }
            return '<div class="option'+active+'" value="'+value+'" onclick="_Parent.prototype._get(\''+me.getId()+'\').clickOption(event, \''+value+'\')">'+label+'</div>';
        }


        /**
         * @private
         * Прелоадер загрузки при запросе удаленного json
         */
        function _wait(){
            var root = me.getRoot();
            var arrow = root.find(".imgArrow");

            arrow.css({
                "background-image" : "url(/bi/img/services/select/wait2.gif)",
                "background-size" : "12px 12px"
            });
        }

        /**
         * @private
         * Смена стрелочки при открытии/закрытии выпадающего списка
         * */
        function _arrowToggle(status){
            var root = me.getRoot();
            var arrow = root.find(".imgArrow");

            if (status == "up") {
                arrow.css({
                    "background-image": "url(/bi/img/services/select/arrowSelect.png)",
                    "background-size": "12px 24px",
                    "background-position": "0 -12px"
                });
            }
            else {
                arrow.css({
                    "background-image" : "url(/bi/img/services/select/arrowSelect.png)",
                    "background-size" : "12px 24px",
                    "background-position" : "0 0"
                });
            }
        }
    };
}