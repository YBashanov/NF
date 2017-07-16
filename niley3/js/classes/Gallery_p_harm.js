/* 
2014-05-12
Галерея Гармошка (она же Линия) - просмотр нескольких картинок, смена картинок нажатием на стрелки и прокруткой колесом мыши
v 1.1
-----------------------------
обработчик вешается на number (без проверки информации), информация подгружается сразу, без ajax
Прокрутка происходит по одному элементу

Необходимые классы:
- Handler
- Universal

var param1 = {
	"numOfGallery"	: "1",				//[умолч. = 1] номер галереи на странице (на листе может быть несколько галерей одновременно)
	"htmlClassName"	: "photomini",		//класс html-шаблона. Сюда будет литься инфа
	"dataClassName"	: "i_banner",		//скрытая верстка. Полная верстка, связанная с БД. Отсюда берется инфа
	"textDivId"		: "banner_text",	//куда записывается текст, если он есть (тут одиночный блок, требуется доработка)
	"action"		: "",				//показывает, как работать с html-шаблоном.
		simple	//[умолч.] самый простой, когда htmlClassName - те div-ы, которые непосредственно будут наполняться инфой
		common	//когда блок htmlClassName является общим для остальных, подчиненных div-ов, сделанных для наполнения инфой
			тут включается функционал доп. прогона (поиска) элементов 2го уровня вложенности
	"stylesCallback": stylesCallback	//внешняя функция обратного вызова, в которой мы выставляем параметры стилей элементов
	"moveSettings" 	: moveSettings		//внешние установки, с помощью которых мы выставляем параметры перемещения элементов
	"arrows"		: false				//если true, то загружает обработчики для стрелок влево-вправо:
		id='arrow_left{GALLERY_NUM_1}'
		id='arrow_right{GALLERY_NUM_1}'
	"clickSide"		: false				//если true, то клики по крайним боковым элементам равнозначны кликам по стрелкам
	"numberTextShow : 2					//если есть, переносит тест для просмотра в отдельный div
										//номер показывает номер активного diva, текст которого будет выводиться в отдельный div
	"clickCallback" : clickCallback		//функция обратного вызова, в которой мы устанавливаем поведение элемента element, соответствующего
										// номеру в галерее number
	clickSide и idBigFoto - не советую использовать вместе
};

в html-шаблоне необходимо организовать 2 типа верстки:
1. информационная
	выводится вся информация целиком, простая скрытая верстка
2. визуализационная
	только несколько "качелей", которые наполняются инфой из информационного массива
	
-----------------------------
Описание moveSettings

var steps = 5;
var speedMovings = 20;
var moveSettings = {
	left : {
		0 : {
			step : steps,
			speedMoving : speedMovings
		},
		1 : {
			length_top : 0,
			length_left : 78,
			length_width : 0,
			length_height : 0,
			step : steps,
			speedMoving : speedMovings
		},
		2 : {
			length_top : 25,
			length_left : -78,
			length_width : -110,
			length_height : -50,
			step : steps,
			speedMoving : speedMovings,
			childNodes : {			//если есть изменяемые "дети", тут описываются свойства изменений
				picture : {			//это div - одновременно и id в html-шаблоне. Состав: id='picture{GALLERY_NUM_1}_5'
					childNum : 0,	//это - уровень вложенности содержимого. Здесь - 1й уровень вложенности
					length_top : 0,
					length_left : 0,
					length_width : -110,
					length_height : -50,
					step : steps,
					speedMoving : speedMovings
				}
			}
		},
	},
	right : {}
}
*/
var Gallery_p_harm = function() {}

Gallery_p_harm.prototype = {
	setSettings : function (obj) {
		this.settings = {};
		this.settings.moveTrigger = false;
		
		this.ie = true;
		if (document.addEventListener) this.ie = false;
		
		if (obj == undefined) obj = false;
		if (obj) {
			for (var val in obj) {
				this.settings[val] 	= obj[val];
			}
		}
		else return false;
	},
	
	//определяем только те элементы, которые есть в наличии
	//базовые элементы-контейнеры загоняются благодаря разрешению экрана
	//при смене разрешения происходит перерегистрация элементов [в разработке]
	setDivs : function() {
		var htmlClassName = this.settings.htmlClassName;
		var dataClassName = this.settings.dataClassName;
		var numOfGallery = this.settings.numOfGallery;
		this.htmlClass = htmlClassName + numOfGallery;
		this.dataClass = dataClassName + numOfGallery;
		if (this.settings.idBigFoto) this.settings.idBigFoto = document.getElementById(this.settings.idBigFoto);

		if (this.settings.action == undefined || this.settings.action == "simple") {
			
			//не тестировал!
			
			var htmlNow = document.getElementsByClassName(this.htmlClass);
			this.dataNow = document.getElementsByClassName(this.dataClass);
			this.htmlNowLength = this.htmlNow.length;
			this.dataNowLength = this.dataNow.length;
			this.setPositionAndListeners ();
		}
		else if (this.settings.action == "common") {
			var parent = document.getElementsByClassName(this.htmlClass);
			var htmlAll = parent[0].childNodes;
			this.htmlNow = {};
			var i=0;
			for (var val in htmlAll) {
				var element = htmlAll[val];
				if (!this.ie && element == length) {break;}
				if (element.tagName == "DIV") {
					this.htmlNow[i] = element;
					i++;
				}
			}
			this.dataNow = document.getElementsByClassName(this.dataClass);
			this.htmlNowLength = i;
			this.dataNowLength = this.dataNow.length;

			this.setPositionAndListeners ();
		}
		else {
			//поведение не определено
		}
		
		if (this.settings.arrows == true) {
			this.setListenersArrows();
		}
	},
	
	//установка положения и прослушивателей
	setPositionAndListeners : function (){
		var blocks = this.htmlNow;
		var length = this.htmlNow.length;
		var data = this.dataNow;
		var number = 0;
		this.i_id = {};

		//берется только некоторое количество из Данных
		for (var i=0; i<this.htmlNowLength; i++) {
			if (data[i]) {
				this.i_id[i] = this.getId(data[i].id);
			}
		}

		i=0; //т.к. number отличается в разных браузерах
		for (var val in blocks) {
			var element = blocks[val];
			
			//запишем первый элемент - для первого авто-клика (авто-показа)
			if (i == 0) this.firstElement = element;
			
			if (!this.ie && element == length) {break;} //Opera Bug - сюда ставим количество блоков

			if (element.tagName == "DIV") {
				number = i;
				i++;

				this.setInfo(element, this.i_id[number], number);
				this.setListeners (element, number);
			}
		}

		this.handlerAddMouseWheel();
		
		//присвоение ключей-границ
		this.i_id0 = 0;
		this.i_idL = this.htmlNowLength-1;
		this.setStyles();
	},
	
	//вернуть уникальный номер на основе названия
	getId : function (idName) {
		return idName.substr(this.dataClass.length+1);
	},
	
	//перечисляем переменные, которые хотим заменить
	setInfo : function (element, i_id, number) {
		var i_picture = "";
		var i_text = "";

		if (i_id == undefined) {}
		else {
			var element_pucture = document.getElementById("i_picture"+this.settings.numOfGallery+"_"+i_id);
			var element_text = document.getElementById("i_text"+this.settings.numOfGallery+"_"+i_id);
			

			if (element_pucture) {
				if (element_pucture.innerHTML)
					i_picture = element_pucture.innerHTML;
				else 
					i_picture = "<img src='"+base_image+"harmonica/nofoto.jpg' />";
			}
			else i_picture = "<img src='"+base_image+"harmonica/nofoto.jpg' />";
			
			if (element_text) i_text 	= element_text.innerHTML;
			else i_text = "&nbsp;";
			
			i_picture = i_picture.replace(/(^\s+|\s+$)/g, '');
			document.getElementById("picture"+this.settings.numOfGallery+"_"+number).innerHTML = i_picture;

			if (this.settings.numberTextShow) {
				if (number == this.settings.numberTextShow) document.getElementById(this.settings.textDivId).innerHTML = i_text;
			}
		}
	},
	
	//как сделать так, чтобы стили не зависели от количества блоков?
	//этот метод должен быть выносным
	//вызывается так: ОБЪЕКТ.setStyles(function(htmlNow, ie){}
	setStyles : function() {
		var htmlNow = this.htmlNow;
		var ie = this.ie;

		var callback = this.settings.stylesCallback;
		if (callback == undefined) { alert("Gallery_p_harm.setStyles: Ошибка. Нет настроек styles для элементов.\nГалерея №"+this.settings.numOfGallery);}
		else {
			callback(htmlNow, ie);
			this.settings.moveTrigger = false;
		}
	},
	
	setListeners : function (element, number){
		var objThis = this;

		//клик по крайним
		if (objThis.settings.clickSide) {
			Handler.add(element, "click", function(e){
				if (number == 1) {
					//положительное направление
					Gallery_p_harm.prototype.moveGallery(1, objThis);
				}
				if ((objThis.htmlNowLength-2) > 1) {
					if (number == objThis.htmlNowLength-2) {
						//положительное направление
						Gallery_p_harm.prototype.moveGallery(-1, objThis);
					}
				}
			});
		}
		else {
			//функция обратного вызова на клик
			if (objThis.settings.clickCallback) {
				Handler.add(element, "click", function(e){
					var callback = objThis.settings.clickCallback;
					callback(objThis, element, number);
				});
			}
			else {
				alert("Gallery_p_harm.setListeners: Ошибка. Нет функции обратного вызова.\nГалерея №"+objThis.settings.numOfGallery);
			}
		}
	},
	
	//прокрутка
	handlerAddMouseWheel : function(){
		var objThis = this;

		var parent = document.getElementById("gallery_line_"+objThis.settings.numOfGallery);
		Handler.add(parent, "mousewheel", function(e){
			e = e ? e : window.event; 
			// Получить элемент, на котором произошло событие 
			var wheelElem = e.target ? e.target : e.srcElement;
			// Получить значение поворота колесика мыши 
			var wheelData = e.detail ? e.detail * -1 : e.wheelDelta / 40;
			// В движке WebKit возвращается значение в 100 раз больше 
			if (Math.abs(wheelData)>100) { wheelData=Math.round(wheelData/100); }

			// Теперь в переменной wheelElem содержится элемент, который получил 
			// собщение от колесика мыши, а в wheelData значение поворота колеса 
			Gallery_p_harm.prototype.moveGallery(wheelData, objThis);
			
			// Отменить штатные действия браузера при кручении колеса мыши 
			return cancelEvent(e);
		});
	},
	
	setListenersArrows : function() {
		var objThis = this;
		
		var arrowLeft = document.getElementById("arrow_left"+this.settings.numOfGallery);
		var arrowRight= document.getElementById("arrow_right"+this.settings.numOfGallery);
		
		Handler.add(arrowLeft, "click", function(e){
			Gallery_p_harm.prototype.moveGallery(1, objThis);
		});
		
		Handler.add(arrowRight, "click", function(e){
			Gallery_p_harm.prototype.moveGallery(-1, objThis);
		});
	},
	
	//необходимо сперва установить параметры для перемещения - устанавливаются извне
	//var moveSettings = { left : {	0 : {} }, right : { 0 : {} } };
	moveGallery : function (action, objThis) {
		if (objThis.settings.moveTrigger == false) {
			objThis.settings.moveTrigger = true;
			
			var htmlNow = objThis.htmlNow;
			var ie = objThis.ie;
			var moveSettings = objThis.settings.moveSettings;
			
			var htmlLength = objThis.htmlNowLength;
			var dataLength = objThis.dataNowLength;
			
			//слева направо (уменьшение номера)
			if (action > 0) {
				for (var k=0; k<htmlLength; k++) {
					if (k < htmlLength-1) {
						if (moveSettings.right[k].childNodes) {
							for (var val in moveSettings.right[k].childNodes) {
								var childDiv = document.getElementById(val + objThis.settings.numOfGallery + "_" + k);
								var childElement = childDiv.childNodes[moveSettings.right[k].childNodes[val].childNum];
								increasePosition(childElement, moveSettings.right[k].childNodes[val]);
							}
						}
						
						increasePosition(objThis.htmlNow[k], moveSettings.right[k]);
					}
					else {
						if (moveSettings.right[k].childNodes) {
							for (var val in moveSettings.right[k].childNodes) {
								var childDiv = document.getElementById(val + objThis.settings.numOfGallery + "_" + k);
								var childElement = childDiv.childNodes[moveSettings.right[k].childNodes[val].childNum];
								increasePosition(childElement, moveSettings.right[k].childNodes[val]);
							}
						}

						increasePosition(objThis.htmlNow[k], moveSettings.right[k], function(){
							if  (objThis.i_id0 == 0) objThis.i_id0 = dataLength-1;
							else objThis.i_id0--;

							if  (objThis.i_idL == 0) objThis.i_idL = dataLength-1;
							else objThis.i_idL--;

							for (var i=htmlLength-1; i>=0; i--) {
								if (i == 0) {
									objThis.i_id[i] = objThis.getId(objThis.dataNow[objThis.i_id0].id);
								}
								else {
									objThis.i_id[i] = objThis.i_id[i-1];
								}
								
								objThis.setInfo(objThis.htmlNow[i], objThis.i_id[i], i);
							}
							
							objThis.setStyles();
							objThis.settings.moveTrigger = false;
						});
					}
				}
			}
			else if (action < 0) {
				for (var k=0; k<htmlLength; k++) {
					if (k < htmlLength-1) {
						if (moveSettings.left[k].childNodes) {
							for (var val in moveSettings.left[k].childNodes) {
								var childDiv = document.getElementById(val + objThis.settings.numOfGallery + "_" + k);
								var childElement = childDiv.childNodes[moveSettings.left[k].childNodes[val].childNum];
								increasePosition(childElement, moveSettings.left[k].childNodes[val]);
							}
						}
					
						increasePosition(objThis.htmlNow[k], moveSettings.left[k]);
					}
					else {
						if (moveSettings.left[k].childNodes) {
							for (var val in moveSettings.left[k].childNodes) {
								var childDiv = document.getElementById(val + objThis.settings.numOfGallery + "_" + k);
								var childElement = childDiv.childNodes[moveSettings.left[k].childNodes[val].childNum];
								increasePosition(childElement, moveSettings.left[k].childNodes[val]);
							}
						}
						
						increasePosition(objThis.htmlNow[k], moveSettings.left[k], function(){
							if  (objThis.i_id0 == dataLength-1) objThis.i_id0 = 0;
							else objThis.i_id0++;

							if  (objThis.i_idL == dataLength-1) objThis.i_idL = 0;
							else objThis.i_idL++;

							for (var i=0; i<=htmlLength-1; i++) {
								if (i == htmlLength-1) {
									objThis.i_id[i] = objThis.getId(objThis.dataNow[objThis.i_idL].id);
								}
								else {
									objThis.i_id[i] = objThis.i_id[i+1];
								}
								
								objThis.setInfo(objThis.htmlNow[i], objThis.i_id[i], i);
							}
							
							objThis.setStyles();
							objThis.settings.moveTrigger = false;
						});
					}
				}
			}
		}
	}
}

//статичный метод
//изображения сменяют друг друга простым "появлением" - изменением прозрачности
//все картинки класса classImages должны быть:
//	position:absolute; top:..; left:..; z-index:1;
//	opacity:1; filter:alpha(opacity=100);
Gallery_p_harm.changeOpacity = function (classImages){
	if (typeof (classImages) == "string") classImages = document.getElementsByClassName(classImages);
	
	var length = classImages.length;
	
	if (length > 1) {
		for (var val in classImages) {
			if (classImages[val].tagName == "IMG"){
				if (val == 0) {
					classImages[val].style.opacity = "1";
					classImages[val].style.filter = "alpha(opacity=100)";
					classImages[val].style.zIndex = "2";
				}
				else {
					classImages[val].style.opacity = "0";
					classImages[val].style.filter = "alpha(opacity=0)";
					classImages[val].style.zIndex = "1";
				}
			}
		}

		var i_now = 0;
		var i_next = 1;
		
		var interval = setInterval(function(){
			classImages[i_next].style.zIndex = "2";
			increasePosition(classImages[i_next], {
				length_opacity : 1,
				step : 10,
				speedMoving : 100
			});
		
			classImages[i_now].style.zIndex = "1";
			increasePosition(classImages[i_now], {
				length_opacity : -1,
				step : 10,
				speedMoving : 100
			});
			
			i_now++;
			i_next++;
			
			if (i_now > length-1) i_now = 0;
			if (i_next > length-1) i_next = 0;
		}, 4000);
	}
}