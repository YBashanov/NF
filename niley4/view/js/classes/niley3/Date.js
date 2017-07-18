/*
дополнение функционала метода Date
*/

if (! Date){
	Date = {};
}

//ВЫЧИТАНИЕ из одного объекта Даты другого. Возвращает результат в секундах
//оба параметра - представление даты xdate.toUTCString() (в строковом виде)
if (! Date.subtract) Date.subtract = function(utcstring1, utcstring2){
	if (utcstring1 == undefined) {
		Error.add("Date.subtract: Не существует аргумент 1", "classes/Date.js");
		return false;
	}
	if (utcstring2 == undefined) {
		Error.add("Date.subtract: Не существует аргумент 2", "classes/Date.js");
		return false;
	}

	var a_utcstring1 = utcstring1.split(" ");
	if (a_utcstring1[5] == "GMT" || a_utcstring1[5] == "UTC") {
		a_utcstring1[2] = Date.getNumMonth(a_utcstring1[2]);
	
		a_time_utcstring1 = a_utcstring1[4].split(":");	
	}
	else {
		Error.add("Date.subtract: Неверный формат аргумента 1", "classes/Date.js");
		return false;
	}

	var a_utcstring2 = utcstring2.split(" ");
	if (a_utcstring2[5] == "GMT" || a_utcstring2[5] == "UTC") {
		a_utcstring2[2] = Date.getNumMonth(a_utcstring2[2]);
	
		a_time_utcstring2 = a_utcstring2[4].split(":");	
	}
	else {
		Error.add("Date.subtract: Неверный формат аргумента 2", "classes/Date.js");
		return false;
	}
	
	/*
		1 - day
		2 - Month (Sep)
		3 - year
		4 - 
			0 - hour
			1 - minutes
			2 - seconds
	*/
	var date1 = new Date(a_utcstring1[3], a_utcstring1[2], a_utcstring1[1], a_time_utcstring1[0], a_time_utcstring1[1], a_time_utcstring1[2], 0);
	var date2 = new Date(a_utcstring2[3], a_utcstring2[2], a_utcstring2[1], a_time_utcstring2[0], a_time_utcstring2[1], a_time_utcstring2[2], 0);
	var time1 = date1.getTime() / 1000;
	var time2 = date2.getTime() / 1000;

	return (time1 - time2);
}


//возвращает временную метку - количество секунд
if (! Date.time) Date.time = function(){
	var mydate = new Date();
	return parseInt(mydate.getTime()/1000);
}

/*
* Создает форматированную строку на основе {@link Date}
*
* <b>Параметры</b>
* format - строка вида Y-m-d H:i:s
*   - n - месяц сокращенно (3 буквы - фев, мар, апр...)
*   - g - месяц полный (genitive) родительный падеж
*
* <b>Возвращает</b>
* {@link String}
* */
Date.prototype.date = function(format){
    var my = this;

    if (format) {
        var length = format.length;
        var paramArray = [];
        var paramArray_i = -1; //итератор
        var symbol = "";
        var lastSymbol = "";

        for (var i=0; i<length; i++) {
            symbol = format[i];

            if (symbol != lastSymbol) {
                paramArray.push(symbol);
                paramArray_i++;
            }
            else {
                paramArray[paramArray_i] += symbol;
            }
            lastSymbol = symbol;
        }


        if (paramArray) {
            var result = "";
            var _res = ""; //промежуточный результат
            var length2 = paramArray.length;
            var phrase = "";
            var namePhrase = true; //ключ

            for (i=0; i<length2; i++) {
                phrase = paramArray[i];
                namePhrase = false;
                _res = "";

                if (phrase == "Y") {
                    _res = my.getFullYear();
                    namePhrase = true;
                }
                //сокращенная запись месяца
                else if (phrase == "n"){
                    _res = Date.prototype.getMonthName(my.getMonth(), "n");
                    namePhrase = false;
                }
                else if (phrase == "m"){
                    _res = my.getMonth() + 1;
                    namePhrase = true;
                }
                else if (phrase == "d"){
                    _res = my.getDate();
                    namePhrase = true;
                }
                else if (phrase == "H"){
                    _res = my.getHours();
                    namePhrase = true;
                }
                else if (phrase == "i"){
                    _res = my.getMinutes();
                    namePhrase = true;
                }
                else if (phrase == "s"){
                    _res = my.getSeconds();
                    namePhrase = true;
                }
                else {
                    result += phrase;
                }

                //добавление данных в строку
                if (namePhrase == true) {
                    if (_res.toString().length == 1) {
                        _res = "0" + _res;
                    }
                    result += _res;
                }
                else {
                    result += _res;
                }
            }
        }

        return result;
    }
    else {
        console.log("Date.date - нет параметра format");
        return null;
    }
};
Date.prototype.getMonthName = function(monthNum, format){
    var my = this;
    if (format == undefined) format = "def";
    
    var ret = Date.prototype.monthNames[format][monthNum];
    if (ret) {
        return ret;
    }
    else {
        return null;
    }
}
Date.prototype.monthNames = {
    def : [],
    n : [
        "янв",
        "фев",
        "мар",
        "апр",
        "мая",
        "июн",
        "июл",
        "авг",
        "сен",
        "окт",
        "ноя",
        "дек"
    ],
    g : [
        "января",
        "февраля",
        "марта",
        "апреля",
        "мая",
        "июня",
        "июля",
        "августа",
        "сентября",
        "октября",
        "ноября",
        "декабря"
    ]
};



