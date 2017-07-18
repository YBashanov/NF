//изменена 12.06.12
//изменена 2013-06-07
//доработана Date.calendarOpen(), всего 5 параметров
//изменена 2013-06-24
//глобальная доработка 2013-08-17
//2013-12-02
//pathToDataLib - используется, когда функция вызывается не через ajax, а напрямую. При этом путь прописывается перед включением данного файла.
//
if (! Date){
	Date = {};
}
Date.action = "input";//варианты - search - замена url, input - вставка в input

Date.set_month = function (id, FormName, InputName){
	var timeElement = document.getElementById('timenow_'+id);
	var timenow = timeElement.getAttribute("timenow");
	var windowidElement = document.getElementById('windowid_'+id);
	var windowid = windowidElement.getAttribute("info");
	var outside_divElement = document.getElementById('outside_div_'+id);
	var outside_div = outside_divElement.getAttribute("info");
	var parent = document.getElementById('date_calendar'+windowid+'_'+id);

	if (pathToDataLib == undefined) {
		var ajaxElement = document.getElementById('pathToAjax');
		var path = ajaxElement.getAttribute("path");
	}
	else var path = pathToDataLib;

	var a1 = document.getElementById('month_'+id+'');
	var SetMonth = a1.options[a1.selectedIndex].value;
	
	var buttonYear1 = document.getElementById('calendar_button1_'+id);
	var buttonYear2 = document.getElementById('calendar_button2_'+id);
	var buttonMonth = document.getElementById('month_'+id);
	buttonMonth.disabled = "disabled";
	buttonYear1.disabled = "disabled";
	buttonYear2.disabled = "disabled";

	HTTP.post (
		path,
		{
			'FormName' : FormName,
			'InputName' : InputName,
			'SetMonth' : SetMonth,
			'time' : timenow,
			'id' : id,
			'windowid' : windowid,
			'outside_div' : outside_div
		},
		function (data){
			parent.innerHTML = data;
		}
	);
}

Date.set_year = function (id, year, FormName, InputName){
	var timeElement = document.getElementById('timenow_'+id);
	var timenow = timeElement.getAttribute("timenow");	
	var windowidElement = document.getElementById('windowid_'+id);
	var windowid = windowidElement.getAttribute("info");
	var outside_divElement = document.getElementById('outside_div_'+id);
	var outside_div = outside_divElement.getAttribute("info");
	var parent = document.getElementById('date_calendar'+windowid+'_'+id);
	if (pathToDataLib == undefined) {
		var ajaxElement = document.getElementById('pathToAjax');
		var path = ajaxElement.getAttribute("path");
	}
	else var path = pathToDataLib;
	
	var buttonYear1 = document.getElementById('calendar_button1_'+id);
	var buttonYear2 = document.getElementById('calendar_button2_'+id);
	var buttonMonth = document.getElementById('month_'+id);
	buttonMonth.disabled = "disabled";
	buttonYear1.disabled = "disabled";
	buttonYear2.disabled = "disabled";

	HTTP.post (
		path,
		{
			'FormName' : FormName,
			'InputName' : InputName,
			'SetYear' : year,
			'time' : timenow,
			'id' : id,
			'windowid' : windowid,
			'outside_div' : outside_div
		},
		function (data){
			parent.innerHTML = data;
		}
	);
}


//открывает (закрывает) таблицу календаря (календарей)
Date.calendarid = 0;
Date.buttonShow = false;
Date.openWindows = Array(); //массив - можно посмотреть, какие окна открыты (счет ведется от 0)
//name_id - id календаря (как правило, div-ы календарей называют: date_calendar1_1, date_calendar1_2, date_calendar1_3...)
	//если они открыты во всплывающем окне №1
//to_id - внешние блоки, внутри которых появляются календари
	//они называются соответственно calendar1_1, calendar1_2, calendar1_3...
	
	//первое число - персональный номер открытого всплыв. окна (в случае Андромед)
	//второе число - номер календаря
//id - порядковый номер элементов в окне (в одном окне может быть несколько input). Соответствует номеру в name_id: 1, 2, 3...
//FormName
//InputName
//windowid - персональный номер открытого окна (если эти окна выпадающие). Это может быть id клиента или заказа...
Date.calendarOpen = function (name_id, to_id, id, FormName, InputName, windowid) {
	if (Date.buttonShow == false) {
		Date.buttonShow = true;

		var parent = document.getElementById(to_id);

		HTTP.post (
			"http://" + server + "/libraries/L_date/l_call_object.php",
			{
				'FormName' : FormName,
				'InputName' : InputName,
				'id' : id,
				'windowid' : windowid
			},
			function (data){
				var res = data.split ("|");

				//если окно открыто
				if (Date.openWindows[windowid] == true) {
					Popup.openClose (name_id);//возвращаем в функцию, а не в элемент

					Date.openWindows[windowid] = false;
/*
//запись в базу
if (
	windowid != "a1" &&
	windowid != "a2" &&
	windowid != "a3" &&
	windowid != "a4"
) {
	//запишем в базу
	lead_time(windowid);
}
else {
	//сортируем
	sortDB();
}*/
				}
				//если окно закрыто
				else {
					//if IE, Opera
					if (parent.currentStyle) {
						Popup.open (name_id, true, res[0], to_id);//возвращаем в функцию, а не в элемент
					}
					//Firefox
					else {
						parent.innerHTML = res[0];
						Popup.open (name_id, true);
					}
					Date.openWindows[windowid] = true;
				}
			}
		);

		Date.buttonShow = false;
	}
}


//установка значения в input
//Date.setInput = function (FormName, InputName, FprintDay, NewMonth, NewYear, name_id, to_id, id, windowid) {
Date.setInput = function (FormName, InputName, FprintDay, NewMonth, NewYear, outside_div) {
	if (Date.action == "input") {
		var val = ""+ FprintDay +"-"+ NewMonth +"-"+ NewYear +"";
		var input = document[FormName][InputName];

		input.value = val;

		//если хотим, чтобы календарь автоматически закрывался, надо это настроить. 
		//Предыдущая схема работала плохо!
		//Date.calendarOpen (name_id, to_id, id, FormName, InputName, windowid);

		//вот эта схема должна работать:
		//Popup.openClose(outside_div);
	}
	//дополнительная модульность - сторонние функции, не относящиеся напрямую к Date
	else if (Date.action == "search") {
		Search.usingCalendar(FprintDay, NewMonth, NewYear);
	}
}
//размещение параметров в адресной строке (для функции поиска).
//Чтобы активировать данную функцию, необходимо 
//Search.usingCalendar();


//открывает календарь только по временной метке, и загружает в пустой div (2013-06-07)
Date.open = function (timenow, id){
	if (id == undefined) id = "date_calendar0_0";

	var parent = document.getElementById(id);
	parent.innerHTML = "<img src='http://"+server+"/@/img/wait.gif' width='30'/>";
	var path = "http://"+server+"/libraries/L_date/l_call_object.php";

	var dates = new Date();
	dates.setTime(timenow*1000);	
	var SetMonth = dates.getMonth()+1;
	var FormName = "";
	var InputName = "";
	var SetId = 0;
	var windowid = 0;

	HTTP.post (
		path,
		{
			'FormName' : FormName,
			'InputName' : InputName,
			'SetMonth' : SetMonth,
			'time' : timenow,
			'id' : SetId,
			'windowid' : windowid
		},
		function (data){
			parent.innerHTML = data;
		}
	);
}