/*
* 2016-12-12
* */
myApp.service('Validate', function ($rootScope) {
    var _scope = null;
    var _array = [];
    var _submit = [];
    var _isGlobal = true;
    var _scopeIsObject = true;

    var _patterns = {
        int     : /[0-9]+/,
        integer : /[0-9]+/,
        float   : /[0-9.,-]+/,
        double  : /[0-9.,-]+/,
        mail    : /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{1,6})+$/
    };

    function _getPattern(rule){
        return _patterns[rule];
    }

    var _serviceMess = {
        "ru_RU" : {
            defaultVal :
                "Пустое поле",
            defaultRule :
                "Неверный формат",

            wrongRuleCheck :
                "Проверяемый текст не соответствует правилу (паттерну) rule",
            wrongFuncCheck :
                "Проверяемый текст не соответствует методу rule",
            notRule :
                "Правило проверки rule не определено",
            valEmpty :
                "Проверяемый текст - пустая строка",
            keyEmpty :
                "Не указан key",
            arrayEmpty :
                "Массив элементов пустой",
            arrayWrong :
                "Не задан массив элементов",
            notScope :
                "Не определен массив значений",
            incorrectFilter :
                "Неверно установлены фильтры ИЛИ требуется доработка класса для данных условий",
            dontRootScope :
                "Не создан объект $rootScope.components = {}"
        }
    };

    function _getServiceMess(code, language){
        if (language == undefined) language = "ru_RU";
        return _serviceMess[language][code];
    }

    // вывод сообщения о пустом поле
    function _getMess(mess, type){
        var insideMessage;
        if (type == "empty"){
            insideMessage = _getServiceMess("defaultVal");
        }
        else if (type == "rule"){
            insideMessage = _getServiceMess("defaultRule");
        }

        if (mess) {
            insideMessage = mess;
        }
        return insideMessage;
    }

    function _goRule(val, rule){
        var r_val = val.match(rule);

        if (r_val) {
            if (r_val[0] != val) {
                return false;
            }
            else {
                return r_val[0];
            }
        }
        else return false;
    }

    //отображаем ошибки
    function markedErrors(element){
        if (! element.errorClass) element.errorClass = "component_error";

        if (_isGlobal == true) {
            if ($rootScope.components[element.key].validate) {
                $rootScope.components[element.key].validate.errorClass = element.errorClass;
                $rootScope.components[element.key].validate.error = element.error;
            }
        }
        else {
            if (_scope[element.key].validate) {
                _scope[element.key].validate.errorClass = element.errorClass;
                _scope[element.key].validate.error = element.error;
            }
        }
    }

    //установка флагов. Вынес отдельно, чтобы не захламлять
    function _setFlags(flags){
        if (flags == undefined) {
            flags = {
                isGlobal : true,
                scopeIsObject : true
            }
        }

        if (flags.isGlobal === false){
            _isGlobal = false;
        }
        else {
            _isGlobal = true;
        }

        if (flags.scopeIsObject === false){
            _scopeIsObject = false;
        }
        else {
            _scopeIsObject = true;
        }
    }


    return {
        //если массив уже установлен - чистит переменные ошибок
        clear : function(scope){
            if (scope == undefined){}
            else {
                _scope = scope;
            }

            if (_scope == undefined){
                c(_getServiceMess("notScope"));
            }

            var len = _array.length;
            if (len > 0){
                for (var i=0; i<len; i++){
                    if (_array[i].key !== undefined) {
                        var key = _array[i].key;

                        if (_isGlobal == true) {
                            $rootScope.components[key].validate = {};
                        }
                        else {
                            _scope[key].validate = {};
                        }
                    }
                }
            }
        },


        /**
         * scope - массив со значениями
         * array[{
         *      key : "username"    - строка. Ключ, определяющий элемент в массиве $scope
         *        OR
         *      key : i             - в случае, если _scope - массив (тогда необходимо указать flags {scopeIsObject : false)
         *      ![afterSubmit : true]- если указываем, данный ключ не будет проверяться при submit формы, он будет использован ПОСЛЕ submit формы
         *      [rule : "mail" OR rule : "/[0-9]+/"] - имя внутреннего метода (готовый паттерн) ИЛИ проверочный паттерн. Default - "", false, null
         *      [messageEmpty : "Пустое поле"]     - надпись при проверке на заполненность. Default - "Пустое поле"
         *      [messageRule : "Неверный формат"]   - надпись при проверке по правилу rule. Default - "Неверный формат"
         *      [errorClass : "component_error"]    - стили, которые применяются при ошибке. Default = "component_error"
         * }]
         * flags = {}
         *      isGlobal = true(default) | false, Флаг, показывает делать ли запись ошибок в глобальный rootScope. Если используем внутри вложенных компонентов,
         *          у которых свои обработчики ошибок, флаг можно выставить в false, иначе будет засоряться rootScope
         *      scopeIsObject = true(default) | false, Флаг, показывающий, каким образом получать текущие значения элементов формы из _scope
         *          Если _scope - объект (у него именованные переменные, в которых сразу находятся значения) - ставим true
         *          Если _scope - массив, то значения получаем
         *
         *   Пример: проверить на пустое
         *   Validate.set($scope, [{
         *       key : "username"
         *   }]);
         *
         *   Пример: записать строку, активируемую после submit формы
         *   Validate.set($scope, [{
         *       key : "enter",
         *       afterSubmit : true,
         *       messageRule : "Ошибка при входе. Проверьте правильность логина и пароля"
         *   }]);
         *
         *   Применяем к форме:
         *   1. Вывод текста ошибки
         *   <span ng-class="components._ключ_.validate.errorClass">{{components._ключ_.validate.error}}</span>
         *   2. Подкраска поля ввода
         *   <input ng-class="components._ключ_.validate.errorClass" type="text" ng-model="_ключ_">
         */
        set : function(scope, array, flags){
            if (scope == undefined){
                console.error(_getServiceMess("notScope"));
                return;
            }
            else {
                _scope = scope;
            }

            _setFlags(flags);

            var result = {
                isValid : true,
                result : [],
                variables : {}
            };
            var isValidTotal = false;

            if (array instanceof Array){
                var len = array.length;

                if (len > 0){
                    var key, rule, _val, messEmpty, messRule, insideMessage, isValid, afterSubmit;
                    isValidTotal = true;
                    _array = array;
//c(_scope);
                    for (var i=0; i<len; i++){
                        isValid  = true;
                        key = rule = _val = messEmpty = messRule = insideMessage = afterSubmit = null;

                        if (_array[i].key !== undefined) {
                            key = _array[i].key;
                            rule = _array[i].rule;
                            if (_scopeIsObject) {
                                _val = _scope[key];
                            }
                            else {
                                _val = _scope[key].value;
                            }
                            messEmpty   = _array[i].messageEmpty;
                            messRule    = _array[i].messageRule;
                            afterSubmit = _array[i].afterSubmit;

                            //предварительная чистка
                            if (_isGlobal == true) {
                                if (!$rootScope.components){
                                    insideMessage = _getServiceMess("dontRootScope");
                                    console.error(insideMessage); //ошибка, требующая вмешательства разработчика
                                }

                                if (!$rootScope.components[key]) {
                                    $rootScope.components[key] = {};
                                }
                                $rootScope.components[key].validate = {};
                            }
                            else {
                                if (! _scope[key]) {
                                    insideMessage = _getServiceMess("incorrectFilter");
                                    console.error(insideMessage); //ошибка, требующая вмешательства разработчика
                                }
                                else {
                                    _scope[key].validate = {};
                                }
                            }

                            //проверку валидации проходит только afterSubmit == false
                            if (! afterSubmit) {
                                // если значение не пустое
                                if (_val) {
                                    if (rule) {
                                        var r_val;
                                        var isRuleTrue = true;
                                        var typeErr;

                                        // использование готового паттерна данного класса
                                        if (typeof rule == "string") {
                                            r_val = _goRule(_val, _getPattern(rule));
                                            typeErr = "wrongFuncCheck";
                                        }
                                        // ручной ввод паттерна
                                        else if (typeof rule == "object") {
                                            r_val = _goRule(_val, rule);
                                            typeErr = "wrongRuleCheck";
                                        }
                                        else {
                                            isRuleTrue = false;
                                            isValid = false;
                                            isValidTotal = false;
                                            insideMessage = _getServiceMess("notRule");
                                            console.error(insideMessage); //ошибка, требующая вмешательства разработчика
                                        }

                                        if (isRuleTrue) {
                                            if (r_val != _val) {
                                                _array[i].error = _getMess(messRule, "rule");
                                                isValid = false;
                                                isValidTotal = false;
                                                insideMessage = _getServiceMess(typeErr);

                                                markedErrors(_array[i]);
                                            }
                                        }
                                    }
                                    else {
                                        //rule может быть пустой строкой - проверки нет
                                    }
                                }
                                // если значение пустое - выводим сообщение
                                else {
                                    _array[i].error = _getMess(messEmpty, "empty");
                                    isValid = false;
                                    isValidTotal = false;
                                    insideMessage = _getServiceMess("valEmpty");

                                    markedErrors(_array[i]);
                                }
                            }
                            else {
                                _submit.push({
                                    key : key,
                                    error : messRule
                                });
                            }
                        }
                        else {
                            isValid = false;
                            isValidTotal = false;
                            insideMessage = _getServiceMess("keyEmpty");
                            console.error(insideMessage); //ошибка, требующая вмешательства разработчика
                        }

                        if (! afterSubmit) {
                            result.result.push({
                                key: key,
                                isValid: isValid,
                                rule: rule,
                                insideMessage: insideMessage,
                                afterSubmit: afterSubmit
                            });
                            result.variables[key] = _val;
                        }
                    }
                }
                else {
                    console.error(_getServiceMess("arrayEmpty")); //ошибка, требующая вмешательства разработчика
                }
            }
            else {
                console.error(_getServiceMess("arrayWrong")); //ошибка, требующая вмешательства разработчика
            }

            result.isValid = isValidTotal;
            return result;
        },


        // показать одно из сообщений после отправки формы
        showSubmitError : function(key){
            if (_scope == undefined){
                console.error(_getServiceMess("notScope"));
                return;
            }

            var len = _submit.length;
            if (len > 0){
                for (var i=0; i<len; i++){
                    if (key) {
                        if (_submit[i].key == key) {
                            markedErrors(_submit[i]);
                        }
                    }
                    // показать все
                    else {
                        markedErrors(_submit[i]);
                    }
                }
            }
        }
    };
});


