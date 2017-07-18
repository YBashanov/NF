/* 
2014-04-28
Галерея Линия - просмотр нескольких картинок, смена картинок нажатием на стрелки и прокруткой колесом мыши

обработчик вешается на number (без проверки информации), информация подгружается сразу, без ajax
Прокрутка происходит по одному элементу

Необходимые классы:
- Handler
- Universal

var param1 = {
	"numOfGallery"	: "1",				//[умолч. = 1] номер галереи на странице (на листе может быть несколько галерей одновременно)
	"htmlClassName"	: "photomini",		//класс html-шаблона. Сюда будет литься инфа
	"dataClassName"	: "i_banner",		//скрытая верстка. Полная верстка, связанная с БД. Отсюда берется инфа
	"action"		: "",				//показывает, как работать с html-шаблоном.
		simple	//[умолч.] самый простой, когда htmlClassName - те div-ы, которые непосредственно будут наполняться инфой
		common	//когда блок htmlClassName является общим для остальных, подчиненных div-ов, сделанных для наполнения инфой
			тут включается функционал доп. прогона (поиска) элементов 2го уровня вложенности
	"stylesCallback": stylesCallback	//внешняя функция обратного вызова, в которой мы выставляем параметры стилей элементов
	"moveSettings" 	: moveSettings		//внешние установки, с помощью которых мы выставляем параметры перемещения элементов
	"arrows"		: true				//если true, то загружает обработчики для стрелок влево-вправо:
		id='arrow_left{GALLERY_NUM_1}'
		id='arrow_right{GALLERY_NUM_1}'
};

в html-шаблоне необходимо организовать 2 типа верстки:
1. информационная
	выводится вся информация целиком, простая скрытая верстка
2. визуализационная
	только несколько "качелей", которые наполняются инфой из информационного массива
*/
var Gallery_p = function() {}

Gallery_p.prototype = {
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
			
			if (!this.ie && element == length) {break;} //Opera Bug - сюда ставим количество блоков

			if (element.tagName == "DIV") {
				number = i;
				i++;
				this.setInfo(element, this.i_id[number], number);
				this.setListeners (element, number);
			}
		}

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
			i_picture = document.getElementById("i_picture"+this.settings.numOfGallery+"_"+i_id).innerHTML;
			i_text = document.getElementById("i_text"+this.settings.numOfGallery+"_"+i_id).innerHTML;
			
			i_picture = i_picture.replace(/(^\s+|\s+$)/g, '');
			
			document.getElementById("picture"+this.settings.numOfGallery+"_"+number).innerHTML = i_picture;
			//if (number == 2) document.getElementById("text"+number).innerHTML = i_text;
		}
	},
	
	//как сделать так, чтобы стили не зависели от количества блоков?
	//этот метод должен быть выносным
	//вызывается так: ОБЪЕКТ.setStyles(function(htmlNow, ie){}
	setStyles : function() {
		var htmlNow = this.htmlNow;
		var ie = this.ie;

		var callback = this.settings.stylesCallback;
		if (callback == undefined) { alert("Gallery_p.setStyles: Ошибка. Нет настроек styles для элементов.\nГалерея №"+this.settings.numOfGallery);}
		else {
			callback(htmlNow, ie);
			this.settings.moveTrigger = false;
		}
	},
	
	setListeners : function (element, number){
		var objThis = this;

		Handler.add(element, "click", function(e){
			//переход куда-то
		});

		Handler.add(element, "mousewheel", function(e){
			e = e ? e : window.event; 
			// Получить элемент, на котором произошло событие 
			var wheelElem = e.target ? e.target : e.srcElement;
			// Получить значение поворота колесика мыши 
			var wheelData = e.detail ? e.detail * -1 : e.wheelDelta / 40;
			// В движке WebKit возвращается значение в 100 раз больше 
			if (Math.abs(wheelData)>100) { wheelData=Math.round(wheelData/100); }

			// Теперь в переменной wheelElem содержится элемент, который получил 
			// собщение от колесика мыши, а в wheelData значение поворота колеса 
			Gallery_p.prototype.moveGallery(wheelData, objThis);
			
			// Отменить штатные действия браузера при кручении колеса мыши 
			return cancelEvent(e);
		});
	},
	
	
	setListenersArrows : function() {
		var objThis = this;
		
		var arrowLeft = document.getElementById("arrow_left"+this.settings.numOfGallery);
		var arrowRight= document.getElementById("arrow_right"+this.settings.numOfGallery);
		
		Handler.add(arrowLeft, "click", function(e){
			Gallery_p.prototype.moveGallery(1, objThis);
		});
		
		Handler.add(arrowRight, "click", function(e){
			Gallery_p.prototype.moveGallery(-1, objThis);
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
						increasePosition(objThis.htmlNow[k], moveSettings.right[k]);
					}
					else {
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
						increasePosition(objThis.htmlNow[k], moveSettings.left[k]);
					}
					else {
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