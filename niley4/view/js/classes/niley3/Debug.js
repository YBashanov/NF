/**
 2015-12-30 - добавлен глобальный метод e(String) - выводит в консоль ошибку
 2016-01-12 - метод e продублирован методом err (т.к. мы иногда используем внутри событий переменную e)
 2016-07-14 - добавлен поиск в функции aa
 2016-11-29 - открытие логирования только через ключ debug=true (можно просто debug)
 2017-03-15
 */
var Debug={ forin:function(b){if("object"==typeof b){str="";for(var c in b)str+=c+"---"+b[c]+"\n";alert(str)}else alert(b)},get: function(key){var get = {};var url = location.href;var s_url = url.split("?");if (s_url[1]) {var _get = s_url[1].split("&");for (var i = 0; i < _get.length; i++) {var s_get = _get[i].split("=");get[s_get[0]] = decodeURI(s_get[1]);}}if (key){return get[key];}else {return get;}}}, d=function(b){ if("object"==typeof b){str="";for(var c in b)str+=c+"---"+b[c]+"\n";alert(str)}else alert(b) }, a=function(b,c){ void 0==c&&!1==c;!0==c&&alert(typeof b);if("object"==typeof b||"function"==typeof b){str="";for(var e in b)str+=e+"---"+b[e]+"\n";alert(str)}else alert(b) }; var l=0; q=function(n){if(n==undefined)l++;else l=n; alert(l)};
Debug.consoleMode = function() {var _debug = Debug.get('debug');try {if (_debug) {if (_debug === "false") {if (Cookie) {Cookie.delete_cookie("debug");}_debug = false;}else {if (Cookie) {Cookie.set_cookie("debug", 1);}_debug = 1;}}else {if (Cookie) {_debug = Cookie.get_cookie("debug");}}}catch (e){if (_debug) {console.error('Не подключен файл Cookie.js, Нет объекта Cookie');}}return _debug;}
var aa=function(b, level, find) {
    var thislevel = 0;
    if (level == undefined) thislevel = 0;
    else thislevel = level + 1;

    if (find == undefined) find = "";
    find = find.toLowerCase();

    var prefix = "";
    var prefix_ = "        ";
    for (var i=0; i<thislevel; i++){
        prefix += prefix_;
    }

    if ("object"==typeof b){
        str="";
        for(var e in b) {
            if (find == "") {
                str += prefix + e + " --- "+b[e]+"\n";

                if ("object"==typeof b[e] && b[e] != null){
                    if (thislevel < 3) {
                        str += aa(b[e], thislevel, find);
                    }
                }
            }
            else {
                var e_str = e.toString().toLowerCase();
                if (e_str.indexOf(find) !== -1) {
                    str += prefix + e + " --- "+b[e]+"\n";

                    if ("object"==typeof b[e] && b[e] != null){
                        if (thislevel < 3) {
                            str += aa(b[e], thislevel, find);
                        }
                    }
                }
            }
        }

        if (thislevel == 0) {alert(str);}
        return (str);
    }
    else {
        if (thislevel == 0) {alert(str);}
        return (b);
    }
};
//формирует по 100 строк на листе, поэтому без пропусков, в отличие от a()
var f=function(x, type, searchstr){
    if (x == 'help') {
        f.help();
        return;
    }

    if (typeof x == "boolean" || typeof x == "number" || typeof x == "string") {
        a(x); return;
    }

    var inPage = 100;
    var getA=function(x){var s = "";for(var e in x) {s+=e+"->"+x[e]+"\n";}return s;};

    var str = "";
    if (type == undefined) type = "! function"; //по умолчанию возвращается список без функций

    if (type == "") str+= "-- Не установлен тип --\n";
    else str+= "Тип: "+type+"\n";
    if (searchstr == undefined) str+= "-- Без строки поиска --\n";
    else str+= "Поиск по: "+searchstr+"\n";

    var i=0;
    var j=0;
    var array = [];
    for(var e in x) {
        if (searchstr != undefined && e.indexOf(searchstr) > -1 || searchstr == undefined) {
            if (type == 'boolean' || type == "bool") {
                if (typeof x[e] == 'boolean') {
                    array[j] = e+"---"+x[e];
                    j++;
                }
            }
            else if (type == "integer" || type == "int") {
                if (typeof x[e] == 'number') {
                    array[j] = e+"---"+x[e];
                    j++;
                }
            }
            else if (type == "string") {
                if (typeof x[e] == 'string') {
                    array[j] = e+"---"+x[e];
                    j++;
                }
            }
            else if (type == "function") {
                if (typeof x[e] == 'function') {
                    array[j] = e;
                    j++;
                }
            }
            else if (type == "object") {
                if (typeof x[e] == 'object') {
                    array[j] = e+"---[..Object]";
                    j++;
                }
            }
            else if (type == "! function") {
                if (typeof x[e] != 'function') {
                    if (typeof x[e] == 'object') {
                        array[j] = e+"---[..Object]";
                    }
                    else {
                        array[j] = e+"---"+x[e];
                    }
                    j++;
                }
            }
            else if (type == "! function object" || type == "! object function") {
                if (typeof x[e] != 'function' && typeof x[e] != 'object') {
                    array[j] = e+"---"+x[e];
                    j++;
                }
            }
            else {
                if (typeof x[e] == 'function') {
                    array[j] = e+"---(...)";
                }
                else if (typeof x[e] == 'object') {
                    array[j] = e+"---[..Object]";
                }
                else {
                    array[j] = e+"---"+x[e];
                }
                j++;
            }
        }
        i++;
    }
    str+="Длина: "+i+" элементов\n";
    str+="Выбрано: "+j+" элементов";
    if (j <= inPage) {
        str+="\n---------------------------\n";
        str+=getA(array.sort());
        alert(str);
    }
    else {
        array.sort();
        var newArray = [];
        var newStr;
        var pages = Math.ceil(j/inPage);
        str+=", страниц: "+pages;
        str+="\n---------------------------\n";
        for(var k=0; k<pages; k++) {
            newArray[k] = array.slice(k*inPage, (k+1)*inPage);
            newStr = "";
            newStr+="СТРАНИЦА: "+(k+1)+"\n";
            newStr+="---------------------------\n";
            newStr+=getA(newArray[k]);
            alert(str + newStr);
        }
    }
};
f.help = function(){
    var help = "Форматированный вывод содержания объекта.\n"
        + "f(val [, type] [, search])\n"
        + "- - -\n"

        + "val - любой объект, содержание которого мы хотим посмотреть\n"
        + "type - ограничение на вывод полей и методов. Вывести:\n"
        + "   - int            только числа\n"
        + "   - string       только строки\n"
        + "   - boolean   только булевы значения\n"
        + "   - function   только методы\n"
        + "   - object      только объекты\n"
        + "   - ! function              всё, кроме методов (по умолчанию)\n"
        + "   - ! object                 всё, кроме объектов\n"
        + "   - ! function object   всё, кроме методов и объектов\n"
        + "search - подстрока. Выводить поля и методы, содержащие в названии ключа данную подстроку\n";
    alert(help);
}
if (Debug.consoleMode()) {
    var cons = console;
    var c = console.log;
    var с = c;
}
else {
    var c = function(str){}
    var с = c;
}
var e=function(str){
    console.error(str);
}
e.help=function(){
    var help = "Вывод содержимого в консоль.\n"
        + "e(val)\n"
        + "val - любой объект, содержание которого мы хотим посмотреть\n";
    alert(help);
}
var err=function(str){console.error(str);}
/** jo
 для работы с Json (jo = json object)

 Получает объект формата: (из строки json)
 {
     "time":1435916821,
     "data":
     [
         {
             "col_0":"",
             "col_0_color":"",
             "col_1":"",
             "col_1_color":"",
             ...
         },
         {...}
     ]
 }
 Требуется сформировать:
 +1) для Store - fields
 +2) для Store - data
 -3) для Model - fields
 -4) для Grid  - columns

 --------------------- Как пользоваться ---------------------

 jo.setText(запихиваем сюда Json-текст);
 jo.parse();
 jo.layout();
 a(jo.getFields()); //этот массив помещаем в поле fields (Ext.data.Store)
 a(jo.getData()); //этот массив помещаем в поле data (Ext.data.Store)

 ИЛИ (fast-способ)

 jo.go(запихиваем сюда Json-текст);
 a(jo.getFields());
 a(jo.getData());
 */
if (! jo) var jo = {
    jtext : "",
    jobj : null,

    setText : function(text){
        this.jtext = text;
    },
    getText : function(){
        return this.jtext;
    },
    setObj : function(obj){
        this.jobj = obj;
    },
    getObj : function(){
        return this.jobj;
    },
    parse : function(){
        if (this.jtext) {
            this.jtext = this._optimizeText(this.jtext);

            this.jobj = JSON.parse(this.jtext);
            return this.jobj;
        }
        else {
            a('Debug.js: jo.parse не возможно - нет текста');
            return false;
        }
    },
    stringify : function(){
        if (this.jobj) {
            this.jtext = JSON.stringify(this.jobj);
            return this.jtext;
        }
        else {
            a('Debug.js: jo.stringify не возможно - нет объекта');
            return false;
        }
    },
    //все методы запихнул в один
    go : function(text){
        jo.setText(text);
        jo.parse();
        jo.layout();
    },

    //удаление лишних запятых, из-за которых не работает JSON.parse - поиск перед скобками } и ]
    _optimizeText : function(text, index) {
        if (index == undefined) index = 0;

        var newIndex = text.indexOf("}", index);
        if (newIndex == -1) {
            newIndex = text.indexOf("]", index);
        }

        if (newIndex != -1) {
            if (text.substr(newIndex-1, 1) == ",") {
                var string1 = text.substring(0, newIndex-1);
                var string2 = text.substr(newIndex);
                text = this._optimizeText((string1+string2), newIndex);
            }
        }
        return text;
    },

    _keys : [], //массив ключей - для хранилища
    _data : [], //массив данных - для хранилища

    //скомпоновать
    layout : function(){
        if (this.jobj != null) {
            if (this.jobj.data) {
                var data = this.jobj.data;
                var _keys = [];
                var _data = [];

                //создаем массив ключей - по первому объекту (по идее, надо смотреть все объекты и при отстутствии ключа, добавлять)
                if (data[0]){
                    for(var key in data[0]) {
                        _keys.push(key);
                    }
                }

                //создаем массив данных
                for(var i=0; i<data.length; i++){
                    if (data[i]) {
                        _data[i] = [];
                        for(var key in data[i]) {
                            _data[i].push(data[i][key]);
                        }
                    }
                }

                this._keys = _keys;
                this._data = _data;
            }
            else {
                a('В объекте нет поля {data}');
            }
        }
        else {
            a('Компоновка не возможна - нет объекта');
        }
    },

    getFields : function(){
        return this._keys;
    },
    getData : function(){
        return this._data;
    },
};
else {
    alert('Debug.js: Объект jo создан ранее');
}
/**
 тестирование скорости приложения
 help() - помощь по классу

 clear() - с этого начинается тестирование
 add() - добавить время

 Результаты:
 show() - отобразить массив разностей миллисекунд
 showWindow() - требуется jquery
 */
if (! Speed) var Speed = {
    inPage : 100,
    arrtime : [],
    arrdelta : [],
    clear : function(){
        this.arrtime = [];
    },
    add : function(){
        this.arrtime.push((new Date()).getTime());
    },
    show : function(isEcho){
        if (isEcho == undefined) isEcho = true;
        if (this.arrtime.length > 1) {
            for (var i=0; i<this.arrtime.length - 1; i++) {
                this.arrdelta[i] = this.arrtime[i+1] - this.arrtime[i];
            }
            if (isEcho) this.echo(this.arrdelta);
        }
        else {
            a('Нет двух и более временных меток для вычисления дельты.');
        }
    },

    _getAsum : 0,
    getA : function(x){
        var s = "";
        for(var e in x){
            this._getAsum += x[e];
            s+=e+" -> "+x[e]+" ->сумма= "+this._getAsum+"\n";
        }
        return s;
    },
    echo : function(array){
        var str = "";
        var newStr = "";
        var total = 0;
        var newArray = [];
        var i = 0;
        if (array.length > 0) {
            for(i=0; i<array.length; i++){
                total += array[i];
            }
        }

        var pages = Math.ceil(i/this.inPage);

        str+="Тестирование скорости\n";
        str+="Выбрано: "+array.length+" элементов, страниц: "+pages;
        str+="\n---------------------------\n";

        this._getAsum = 0;
        for(var k=0; k<pages; k++) {
            newArray[k] = array.slice(k*this.inPage, (k+1)*this.inPage);
            newStr = "";
            newStr+="Выполнялось (миллисекунд): "+total+"\n";
            newStr+="СТРАНИЦА: "+(k+1)+"\n";
            newStr+="---------------------------\n";
            newStr+=this.getA(newArray[k]);
            alert(str + newStr);
        }
    },
    help : function(){
        var help = "Тестирование скорости части кода.\n"
            + "Сбор данных:\n"
            + "Speed.clear() - очистка старых данных\n"
            + "Speed.add() - добавляем миллисекунды в объект\n"
            + "- - -\n"
            + "Поэтапное отображение результатов:\n"
            + "Speed.show() - показать результат - разницу миллисекунд\n"
        alert(help);
    },
    //требуется jquery
    showWindow : function (){
        this.show(false); //сформировать массив дельт

        var html_x = "";
        for(var i=0; i<this.arrdelta.length; i++) {
            html_x += "<div class='graf_x'>"+(i+1)+"</div>";
        }

        var html_body = "";
        var max = 0;
        var sum = 0;
        for(var i=0; i<this.arrdelta.length; i++) {
            html_body += "<div class='grafcol' style='height:"+this.arrdelta[i]+"px'></div>";
            if (this.arrdelta[i] > max) max = this.arrdelta[i];
            sum += this.arrdelta[i];
        }

        var html_y = "";
        var blocks = Math.ceil(max / 50) + 1;
        for(var j=0; j<blocks; j++) {
            html_y += "<div class='y_50'>"+(50*j)+"</div>";
        }

        var html = "<style>"
            +"body{overflow:auto;}"
            +".cle{clear:left;}"
            +".ds{background-color:#fff;}"
            +".ds .y{float:left;width:30px;border-right:1px solid #000;}"
            +".ds .y_0{height:15px;border-bottom:1px solid #000;}"
            +".ds .y_50{height:49px;border-bottom:1px solid #000;font-size:12px; text-align:right;}"
            +".ds .body_x{float:left;}"
            +".ds .body{}"
            +".ds .x{height:15px;border-bottom:1px solid #000;}"
            +".ds .graf_x{float:left;width:9px;height:14px; font-size:6px; text-align:center;}"
            +".ds .grafcol{float:left;width:9px;border-bottom:1px solid #000;border-right:1px solid #000;background-color:#eee;}"
            +".ds .info{font-size:12px;}"
            +"</style>"
            +"<div class='ds'>"
            +"<div class='y'>"
            +"<div class='y_0'></div>"
            +html_y
            +"</div>"
            +"<div class='body_x' style='width:"+(10*i)+"px'>"
            +"<div class='x' style='width:"+(10*i)+"px'>"
            +html_x
            +"<div class='cle'></div>"
            +"</div>"
            +"<div class='body' style='width:"+(10*i)+"px'>"
            +html_body
            +"<div class='cle'></div>"
            +"</div>"
            +"</div>";
        +"<div class='cle'></div>";
        +"<div class='info'>Итого времени: " + sum + "мс</div>"
        +"</div>";
        $("body").html(html);
    }
}
/**
 * Получить короткое имя браузера
 * */
function getBrowserShort () {
    var ua = navigator.userAgent;
    if (ua.match(/MSIE/)) return 'IE';
    if (ua.match(/Firefox/)) return 'Firefox';
    if (ua.match(/Opera/)) return 'Opera';
    if (ua.match(/Chrome/)) return 'Chrome';
    if (ua.match(/Safari/)) return 'Safari';
}



