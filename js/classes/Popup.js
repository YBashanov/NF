var Popup = new Object();

//blocked[div] = true - окно отображается, false - скрыто
Popup.blocked = new Array();
//общий индекс всех октрытых окон
Popup.zIndex = 100;

//последний вызванный элемент (для IE)
Popup.elements = new Array(); //содержит массив object-ов (element)
Popup.elementZIndexes = new Array(); //содержит массив zIndex-открытых элементов (совпадает с Popup.elements) (вспомогат.)
Popup.elementId = new Array(); //содержит масив всех id - для того, чтобы при закрытии элемента вернуть предпоследний эл.
Popup.lastElement = null;//чтобы предотвратить постоянный вызов через цикл - последний element
Popup.lastid = null;//просто id - только для того, чтобы проверять, этот ли элемент был только что вызван или нет


//вспомогательная - подсветка элементов
function alertElements() {
	//проверяем, есть ли вообще хоть один элемент в массиве
	var str = "Массив элементов: элемент, индекс, Id\n\n";
	for (var i=100; i<Popup.elements.length; i++) {
		str += Popup.elements[i] + " - " + Popup.elementZIndexes[i] + " - " + Popup.elementId[i] + "\n";
	}
	str += "\n";
	str += "LastElement - " + Popup.getLastElement() + "\n";
	str += "LastId - " + Popup.getLastId() + "\n";
	alert(str);
}
function lookzIndex() {
	alert(Popup.zIndex);
}







//возвращает последний "активный" элемент массива - последний активный элемент
Popup.getLastElement = function() {
	//проверяем, есть ли вообще хоть один элемент в массиве
	var lastElement;
	for (var i = Popup.zIndex; i>100; i--) {
		if (Popup.elements[i] != null) {
			lastElement = Popup.elements[i];
			break;
		}
	}
	return lastElement;
}
//возвращает последний "активный" элемент массива - последний активный ID элемента
Popup.getLastId = function() {
	//проверяем, есть ли вообще хоть один элемент в массиве
	var lastid;
	for (var i = Popup.zIndex; i>100; i--) {
		if (Popup.elementId[i] != null) {
			lastid = Popup.elementId[i];
			break;
		}
	}
	return lastid;
}
//вернуть список стилей
//возвращает набор установленных стилей только для чтения (из <style>)
Popup.getStyles = function (element) {
	if (window.getComputedStyle)
		var styles = window.getComputedStyle(element, null);
	else if (element.currentStyle)
		var styles = element.currentStyle;
	else 
		var styles = null;
	return styles;
}



//при загрузке нового div-a
//присвоение этому div-у определенный Z-index и свойство blocked[div]
//id - айдишник элемента
//sideScript- (для IE) если загрузка нового блока происходит из внешнего скрипта (с добавлением html-кода) true, false
//content   - (только IE) возвращенный из ajax элемент (целиком блок) 
//explodeId - (только IE) id элемента, в который вставим содержимое блока (куда вставить)
Popup.open = function (id, sideScript, content, explodeId) {
	if (! sideScript)
		sideScript = false;
	if (! content)
		content = false;
	if (! explodeId)
		explodeId = false;

	//if IE - гарантированная проверка
	if (document.attachEvent) {

		//если скрипт вызван из стороннего скрипта (добавление html к странице)
		if (sideScript) {
			//проверяем, есть ли этот блок у нас на странице
			var isIntoPage = false;
			for (var i=100; i < Popup.elementId.length; i++) {
				if (Popup.elementId[i] == id) {
					var isIntoPage = true;
					break;//есть уже такой элемент - ничего не делаем
				}
			}
			
			//нет такого элемента на странице
			if (isIntoPage == false) {
				explodeElement = document.getElementById (explodeId);
			
				Popup.zIndex++;
			
				//создаем дополнительный div-контейнер, где будет храниться наш контент (новый popupIE100)
				var newElement = document.createElement("div");
				newElement.setAttribute ("id", "popupIE"+Popup.zIndex);
				newElement.setAttribute ("className", "abs");
				explodeElement.appendChild (newElement);
				newElement.innerHTML = content;
				
				element = document.getElementById (id);
				var styles = Popup.getStyles (element);
	
				element.style.zIndex = styles.zIndex;
				element.style.zIndex = Popup.zIndex;
				element.style.display = styles.display;
				element.style.display = "block";
				Popup.blocked[id] = true;
				
				//запоминаем этот элемент последним в наших информационных массивах
				Popup.elements[Popup.zIndex] = element;
				Popup.elementZIndexes[Popup.zIndex] = Popup.zIndex;
				Popup.elementId[Popup.zIndex] = id;
			}
			//такой элемент уже есть на странице - элемент всего лишь получает фокус
			else {
				Popup.focus (id);
			}
		}
		//если скрипт вызван из focus (просто смена очередности элементов)
		else {
			element = document.getElementById (id);
			var styles = Popup.getStyles (element);
			
			Popup.zIndex++;
			element.style.zIndex = styles.zIndex;
			element.style.zIndex = Popup.zIndex;

			Popup.lastElement = Popup.getLastElement();

			//меняем местами div-ы (начальные - popupIE100)
			var parentElement_this = element.parentNode;
			var parentElement_last = Popup.lastElement.parentNode;
			//корневой элемент
			var rootElement = parentElement_this.parentNode;
			
			//удаляем из root
			rootElement.removeChild(parentElement_this);
			rootElement.removeChild(parentElement_last);
			//добавляем, но с измененными местами
			rootElement.appendChild(parentElement_last);
			rootElement.appendChild(parentElement_this);

			//запоминаем этот элемент последним
			Popup.elements[Popup.zIndex] = element;
			Popup.elementZIndexes[Popup.zIndex] = Popup.zIndex;
			Popup.elementId[Popup.zIndex] = id;
		}
	}
	//Firefox
	else {
		if ( typeof id == "string" )
			element = document.getElementById (id);
		else 
			element = id;

		//получаем стили элемента
		var styles = Popup.getStyles (element);
	
		Popup.zIndex++;
		element.style.zIndex = styles.zIndex;
		element.style.zIndex = Popup.zIndex;
		element.style.display = styles.display;
		element.style.display = "block";
		Popup.blocked[id] = true;
	}
	Popup.lastid = id;
}

//открытие-закрытие div-a (скрываем - отображаем)
Popup.openClose = function (id) {
	if ( typeof id == "string" )
		element = document.getElementById (id);
	else 
		element = id;

	//получаем стили элемента
	var styles = Popup.getStyles (element);

	//открыть окно снова (обычно после закрытия)
	if ( Popup.blocked[id] == false ) {
		Popup.open (id, true);
	}
	//закрыть окно
	else if ( Popup.blocked[id] == true ) {
		element.style.display = styles.display;
		element.style.display = "none";
		Popup.blocked[id] = false;

		//if IE
		//при закрытии надо уничтожать последний элемент в массиве, т.к. иначе у следующего элемента не
		// будет привязки к предыдущему (рисовал на листе схему)
		if (element.currentStyle) {
			//ищем все элементы, записанные ранее с данным id (обычно их не больше 2х, т.к. также идет контроль на focus-e)
			for (var i = 100; i<Popup.elementId.length; i++) {
				//если такой элемент есть - стираем его
				if (Popup.elementId[i] != null && Popup.elementId[i] == id) {
					Popup.elementId[i] = undefined;
					Popup.elements[i] = undefined;
					Popup.elementZIndexes[i] = undefined;
					
					//для IE то, что элемент будет невидимым - этого мало
					var parentElement_new = element.parentNode;
					parentElement_new.removeChild(element);
				}
			}
			Popup.lastElement = Popup.getLastElement();		//обнуляем, чтобы можно было создать тот же самый элемент
			Popup.lastid = Popup.getLastId();//обнуляем, чтобы можно было создать тот же самый элемент
		}
	}
}

//делаем это окно "активным" (выставляем на передний план)
//то есть открываем его заново, с новым zIndex (если оно не было в фокусе)
Popup.focus = function (id) {
	if (id != Popup.lastid) {
		//if IE
		//тут должна происходить очистка массива от аналогичного значения, записанного ранее
		//очищаем всего 1 элемент
		if (element.currentStyle) {
			//ищем тот же элемент, записаный ранее 
			for (var i = 100; i<Popup.elementId.length; i++) {
				//если такой элемент есть - стираем его
				if (Popup.elementId[i] != null && Popup.elementId[i] == id) {
					Popup.elementId[i] = undefined;
					Popup.elements[i] = undefined;
					Popup.elementZIndexes[i] = undefined;
					break;
				}
			}
		}
		
		Popup.open (id);
	}
}


//перемещение элементов
Popup.drag = function (id, event) {	
	if ( typeof id == "string" )
		elementToDrag = document.getElementById (id);
	else
		elementToDrag = id;

	Popup.focus (id);
	
	//координаты мыши (в оконных координатах)
	//в точке, откуда начинается перемещение элемента
	var startX = event.clientX;
	var startY = event.clientY;

	//начальная позиция (в координатах документа) перетаскиваемого элемента.
	//Поскольку elementToDrag позиционируется в абсолютных координатах, предполагается, что его
	// свойство offsetParent ссылается на элемент body документа
	var origX = elementToDrag.offsetLeft;
	var origY = elementToDrag.offsetTop;
	
	//несмотря на то, что координаты исчисляются в разных системах, мы может вычислить 
	// разницу между ними и использовать ее в функции moveHandler()
	var deltaX = startX - origX;
	var deltaY = startY - origY;

	//зарегистрировать обработчики событий mousemove и mouseup
	// которые последуют вслед за событием mousedown
	if (document.addEventListener) {
		//DOM Level 2
		document.addEventListener("mousemove", moveHandler, true);
		document.addEventListener("mouseup", upHandler, true);
	}
	else if (document.attachEvent) {
		//IE 5+
		//в модели обработки IE перехват событий производится вызовом метода 
		// setCapture() элемента, выполняющего перехват
		elementToDrag.setCapture();
		elementToDrag.attachEvent("onmousemove", moveHandler);
		elementToDrag.attachEvent("onmouseup", upHandler);
		
		//интерпретировать событие потери перехвата как событие mouseup
		elementToDrag.attachEvent("onlosecapture", upHandler);
	}
	else {
		//IE 4
		//в IE 4 мы не можем использовать attachEvent(), setCapture()
		// поэтому вставляем обработчики событий непосредственно в объект документа
		// и уповаем на то, что требуемые события мыши всплывут
		var oldmovehandler = document.onmousemove;
		var olduphandler = document.onmouseup;
		document.onmousemove = moveHandler;
		document.onmouseup = upHandler;
	}
	
	//событие обработано, необходимо прервать его дальнейшее распространение
	if (event.stopPropagation) 
		event.stopPropagation();
	else 
		event.cancelBubble = true;
	
	//предотвращаем действие по умолчанию
	if (event.preventDefault) 
		event.preventDefault();
	else
		event.returnValue = false;
		
	//перехватывает события Mousemove в процессе перетаскивания элемента.
	//Отвечает за перемещение элемента
	function moveHandler (e) {
		if (!e) 
			e = window.event; //IE

		//переместить элемент в текущие координаты указателя мыши, при необходимости 
		// подстроить его позицию на смещение начального щелчка
		elementToDrag.style.left = (e.clientX - deltaX) + "px";
		elementToDrag.style.top = (e.clientY - deltaY) + "px";

		//и прервать дальнейшее распространение события
		if (e.stopPropagation) 
			e.stopPropagation(); //DOM Level 2
		else 
			e.cancelBubble = true; //IE
	}
	
	//перехватывает заключительное событие Mouseup,
	// которое возникает в конце операции перетаскивания
	function upHandler (e) {
		if (!e)
			e = window.event;

		
		//отменить регистрацию перехватывающих обработчиков
		if (document.removeEventListener) {
			document.removeEventListener("mouseup", upHandler, true);
			document.removeEventListener("mousemove", moveHandler, true);
		}
		else if (document.detachEvent) {
			elementToDrag.detachEvent("onlosecapture", upHandler);
			elementToDrag.detachEvent("onmouseup", upHandler);
			elementToDrag.detachEvent("onmousemove", moveHandler);
		}
		else {
			//восстановить первоначальные обработчики, если они были
			document.onmouseup = olduphandler;
			document.onmousemove = oldmovehandler;
		}
		
		//и прервать дальнейшее распространение события
		if (e.stopPropagation){
			e.stopPropagation();
		}
		else {
			e.cancelBubble = true;
			e.releaseCapture(); //необходимая функция, иначе IE зависнет
		}
	}
}