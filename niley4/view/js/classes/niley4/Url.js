/**
 * Работает с url-строкой. Меняет url строку без перезагрузки страницы
 * Расширяется {@link ScopeValues}
 *
 * <b>Методы</b>
 * {@link Url}
 * {@link Url.runElements} - Изменить элементы интерфейса в зависимости от параметров url
 * {@link Url.runUrl} - изменить url
 * {@link Url.addElement} - Добавить элемент для участия в операциях с url
 * {@link Url.callbackEvent} - Метод, который помещается в другой модуль, и вызывается из него в его внешних методах-событиях.
 * {@link Url.getCallbackAllowed} - получить разрешение на работу коллбэков
 * {@link Url.setElementToCopyUrl} - Установить элемент для копирования в него результатов работы с url
 */
var _url_;

if (!Url) {
    /**
     * <b>Конструктор</b> объекта {@link _url_}
     *
     * <b>Параметры</b>
     * = {@link ScopeValues.setParams}
     */
    var Url = function (params) {
        var me = this;
        ScopeValues.apply(me, arguments);
        me.setParams(params);


        /* Зарегистрированные элементы */
        var elements = {};

        /* количество элементов сейчас */
        var count = 0;

        /**
        * разрешено ли принимать коллбэки от зарегистрированных элементов
        * Запирается, когда коллбэки могут прийти от первой загрузки, когда url настраивает элементы
        * */
        var isCallbackAllowed = true;

        /* объект, соответствующий параметрам в url */
        var urlObject = {};

        /* элемент для копирования в него копии url */
        var elementToCopyUrl = null;

        /**
         * Установить элемент для копирования в него результатов работы с url
         *
         * <b>Параметры</b>
         * selector - обычный селектор
         */
        me.setElementToCopyUrl = function(selector){
            elementToCopyUrl = $(selector);
        };


        /**
         * получить разрешение на работу коллбэков
         */
        me.getCallbackAllowed = function(){
            return isCallbackAllowed;
        };


        /**
         * Записать аргументом выбранного элемента строку url
         *
         * <b>Параметры</b>
         * url - строка url
         */
        function writeUrlToElement(url){
            elementToCopyUrl.attr("url", url);
        }


        /** !!!!!!!!!!!
         * Изменить элементы интерфейса в зависимости от параметров url
         *
         * требуется вход в данный объект через прямое изменение командной строки
         * Результат - изменение интерфейса (кнопок)
         * Должен вызываться в самом конце, после регистрации всех ViewElement-ов
         */
        me.runElements = function() {
            //элементы сами вызывают этот метод
            // и при каждой инициализации проверяется, все ли они готовы к обработке
            /*
            внутри select вешается промис, который при положительном ответе вызывается здесь, т.е. его нам тут нужно получить
            и хранить где-то

            при полной готовке всего блока selectов, они также в цикле будут все вызываться и fire на их метод

            НАДО ПОДСТАВЛЯТЬ ТОЧКУ И ЗУМ!!!!!!
             */
            var hash = getHash();
            if (elementToCopyUrl) {
                writeUrlToElement(hash);
            }

            var urlObject = strToObj(hash);

            // проходим по всем элеметам, что есть в адресной строке
            // и вешаем им слушатель состояния
            for (var val in urlObject) {
                var element = elements[val].element;
                control(element);
            }

            // слушаем у каждого элемента изменение состояния
            function control(element) {
                var promise = element.getPromise();

                if (promise) {
                    promise.then(
                        function (data) {
                            // тут нужно пройти по всем элементам и опросить их (на наличие состояния)
                            checkElementsStatus();
                        }
                    );
                }
            }

            // опрос состояния у элементов
            function checkElementsStatus(){
                var trueCount = 0;

                for (var val in urlObject) {
                    var element = elements[val].element;
                    if (element.getIsLoad() == true) {
                        trueCount++;
                    }
                }

                if (count == trueCount) {
                    // c('go');
                    fire();
                }
            }

            // запуск методов элементов
            function fire(){
                isCallbackAllowed = false;
                var trueCount = 0;

                me.map.setQueryLock(true);

                for (var val in urlObject) {
                    trueCount++;

                    //проходит только 1 запрос - последний
                    if (count == trueCount) {
                        me.map.setQueryLock(false);
                    }

                    elements[val].activeMethod(null, urlObject[val], function () {
                        elements[val].element.click.processCheck = false;
                        // trueCount++;
                    });
                }

                isCallbackAllowed = true;
            }
        };


        /**
         * изменить url
         * + записать копию url аргументом в элемент
         *
         * <b>Параметры</b>
         * [String]
         */
        me.runUrl = function(string) {
            if (string == undefined) {
                string = objToStr();
            }
// c('runUrl')
// c(string)
            if (elementToCopyUrl) {
                writeUrlToElement(string);
            }

            location.hash = "#" + string;
        };


        /**
         * Добавить элемент в объект для того, чтобы этот элемент участвовал в операциях с url
         *
         * <b>Параметры</b>
         * params : {
         *  - {@link _viewElement_} element - элемент (регистрация элемента для прослушивания)
         *  - {@link String} urlBuildMethod - метод, который вызывается у объектов для формирования url
         *  - {@link String} activeMethod - метод, который вызывается у объектов при активности url
         *      либо изменение url меняет его.
         *      Аргументы метода: (e, <b>value</b>, ...)
         *  - [{@link String} urlKey] - ключ, по которому параметр будет выводиться в url, и по которому будет
         *      считываться из url при работе с элементами. Если не указан - в строке url будут id элементов
         * }
         */
        me.addElement = function(params){
            if (params == undefined) params = {};

            if (params.element == undefined) params.element = null;
            if (params.urlBuildMethod == undefined) params.urlBuildMethod = "getValue";
            if (params.activeMethod == undefined) params.activeMethod = null;
            if (params.urlKey == undefined) params.urlKey = null;

            var element         = params.element;
            var urlBuildMethod  = params.urlBuildMethod;
            var activeMethod    = params.activeMethod;
            var urlKey          = params.urlKey;

            if (element && activeMethod) {

                var key;
                if (urlKey) {
                    element.urlKey = urlKey;
                    key = urlKey;
                }
                else {
                    key = element.getId();
                }

                elements[key] = {
                    element : element,
                    urlBuildMethod : element[urlBuildMethod],
                    activeMethod : element[activeMethod]
                };
            }
// c(elements);
        };


        /**
         * Метод, который помещается в другой модуль, и вызывается из него в его внешних методах-событиях.
         * Вход в данный объект через изменение интерфейса (кнопок)
         * Результат - изменение командной строки
         *
         * <b>Параметры</b>
         * params : {
         *  - {@link String} eventName - имя метода (события)
         *  - {@link String} elementKey - имя объекта (идентификатор)
         *  - {@link Event} e - event
         *  - {@link String} value - значение объекта в данный момент (например, значение Select)
         *  - arguments - все аргументы в куче
         * }
         */
        me.callbackEvent = function(params){
            if (isCallbackAllowed) {
                //если тронули элемент, которого нет среди зарегистрированых - никаких изменений нет
                var element = getElement(params.me.urlKey);
                if (!element) {
                    element = getElement(params.elementKey);
                }

                //element может быть undefined, если этот элемент не зарегистрирован в системе с помощью url.addElement
                if (element) {
                    buildUrlObject();
                }

                me.runUrl();
            }
        };


        /**
         * @private
         * Собрать urlObject из значений зарегистрированных объектов
         */
        function buildUrlObject(){
            urlObject = {};
            var key, value;
// c(elements);
            for (var val in elements) {
                key = val;
                value = elements[val].urlBuildMethod();
                urlObject[key] = value;
            }
// c(urlObject);
        }


        /**
         * @private
         * Получить элемент по его уникальному ключу
         *
         * <b>Возвращает</b>
         * {@link _viewElement_}
         */
        function getElement (key) {
            return elements[key];
        };


        /**
         * Получить строку-хэш
         */
        function getHash(){
            var hash = location.hash;

            return hash.substr(1);
        };


        /**
         * @private
         * Создает из объекта строку для вставки в url
         */
        function objToStr() {
            var str = "";
            for (var val in urlObject) {
                if (urlObject[val]) {
                    str += val + "=" + urlObject[val] + "&";
                }
            }
            if (str) {
                str = str.substr(0, str.length-1);
            }
            return str;
        }


        /**
         * @private
         * Создает из строки url объект для работы с элементами ViewElements.
         * Проверяет наличие элемента среди зарегистрированных.
         */
        function strToObj(str){
            var pairs = str.split("&");
            var obj = {}, pairs_2;
            var length = pairs.length;
            var objCount = 0;

            for (var i=0; i<length; i++) {
                pairs_2 = pairs[i].split("=");

                //если существует такой зарегистрированный элемент
                if (elements[pairs_2[0]]) {
                    obj[pairs_2[0]] = pairs_2[1];
                    objCount++;
                }
            }

            count = objCount;
            return obj;
        }
    }
}
















