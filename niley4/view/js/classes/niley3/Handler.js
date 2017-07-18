var Handler = {}
var HandlerArtif = {};

//в DOM-совместимых браузерах наши функции являются тривиальными обертками
//вокруг addEventListener() и removeEventListener()
if (document.addEventListener){
	Handler.add = function (element, eventType, handler){
		if (typeof eventType != "string"){
			alert("Handler: Ошибка регистрации события! " + eventType.toString());
			return;
		}
		// Событие вращения колесика для Mozilla
		if (eventType == 'mousewheel') {
			element.addEventListener('DOMMouseScroll', handler, false);
		}
		element.addEventListener(eventType, handler, false);
	}
	Handler.remove = function (element, eventType, handler) {
		if (typeof eventType != "string"){
			alert("Handler: Ошибка удаления события! " + eventType.toString());
			return;
		}
		// Событие вращения колесика для Mozilla 
		if (eventType == 'mousewheel') { 
			element.removeEventListener('DOMMouseScroll', handler, false); 
		} 
		element.removeEventListener(eventType, handler, false);
	}
}

//в IE5 и более поздних версиях используются attachEvent(), detachEvent()
//с применением некоторых приемов, делающих их совместимыми с 
//addEventListener(), removeEventListener()
else if (document.attachEvent) {
	Handler.add = function (element, eventType, handler) {

		if (typeof eventType != "string"){
			alert("Handler: Ошибка регистрации события! " + eventType.toString());
			return;
		}

		//не допускать повторную регистрацию обработчика
		//_find() - частная вспомогательная функция определена далее
		if (Handler._find(element, eventType, handler) != -1) {
			return;
		}

		//функция для того, чтобы вызывать функцию как метод элемента.
		//Эта же функция регистрируется вместо фактического обработчика событий
		var wrappedHandler = function(e) {
			if (!e) e = window.event;
			
			//создать искусственный объект события, отчасти совместимый со стандартом DOM
			var event = {
				_event: e, //если потребуется настоящий объект события IE
				type: e.type,//тип события
				target: e.srcElement,//где возникло событие
				currentTarget: element,//где обрабатывается
				relatedTarget: e.fromElement ? e.fromElement : e.toElement,
				eventPhase: (e.srcElement == element) ? 2 : 3,
				
				//координаты указателя мыши
				clientX: e.clientX, 
				clientY: e.clientY,
				screenX: e.screenX,
				screenY: e.screenY,
				
				//состояния клавиш
				altKey: e.altKey,
				ctrlKey: e.ctrlKey,
				shiftKey: e.shiftKey,
				charCode: e.keyCode,
				
				//функции управления событиями
				stopPropagation: function() {
					this._event.cancelBubble = true;
				},
				preventDefault: function() {
					this._event.returnValue = false;
				}
			}
			
			//вызвать функцию-обработчик как метод элемента, передать
			//искусственный объект события как едиственный аргумент.
			//Если функция Function.call(), определена - использовать ее.
			//иначе применить маленький трюк
			if (Function.prototype.call) {
				handler.call (element, event);
			}
			else {
				//если функция Function.call остутствует, стимулировать ее вызов
				element._currentHandler = handler;
				element._currentHandler(event);
				element._currentHandler = null;
			}
		}
		
		//зарегистрировать вложенную функцию как обработчик события
		element.attachEvent("on" + eventType, wrappedHandler);

		//теперь необходимо сохранить информацию о пользовательской функции-обработчике
		//и вложенной функции, которая вызывает этот обработчик. Делается это для того, 
		//чтобы можно было отменить регистрацию обработчика методом remove() или автоматически
		//при выгрузке страницы
		
		//сохранить всю инфу об обработчике в объекте
		var h = {
			element: element,
			eventType: eventType,
			handler: handler,
			wrappedHandler: wrappedHandler
		}
		
		//Определить документ, частью которого является обработчик. Если элемент
		//не имеет свойства document, это не окно и не элемент документа.
		//следовательно - это должен быть сам объект document
		var d = document || element.document || element;
		
		//Window
		var w = d.parentWindow;
		//var w = d;
		
		//необходимо связать этот обработчик с окном, чтобы можно было удалить его при
		//выгрузке окна
		var id = Handler._uid(); //сгенерировать уникальное имя свойства
		if (!w._allHandlers) w._allHandlers = {} //создать объект, если необходимо
		w._allHandlers[id] = h; //сохранить обработчик в этом объекте
		
		//и связать инедтификатор информации об обработчике с этим элементом
		if (!element._handlers) element._handlers = [];
		element._handlers.push (id);
		
		//если связанный с окном обработчик события onunload еще не зарегистрирован,
		//зарегистрировать его.
		if (!w._onunloadHandlerRegistered) {
			w._onunloadHandlerRegistered = true;
			w.attachEvent ("onunload", Handler._removeAllHandlers);
		}
	}

	Handler.remove = function (element, eventType, handler) {
		if (typeof eventType != "string"){
			alert("Handler: Ошибка удаления события! " + eventType.toString());
			return;
		}
	
		//отыскать заданный обработчик в массиве element._handlers[]
		var i = Handler._find (element, eventType, handler);
		if (i == -1) return;
		
		//получить ссылку на окно для данного элемента
		var d = document || element.document || element;
		var w = d.parentWindow;
		//var w = d;
		
		//отыскать удаленный идентификатор обработчика
		var handlerId = element._handlers[i];
		//и использовать его для поиска информации об обработчике
		var h = w._allHandlers[handlerId];
		// используя эту информацию, отключить обработчик от элемента
		element.detachEvent("on" + eventType, h.wrappedHandler);
		//удалить один элемент из массива element._handlers
		element._handlers.splice(i, 1);
		//и удалить информацию об обработчике из объекта _allHandlers
		delete w._allHandlers[handlerId];
	}
	
	//вспомагательная функция поиска обработчика в массиве element._handlers
	//возвращает индекс в массиве или -1, если требуемый обработчик не найден
	Handler._find = function (element, eventType, handler) {
		var handlers = element._handlers;

		if (!handlers) 
			return -1;

		//получить ссылку на окно данного элемента
		var d = document || element.document || element;
		var w = d.parentWindow;
		//var w = d;

		//обойти в цикле обработчики, связанные с этим элементом, отыскать обработчик
		//с требуемыми типом и функцией. Обход идет в обратном порядке,
		//потому что отмена регистрации обработчиков, скорее всего,
		//будет выполняться в порядке, обратном их регистрации
		for (var i = handlers.length-1; i >= 0; i--) {
			var handlerId = handlers[i]; //получить идентификатор
			if (w._allHandlers) {//добавил 5.03.13
				var h = w._allHandlers[handlerId]; //получить информацию

				//если тип события и функция совпадают, значит, требуемый обработчик найден
				if (h.eventType == eventType && h.handler == handler) {
					return i;
				}
			}
		}
		return -1; //совпадений не найдено
	}
	
	//
	Handler._removeAllHandlers = function() {
		//данная функция регистрируется как обработчик событий onunload
		//с помощью attachEvent(). Это означает, что ключевое слово this
		//ссылается на объект Window, в котором возникло это событие
		var w = this;
		
		//обойти все зарегистрированные обработчики
		for (id in w._allHandlers) {
			//получить информацию об обработчике по идентификатору
			var h = w._allHandlers[id];
			//использовать ее для отключения обработчика
			h.element.detachEvent ("on" + h.eventType, h.wrappedHandler);
			//удалить информацию из объекта window
			delete w._allHandlers[id];
		}
	}
	
	//частная вспомогательная функция для генерации уникальных идентификаторов
	Handler._counter = 0;
	Handler._uid = function() {
		return "h" + Handler._counter++;
	}
}

// Кроссбраузерная функция подавления события 
function cancelEvent(e) {
	e = e ? e : window.event; 
	if (e.stopPropagation) {
		e.stopPropagation();
	}
	if (e.preventDefault) {
		e.preventDefault();
	}
	e.cancelBubble = true;
	e.cancel = true;
	e.returnValue = false;
	return false;
}


//регистрирует обработчик события ondataavialable в заданном элементе
HandlerArtif.add = function(element, artif_event, handler) {
	if (typeof artif_event != "string"){
		alert("Ошибка регистрации события! " + artif_event.toString());
		return;
	}
	
	if (element.addEventListener) {
		element.addEventListener(artif_event, handler, false);
	}
	else if (element.attachEvent) {
		element.attachEvent ("on" + artif_event, handler);
	}
}


//отправляет искусственное событие ondataavialable заданному элементу
// Объект события включает в себя свойства с именами datatype, data,
// которым присваиватся заданные значения. Свойство datatype  принимает
// значение строки или другого элементарного типа (или Null),
// идентифицирующего тип этого сообщения, а data может принимать значение любого
// javascript-типа, включая массивы и объекты

//element - элемент
//artif_event - искусственное событие
HandlerArtif.send = function (element, artif_event, datatype, data) {
	//Создать объект события. Если создать невозможно, просто вернуть управление.
	if (document.createEvent) { //DOM
		//создать событие с заданным именем модуля событий
		//для событий мыши используется MouseEvent
		var e = document.createEvent("Events");
		
		//инициировать объект события, используя метод init заданного модуля. Здесь указывается
		// тип события, способность к всплытию и признак невозможности отмены.
		e.initEvent (artif_event, true, false);
	}
	else if (document.createEventObject) { //IE
		//достаточно вызвать простой метод
		var e = document.createEventObject();
	}
	else 
		return;//в других браузерах ничего не делать
	
	//здесь к объекту события добавляются нестандартные свойства
	// кроме того, необходимо определить значения существующих свойств.
	e.datatype = datatype;
	e.data = data;
	
	//отправить событие заданному элементу
	if (element.dispatchEvent)
		element.dispatchEvent (e); //DOM
	else if (element.fireEvent)
		element.fireEvent ("ondataavialable", e); //IE
}


HandlerArtif.remove = function (element, eventType, handler) {
	if (document.addEventListener){
		if (typeof eventType != "string"){
			alert("Handler: Ошибка удаления события! " + eventType.toString());
			return;
		}
		element.removeEventListener(eventType, handler, false);
	}
	else if (document.attachEvent) {
		/*не доработано*/
	}
}