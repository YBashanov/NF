/*
Кнопка и поле ПОИСК - объект изменяет url, а что дальше - не его забота
Требуется
- Handler
- Wait

v1.0 - начало 2014
v1.1 - 2014-05-27
v1.2 - 2014-10-23 - переименовал некоторые функции в связи с последними версиями подключаемых классов

Search.setSettings({
	startColor 		: "#666",
	startFontStyle 	: "italic",
	setString 		: "",
	setColor 		: "#222",
	setFontStyle 	: "normal",
	action			: "after_post",
	path			: "content/udk_search",
	parent_div		: "parentUDK",
	null_divs		: {
		0 : "contentUDK"
	},
	errorColor		: "#f00",
	minlength		: 4
});
Search.start("search_catalog", "поиск...");
*/
var Search = {
	thisFile : "js/search",
	settings : {},
	
	//память - не только для ПОИСК, а также для пометки иных полей форм
	memory : {},	//запоминает для простой, одноязычной записи
	memory_id : 0,	//указатель на ячейку в памяти
	
	//возвращает текст в зависимости от языка
	//это чисто для ПОИСК
	getLangText : function(key) {
		var object = {
			"ru" : {
				"search" : "поиск..."
			},
			"en" : {
				"search" : "search..."
			}
		}
		
		return object[language][key];
	},
	
	setSettings : function (settings) {
		this.settings = {};
		this.settings.moveTrigger = false;
		
		this.ie = true;
		if (document.addEventListener) this.ie = false;
		
		if (settings == undefined) settings = false;
		if (settings) {
			for (var val in settings) {
				this.settings[val] 	= settings[val];
			}
		}
		else return false;
	},
	
	//статьи
	usingCalendar : function(day, month, year){
		//очистка URL
		var newURL = "";
		if (get) {
			for (var key in get) {
				if (
					key !== "searchTime"
				) {
					if (get[key] != "")
						newURL += key+"="+get[key]+"&";
				}
			}			
		}
		var suffix = "";

		month = month-1;
		
		var date = new Date();
		date.setDate(day);
		date.setMonth(month);
		date.setFullYear(year);
		date.setHours(0);
		date.setMinutes(0);
		date.setSeconds(0);
		var time = Math.floor(date.getTime()/1000);

		if (time != 0) 	suffix += "searchTime="+time+"&";

		location.href = "http://"+server+"/search/?"+newURL + suffix;
	},
	
	//text_en - не доработано
	start : function (id, text, text_en){
		if (id == undefined) id = "searchHead";
		if (text == undefined)text = "";
		if (text_en == undefined)text_en = "";
		
		//память - не только для ПОИСК, а также для пометки иных полей форм
		Search.memory[id] = {
			"ru" : {
				"text" : text
			},
			"en" : {
				"text" : text_en
			}
		};
		
		var input = document.getElementById(id);

		input.value = Search.memory[id][language]["text"];
		input.style.color = Search.settings.startColor;
		input.style.fontStyle = Search.settings.startFontStyle;

		Search.settings.input = input;
		Search.setListeners (input, id);
	},
	
	setListeners : function(element, id){
		Handler.add(element, "focus", function(e){
			if (element.value == Search.memory[id][language]["text"]) {
				element.value = Search.settings.setString;
				element.style.color = Search.settings.setColor;
				element.style.fontStyle = Search.settings.setFontStyle;
			}
		});
		
		Handler.add(element, "blur", function(e){
			if (element.value == Search.settings.setString) {
				element.value = Search.memory[id][language]["text"];
				element.style.color = Search.settings.startColor;
				element.style.fontStyle = Search.settings.startFontStyle;
			}
		});
		
		Handler.add(element, "keydown", function(e){
			var keyCode = e.keyCode;
			if (keyCode == undefined) keyCode = window.event.keyCode;
			
			if (
				element.value !== Search.memory[id][language]["text"] &&
				element.value !== Search.settings.setString
			){
				if (keyCode == 13){
					Search.searchClick(element.value);
				}
			}
		});
	},
	
	//действие зависит от установленных параметров
	//action = direct (прямое воздействие)
	//action = after_post (воздействие на адресную строку через post)
		//(after_post) path - путь до файла обработчика
		//(after_post) parent_div - блок, в который возвращаются данные после выборки из базы
		//(after_post) null_divs - блоки, которые мы собираемся очистить (перечисление в виде числового объекта)
	searchClick : function (value) {
		if (Search.settings.minlength) {
			//если это алфавитные символы (не число)
			if (parseInt(value) != value) {
				if (value.length < Search.settings.minlength) {
					var input = Search.settings.input;
					input.style.color = Search.settings.errorColor;
					input.value = "Введите не менее " + Search.settings.minlength + " букв";
					input.blur();
					
					setTimeout(function(){
						input.style.color = Search.settings.startColor;
						input.style.fontStyle = Search.settings.startFontStyle;
						input.value = Search.getLangText("search");
					}, 2000);
					return false;
				}
			}
		}
		
		if (value) {
			value = encodeURIComponent(value);

			if (Search.settings.action == "direct") {
				location.href = "http://"+server+"/"+page+"/"+language+"/?t="+value;
			}
			else if (Search.settings.action == "after_post") {
				if (Search.settings.path == undefined) {
					Error.add("Search: Путь path не выбран");
				}
				else {
					//прогресс
					var input = Search.settings.input;
					Wait.imgBarStop(input);
					Wait.imgBar(input);
					
					HTTP.post (
						"http://"+server+"/ajax/listen/"+Search.settings.path+".php",
						{
							"language":language,
							"value":value
						},
						function (data){
							var res = data.split("|");
							if (res[0] == 1) {
								//выдача вариантов со ссылками в parent
								if (Search.settings.parent_div) {
									Wait.imgBarStop(input);
									
									var parent = document.getElementById(Search.settings.parent_div);
									parent.innerHTML = res[1];
									
									if (Search.settings.null_divs) {
										for(var val in Search.settings.null_divs) {
											var null_div = document.getElementById(Search.settings.null_divs[val]);
											if (null_div) null_div.style.display = "none";
										}
									}
								}
								else {
									Error.add("Search: Не указано, в какой div возвращать данные");
								}
							}
							else if (res[0] == 2) {
								//надпись, что нет такой выдачи, либо другая ошибка
								Wait.imgBarStop(input);
								input.style.color = Search.settings.errorColor;
								input.value = "Ничего не найдено";
								input.blur();
								
								setTimeout(function(){
									input.style.color = Search.settings.startColor;
									input.style.fontStyle = Search.settings.startFontStyle;
									input.value = Search.getLangText("search");
								}, 1000);
							}
							else {
								Error.add(data);
								Wait.imgBarStop(input);
							}
						}
					);
				}
			}
			else {
				Error.add("Search: Воздействие на url не выбрано");
			}
		}
	},
	
	imgBarStop : function (){
		Wait.imgBarStop(Search.settings.input);
	},
	
	
	sbuttonClick : function (){
		var element = document.getElementById("searchHead");
		if (
			element.value !== Search.getLangText("search") &&
			element.value !== Search.settings.setString
		){
			Search.searchClick(element.value);
		}
	},
	
	
	//использование автоподстановки части url - при выборе select
	//element - это this, используемый при вызове события из элемента: onchange='Search.changeSelect(this)'
	changeSelect : function (element){
		if (element == undefined) {
			alert("Search.changeSelect: Не указан элемент-инициатор события");
			return false;
		}
		
		var npage 		= document.getElementById("cat_npage");
		var inpage 		= document.getElementById(element.id);

		var a_url = {};
		var path = "";
		if (url) {
			a_url = url.split("?");
			if (a_url[0]) {
				path = a_url[0];
			}
		}
		else {
			path = "/catalog/";
		}
		
		//очистка URL
		var newURL = "";
		if (get) {
			for (var key in get) {
				if (
					key !== "npage" &&
					key !== "inPage"
				) {
					if (get[key] != "")
						newURL += key+"="+get[key]+"&";
				}
			}			
		}
		var suffix = "";

		if (npage.value) 	suffix += "npage="+npage.value+"&";
		if (inpage.value)	suffix += "inPage="+inpage.value+"&";

		location.href = "http://" + server + path + "?" + newURL + suffix;
	}
}