// ==ClosureCompiler==
// @output_file_name default.js
// @compilation_level SIMPLE_OPTIMIZATIONS
// ==/ClosureCompiler==
//2014-07-16 - Drag.setTransformAttrs

var Drag = new Object(); //для SVG-объекта


//вернуть список стилей
//возвращает набор установленных стилей только для чтения (из <style>)
Drag.getStyles = function (element) {
	if (window.getComputedStyle)
		var styles = window.getComputedStyle(element, null);
	else if (element.currentStyle)
		var styles = element.currentStyle;
	else 
		var styles = null;
	return styles;
}


//пересчет координат в зависимости от коэффициентов
//centerArray - координаты центра ориентируемого объекта
//k - коэффициент масштабирования
Drag.recalculate = function (x, y, divider, centerArray, k) {
	var coords = [];
	//перемещение точек
	if (k != undefined && centerArray != undefined) {
		coords[0] = (x - centerArray[0]) / k;
		coords[0] += parseInt (centerArray[0]);
		coords[1] = (y - centerArray[1]) / k;
		coords[1] += parseInt (centerArray[1]);
	}
	//установка точек
	else {
		coords[0] = (x - Screen.staticScreen.CanvasCenterX) / Screen.screenObject.screenK;
		coords[0] += Screen.staticScreen.CanvasCenterX;
		coords[1] = (y - Screen.staticScreen.CanvasCenterY) / Screen.screenObject.screenK;
		coords[1] += Screen.staticScreen.CanvasCenterY;
	}

	if (divider) {
		coords[0] /= divider;
		coords[1] /= divider;
		coords[0] = Math.round(coords[0]);
		coords[1] = Math.round(coords[1]);
		coords[0] *= divider;
		coords[1] *= divider;
	}
	else {
		coords[0] = Math.round(coords[0]);
		coords[1] = Math.round(coords[1]);
	}
	return coords;
}


//простое округление и приведение к сетке числа divider без учета коэффициентов
Drag.round = function (x, y, divider) {
	var coords = [];
	x /= divider;
	y /= divider;
	x = Math.round(x);
	y = Math.round(y);
	x *= divider;
	y *= divider;
	coords[0] = x;
	coords[1] = y;
	return coords;
}


//возвращает угол поворота (для svg - если использовалась rotate)
Drag.getAngle = function (element) {
	//вычислим угол, с которого начинается вращение
	var transform = element.getAttribute ("transform");
	var startAngle = 0;
	if (transform != null && transform != "") {
		var angle = transform.split ("rotate(");
		startAngle = parseInt(angle[1]);
	}
	return startAngle;
}


//возвращает угол в градусах
Drag.getAngle = function (startX, startY, centerX, centerY, endX, endY) {
	//1. рассчитаем треугольник Центр - Начало движения мыши - ось Х
	//гипотенуза треугольника
	var a = Math.sqrt (Math.pow ((startX - centerX), 2) + Math.pow ((startY - centerY), 2));

	//2. вычисляем угол наклона перемещения мыши к оси X (угол альфа)
	//b - длина катета, лежащего на оси X (а - длина гипотенузы)
	var b = Math.abs (startX - centerX);
	var alpha = Drag.getGrad (Math.acos (b / a));
	
	//3. рассчитываем треугольник Центр - конец движения мыши
	var k = Math.sqrt (Math.pow ((endX - centerX), 2) + Math.pow ((endY - centerY), 2));
	
	//4. вычисляем угол beta
	//m - длина катета, лежащего на оси X (k - длина гипотенузы)
	var m = Math.abs (endX - centerX);
	var beta = Drag.getGrad (Math.acos (m / k));
	
	
	
	//4. полный угол alpha рассчитан в пределах от 0 до 90
	if (startX > centerX) {
		//квадрант 1
		if (startY < centerY) {
		}
		//квадрант 4
		else if (startY > centerY) {
			alpha = 360 - alpha;
		}
		//между 1 и 4
		else if (startY == centerY) {
			alpha = 0;
		}
	}
	else if (startX < centerX) {
		//квадрант 2
		if (startY < centerY) {
			alpha = 180 - alpha;
		}
		//квадрант 3
		else if (startY > centerY) {
			alpha += 180;
		}
		//между 2 и 3
		else if (startY == centerY) {
			alpha = 180;
		}
	}
	else if (startX == centerX) {
		//между 1 и 2
		if (startY < centerY) {
			alpha = 90;
		}
		//между 3 и 4
		else if (startY > centerY) {
			alpha = 270;
		}
	}
	
	
	//5. полный угол beta
	if (endX > centerX) {
		//квадрант 1
		if (endY < centerY) {
		}
		//квадрант 4
		else if (endY > centerY) {
			beta = 360 - beta;
		}
		//между 1 и 4
		else if (endY == centerY) {
			beta = 0;
		}
	}
	else if (endX < centerX) {
		//квадрант 2
		if (endY < centerY) {
			beta = 180 - beta;
		}
		//квадрант 3
		else if (endY > centerY) {
			beta += 180;
		}
		//между 2 и 3
		else if (endY == centerY) {
			beta = 180;
		}
	}
	else if (endX == centerX) {
		//между 1 и 2
		if (endY < centerY) {
			beta = 90;
		}
		//между 3 и 4
		else if (endY > centerY) {
			beta = 270;
		}
	}

	//5. Посчитаем разницу углов. Причем движение против часовой - положительное
	var deltaAngle = alpha - beta;
	return deltaAngle;
}


//возвращает атрибуты атрибута transform - translate и rotate
Drag.getTransformAttrs = function (element) {
	//определить назначенное перемещение translate
	var transform = element.getAttribute ("transform");
	if (transform != undefined) {
		//вытащить tx, ty смещения
		var transformFuncs = transform.split(")");
		
		var translate = transformFuncs[0].split("translate(");
		var translateAttrs = translate[1].split(",");

		var rotate = transformFuncs[1].split("rotate(");
		var rotateAttrs = rotate[1].split(",");
		
		var scale = transformFuncs[2].split("scale(");
		var scaleAttrs = scale[1].split(",");

		var returnArray = Array();
		returnArray[0] = parseInt (translateAttrs[0]); //translate(x
		returnArray[1] = parseInt (translateAttrs[1]); //translate( ,y)
		returnArray[2] = parseInt (rotateAttrs[0]);
		returnArray[3] = parseInt (rotateAttrs[1]);
		returnArray[4] = parseInt (rotateAttrs[2]);
		returnArray[5] = parseInt (scaleAttrs[0]);
		returnArray[6] = parseInt (scaleAttrs[1]);
		
		return returnArray;
	}
	else return false;
}


//устанавливает атрибуты атрибута transform
Drag.setTransformAttrs = function (element, x, y, angle, rotateX, rotateY, ie, kwidth, kheight) {
	if (x 		== undefined) x = 0;
	if (y 		== undefined) y = 0;
	if (angle 	== undefined) angle = 0;
	if (rotateX == undefined) rotateX = 0;
	if (rotateY == undefined) rotateY = 0;
	if (kwidth 	== undefined) kwidth = 1;
	if (kheight == undefined) kheight = 1;
	
	if (ie == undefined || ie == false) {
		element.setAttribute ("transform", "translate("+x+","+y+") rotate("+angle+","+rotateX+","+rotateY+") scale("+kwidth+","+kheight+")");
	}
	else {
		//здесь вращение только через центр
		element.style.rotation = angle;
	}
}


//вычисляет центр фигуры
Drag.getCenter = function (element) {
	var returnArray = Array();
	returnArray[0] = parseInt (element.getAttribute("x")) + parseInt (element.getAttribute("width")/2);
	returnArray[1] = parseInt (element.getAttribute("y")) + parseInt (element.getAttribute("height")/2);
	return returnArray;
}


//возвращает радианы 
Drag.getRadian = function (grad) {
	return (grad/180)*Math.PI;
}


//возвращает градусы
Drag.getGrad = function (rad) {
	return (rad*180)/Math.PI;
}


//перемещение элементов
//id - id элемента
//event - e
//handler - функция обратного вызова
//settings - обязательный объект координат (описание внизу)
Drag.drag = function (id, event, handler, settings) {
	if ( typeof id == "string" ){
		elementToDrag = document.getElementById (id);
	}
	else {
		alert("Drag.drag(): 1й параметр - указывайте id элемента, а не element");
	}

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
		// ! использование внешнего массива координат

		if (settings.left != "fixed") {
			if (settings.AbsLeft) {
				if (settings.AbsLeft >= (e.clientX - deltaX)) {
					elementToDrag.style.left = (e.clientX - deltaX) + "px";
				}
			}
			else 
				elementToDrag.style.left = (e.clientX - deltaX) + "px";
		}
		
		if (settings.top != "fixed") {
			if (settings.AbsTop != undefined && settings.AbsTopMin != undefined) {
				if (settings.AbsTop >= (e.clientY - deltaY) && settings.AbsTopMin < (e.clientY - deltaY))
					elementToDrag.style.top = (e.clientY - deltaY) + "px";
			}
			else if (settings.AbsTop != undefined){
				if (settings.AbsTop >= (e.clientY - deltaY))
					elementToDrag.style.top = (e.clientY - deltaY) + "px";
			}
			else if (settings.AbsTopMin != undefined){
				if (settings.AbsTopMin >= (e.clientY - deltaY))
					elementToDrag.style.top = (e.clientY - deltaY) + "px";
			}
			else 
				elementToDrag.style.top = (e.clientY - deltaY) + "px";
		}

		
		//значения для функции обратного вызова
		//запишем в объект значения элемента (координаты)
		if (settings != undefined) {
			//место, где сейчас находится ползунок
			settings.middleX = e.clientX - deltaX;
			settings.middleY = e.clientY - deltaY;
		}
		//функция обратного вызова
		if (handler != undefined) {
			//startX, startY
			//endX, endY
			//middleX, middleY
			handler (settings);
		}
		
		
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



//перемещение элементов SVG
//id - id элемента (может быть массивом id элементов - координаты вычисляются по первому элементу)
//event - e
//handler - функция обратного вызова
//settings - необязательный (желательный) объект координат - чтобы ограничить движение
//k - коэффициент масштабирования (общий) - необязательный
//divider - для сетки (перемещает объект дискретно)
//myUpHandler - функция обратного вызова для остановки действия
/*
возможные варианты внешнего массива координат
по top или по left может быть указано только ОДНО значение
var settings = {
	"top" : "fixed" //стопор по top
	"top" : 100 //перемещение элемента за одно касание мышью
	"topMin" : 0 //перемещение элемента в обратную сторону (0 - блокируется)
	"AbsTop" : 260 //насколько может быть удаление по top (абсолютное, от начального положения)
	"AbsTopMin" : 0 //удаление по top (минимальное, абсолютное, от начального положения)

	"left" : "fixed"//стопор по left
	"left" : 100 //перемещение элемента за одно касание мышью
	"leftMin" : 100 //перемещение элемента в обратную сторону
	"AbsLeft" : 340 //насколько может быть удаление по left (абсолютное, от начального положения)
	"AbsLeftMin" : 15 //удаление по left (минимальное, абсолютное, от начального положения)
}
*/
Drag.SVG_drag = function (id, event, handler, settings, k, divider, myUpHandler) {
	var length = 1;
	var elementToDrag = false;
	if ( typeof id == "string" ){
		elementToDrag = document.getElementById (id);
	}
	else if (typeof id == "object") {
		//если массив
		length = id.length;
		elementToDrag = document.getElementById (id[0]);
	}
	else {
		alert("Drag.SVG_drag(): 1й параметр - указывайте id элемента, а не element");
	}
	if (k == undefined) k = 1;
	if (divider == undefined) divider = 1;
	

	//координаты прямоугольника и круга различают по названиям
	var coordXName = "x";
	if (settings.coordXName) {
		coordXName = settings.coordXName
	}
	var coordYName = "y";
	if (settings.coordYName) {
		coordYName = settings.coordYName
	}

	//координаты мыши (в оконных координатах)
	//в точке, откуда начинается перемещение элемента
	var startX = event.clientX;
	var startY = event.clientY;

	//начальная позиция (в координатах документа) перетаскиваемого элемента.
	//Поскольку elementToDrag позиционируется в абсолютных координатах, предполагается, что его
	// свойство offsetParent ссылается на элемент body документа
	var origX = elementToDrag.getAttribute(coordXName);
	var origY = elementToDrag.getAttribute(coordYName);

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
		
		//пересчет координат - сетка и масштабирование
		var centerArray = [];
		centerArray[0] = startX;
		centerArray[1] = startY;
		
		var nowClientX = e.clientX;
		var nowClientY = e.clientY;
		
		//округление координат под сетку и пересчет в зависимости от коэффициентов
		var coords = Drag.recalculate (nowClientX, nowClientY, divider, centerArray, k);
		var EclientX = coords[0];
		var EclientY = coords[1];

		//округление дельты под сетку
		var delta = Drag.round (deltaX, deltaY, divider);
		deltaX = delta[0];
		deltaY = delta[1];

		//если добавил массив id элемента, тогда прогонится каждый
		for (var i=0; i<length; i++) {
			if (length > 1) {
				elementToDrag = document.getElementById (id[i]);
			}
			
			//переместить элемент в текущие координаты указателя мыши, при необходимости 
			// подстроить его позицию на смещение начального щелчка
			// ! использование внешнего массива координат
			if (settings.left != "fixed") {
				if (settings.AbsLeft != undefined && settings.AbsLeftMin != undefined) {
					if (settings.AbsLeft >= (EclientX - deltaX) && settings.AbsLeftMin < (EclientX - deltaX)) {
						elementToDrag.setAttribute(coordXName, (EclientX - deltaX));
					}
				}
				else if (settings.AbsLeft != undefined){
					if (settings.AbsLeft >= (EclientX - deltaX))
						elementToDrag.setAttribute(coordXName, (EclientX - deltaX));
				}
				else if (settings.AbsLeftMin != undefined){
					if (settings.AbsLeftMin >= (EclientX - deltaX))
						elementToDrag.setAttribute(coordXName, (EclientX - deltaX));
				}
				else {
					elementToDrag.setAttribute(coordXName, (EclientX - deltaX));
				}
			}

			if (settings.top != "fixed") {
				if (settings.AbsTop != undefined && settings.AbsTopMin != undefined) {
					if (settings.AbsTop >= (EclientY - deltaY) && settings.AbsTopMin < (EclientY - deltaY))
						elementToDrag.setAttribute(coordYName, (EclientY - deltaY));
				}
				else if (settings.AbsTop != undefined){
					if (settings.AbsTop >= (EclientY - deltaY))
						elementToDrag.setAttribute(coordYName, (EclientY - deltaY));
				}
				else if (settings.AbsTopMin != undefined){
					if (settings.AbsTopMin >= (EclientY - deltaY))
						elementToDrag.setAttribute(coordYName, (EclientY - deltaY));
				}
				else 
					elementToDrag.setAttribute(coordYName, (EclientY - deltaY));
			}

			
			//значения для функции обратного вызова
			//запишем в объект значения элемента (координаты)
			if (settings != undefined) {
				//место, где сейчас находится ползунок
				settings.middleX = EclientX - deltaX;
				settings.middleY = EclientY - deltaY;
			}
			//функция обратного вызова
			if (handler != undefined) {
				//startX, startY
				//endX, endY
				//middleX, middleY
				settings.e = e;
				handler (settings);
			}
			
			//и прервать дальнейшее распространение события
			if (e.stopPropagation)
				e.stopPropagation(); //DOM Level 2
			else
				e.cancelBubble = true; //IE
		}
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
		
		//функция обратного вызова
		if (myUpHandler != undefined) {
			myUpHandler (e);
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


//перемещение элементов SVG через свойство translate
//id - id элемента (может быть массивом id элементов - координаты вычисляются по первому элементу)
//event - e
//handler - функция обратного вызова
//settings - необязательный (желательный) объект координат - функция фигуры, по которой будет движение
	//settings.functionLine - функция направляющей линии
	//settings.coords - функция координат перемещения в плоскости (возвращает объект)
	//settings.lineArray - массив направляющих линий
	//settings.lineNum - номер направляющей линии в массиве линий
	//settings.lineName - имя направляющей линии в id линии (например, id = line23, так вот name будет "line"
//k - коэффициент масштабирования (общий) - необязательный
//divider - для сетки (перемещает объект дискретно)
//myUpHandler - функция обратного вызова для остановки действия
Drag.SVG_translate = function (id, event, handler, settings, k, divider, myUpHandler) {
	//исходные данные
	//смещение [:translate] относительно декартовых координат [:x,y] в нашей системе из двух разных систем координат
	var deltaCoordsVsTranslateX = 300;
	var deltaCoordsVsTranslateY = 0;

	var length = 1;
	var elementToDrag = false;
	if ( typeof id == "string" ){
		elementToDrag = document.getElementById (id);
	}
	else if (typeof id == "object") {
		//если массив
		length = id.length;
		elementToDrag = document.getElementById (id[0]);
	}
	else {
		alert("Drag.SVG_translate(): 1й параметр - указывайте id элемента, а не element");
	}
	if (k == undefined) k = 1;
	if (divider == undefined) divider = 1;


	//координаты мыши (в оконных координатах)
	//в точке, откуда начинается перемещение элемента
	var startX = event.clientX - deltaCoordsVsTranslateX;
	var startY = event.clientY - deltaCoordsVsTranslateY;

	//начальная позиция (в координатах transform) перетаскиваемого элемента.
	//Поскольку elementToDrag позиционируется в абсолютных координатах, предполагается, что его
	// свойство offsetParent ссылается на элемент body документа
	//получим уже установленные параметры transform
	var tAttrs = Drag.getTransformAttrs (elementToDrag);
	var origX = tAttrs[0];
	var origY = tAttrs[1];

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

	
	//var trigger = false;
	//перехватывает события Mousemove в процессе перетаскивания элемента.
	//Отвечает за перемещение элемента
	function moveHandler (e) {
		if (!e) 
			e = window.event; //IE

		//пересчет координат - сетка и масштабирование
		var centerArray = [];
		centerArray[0] = startX;
		centerArray[1] = startY;
		
		var nowClientX = e.clientX - deltaCoordsVsTranslateX;
		var nowClientY = e.clientY - deltaCoordsVsTranslateY;

		//округление координат под сетку и пересчет в зависимости от коэффициентов
		//используются только для построения, без учета формулы направляющей линии
		var coords = Drag.recalculate (nowClientX, nowClientY, divider, centerArray, k);

		//округление дельты под сетку
		//используются только для построения, без учета формулы направляющей линии
		var delta = Drag.round (deltaX, deltaY, divider);

		//если добавил массив id элемента, тогда прогонится каждый
		for (var i=0; i<length; i++) {
			if (length > 1) {
				elementToDrag = document.getElementById (id[i]);
			}

			//получим уже установленные параметры transform
			var tAttrs = Drag.getTransformAttrs (elementToDrag);

			//если есть формула направляющей линии, двигаем объект по данной направляющей
			if (settings != undefined && settings.functionLine != undefined) {
			
				if (settings.width == undefined || settings.height == undefined) {
					alert("Укажите settings.width, settings.height для перемещения модели без deltaX, deltaY");
				}
			
				var line = settings.lineArray[settings.lineNum];
				var result = settings.functionLine (line, 1, e);

				//просто пример
				if (result != undefined) {
					tAttrs[0] = result.x - (settings.width/2) - deltaCoordsVsTranslateX;
					tAttrs[1] = result.y - (settings.height/2) - deltaCoordsVsTranslateY;
				}
				else {
					//tAttrs[0] = coords[0] - delta[0];
					//tAttrs[1] = coords[1] - delta[1];
				}
			}
			//если нет такой направляющей
			else {
				tAttrs[0] = coords[0] - delta[0];
				tAttrs[1] = coords[1] - delta[1];
			}

			Drag.setTransformAttrs (elementToDrag, tAttrs[0], tAttrs[1], tAttrs[2], tAttrs[3], tAttrs[4]);

			//функция обратного вызова
			if (handler != undefined) {
				e.resultX = tAttrs[0];
				e.resultY = tAttrs[1];
				handler (e);
			}
			
			//и прервать дальнейшее распространение события
			if (e.stopPropagation)
				e.stopPropagation(); //DOM Level 2
			else
				e.cancelBubble = true; //IE
		}
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
		
		//функция обратного вызова
		if (myUpHandler != undefined) {
			myUpHandler (e);
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