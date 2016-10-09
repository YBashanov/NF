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

//возвращает числовое представление месяца по названию
if (! Date.getNumMonth) Date.getNumMonth = function(s_month){
	if (s_month == undefined) return false;
	
	var a_month = {
		"Jan":"1",
		"Feb":"2",
		"Mar":"3",
		"Apr":"4",
		"May":"5",
		"Jun":"6",
		"Jul":"7",
		"Aug":"8",
		"Sep":"9",
		"Oct":"10",
		"Nov":"11",
		"Dec":"12"
	}
	
	return a_month[s_month];
}

//возвращает временную метку - количество секунд
if (! Date.time) Date.time = function(){
	var mydate = new Date();
	return parseInt(mydate.getTime()/1000);
}





