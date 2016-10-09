//удалить элемент
function removeElement (element) {
	if (typeof(element) == "string") element = document.getElementById(element);
	if (element) {
		element.parentNode.removeChild(element);
	}
}

//возвращает размер окна
function getWindowSize (){
	var widthWindow = parseInt(document.body.clientWidth);
	var heightWindow = parseInt(document.body.clientHeight);
	var array = [];
	array[0] = widthWindow;
	array[1] = heightWindow;

	return array;
}

//аналог PHP-функции strpos
function strpos(haystack, needle, offset){
    var i = haystack.indexOf( needle, offset );
    return i >= 0 ? i : false;
}

//вернуть список стилей
//возвращает набор установленных стилей только для чтения (из <style>)
function getStyles(element) {
	if (typeof(element) == "string") element = document.getElementById(element);
	
	if (window.getComputedStyle)
		var styles = window.getComputedStyle(element, null);
	else if (element.currentStyle)
		var styles = element.currentStyle;
	else 
		var styles = null;
	return styles;
}

//определение браузера (chrAfterPoint - сколько знаков выдавать) по умолчанию - все
function browserDetectNav(chrAfterPoint) {
var
    UA=window.navigator.userAgent,       // содержит переданный браузером юзерагент
    //--------------------------------------------------------------------------------
    OperaB = /Opera[ \/]+\w+\.\w+/i,     //
    OperaV = /Version[ \/]+\w+\.\w+/i,   //
    FirefoxB = /Firefox\/\w+\.\w+/i,     // шаблоны для распарсивания юзерагента
    ChromeB = /Chrome\/\w+\.\w+/i,       //
    SafariB = /Version\/\w+\.\w+/i,      //
    IEB = /MSIE *\d+\.\w+/i,             //
    SafariV = /Safari\/\w+\.\w+/i,       //
        //--------------------------------------------------------------------------------
    browser = new Array(),               //массив с данными о браузере
    browserSplit = /[ \/\.]/i,           //шаблон для разбивки данных о браузере из строки
    OperaV = UA.match(OperaV),
    Firefox = UA.match(FirefoxB),
    Chrome = UA.match(ChromeB),
    Safari = UA.match(SafariB),
    SafariV = UA.match(SafariV),
    IE = UA.match(IEB),
    Opera = UA.match(OperaB);
         
        //----- Opera ----
        if ((!Opera=="")&(!OperaV=="")) browser[0]=OperaV[0].replace(/Version/, "Opera")
                else
                    if (!Opera=="") browser[0]=Opera[0]
                        else
                            //----- IE -----
                            if (!IE=="") browser[0] = IE[0]
                                else
                                    //----- Firefox ----
                                    if (!Firefox=="") browser[0]=Firefox[0]
                                        else
                                            //----- Chrom ----
                                            if (!Chrome=="") browser[0] = Chrome[0]
                                                else
                                                    //----- Safari ----
                                                    if ((!Safari=="")&&(!SafariV=="")) browser[0] = Safari[0].replace("Version", "Safari");
//------------ Разбивка версии -----------
 
    var
            outputData;                                      // возвращаемый функцией массив значений
                                                             // [0] - имя браузера, [1] - целая часть версии
                                                             // [2] - дробная часть версии
    if (browser[0] != null) outputData = browser[0].split(browserSplit);
    if ((chrAfterPoint==null)&&(outputData != null))
        {
            chrAfterPoint=outputData[2].length;
            outputData[2] = outputData[2].substring(0, chrAfterPoint); // берем нужное ко-во знаков
            return(outputData);
        }
        else return(false);
}

//изменение размеров и координат объекта в зависимости от заданных установок
// + действие по окончании изменений

//settings - настройки процесса движения
//settings = {
//	length_top - длина по высоте, которую пройдет трансформация элемента
//	length_left
//	length_width
//	length_height
//	length_borderRadius 	(2014-06-16)
//	length_opacity			(2014-06-16)
//	length_clipwidth		(2014-07-03)
//	length_clipheight		(2014-07-03)
//	step : 10,
//	speedMoving : 100
//}

//var universal_timeinterval = false;
function increasePosition(element, settings, callback){
	if (element == "help") {
		var result = "";
		result += "--- (help) ---\n";
		result += "\n";
		result += "animate ( element, settings, callback );\n";
		result += "   \n";
		result += "Действие:\n";
		result += "   трансформирует javascript-элемент в окне браузера.\n";
		result += "   (перемещает, изменяет размеры, свойства)\n";
		result += "   \n";
		result += "Параметры:\n";
		result += "   - element - либо id элемента, либо javascript-элемент.\n";
		result += "   - settings - объект, параметры перемещения\n";
		result += "   - callback - [не обяз., по умолч.= false] функция,\n";
		result += "   будет вызвана по окончании Действия.\n";
		result += "   \n";
		result += "Параметры перемещения settings (пишутся без указания px),\n";
		result += "   не указанные параметры игнорируются:\n";
		result += "   - length_top - изменение top, px\n";
		result += "   - length_left - изменение left, px\n";
		result += "   - length_width - изменение ширины, px\n";
		result += "   - length_height - изменение высоты, px\n";
		result += "   - length_borderRadius - изменение окружного радиуса, px\n";
		result += "   - length_opacity - изменение прозрачности, число от 0.1 до 1\n";
		result += "   - length_clipwidth - изменение ширины clip, px\n";
		result += "   - length_clipheight - изменение высоты clip, px\n";
		result += "   - step - число шагов анимации, по умолч.= 12\n";
		result += "   - speedMoving - скорость каждого шага анимации, по умолч.=25\n";
		result += "   \n";
		result += "Примеры:\n";
		result += "   1. элемент уходит вниз на 200px, влево на 100px,\n";
		result += "   постепенно уменьшается его видимость до 50%\n";
		result += "   animate ( myElement, {\n";
		result += "      length_top : 200,\n";
		result += "      length_left : -100,\n";
		result += "      length_opacity : -0.5\n";
		result += "   });\n";
		result += "   \n";
		
		alert(result);
		return false;
	}


	if (typeof(element) == "string") element = document.getElementById(element);

	//scrollTo()
	
	var top = 0;
	var left = 0;
	var width = 0;
	var height = 0;
	var borderRadius = 0;
	var opacity = 1;
	var clipheight = 1;

	var dleft;
	var dtop;
	var dwidth;
	var dheight;
	var dborderRadius;
	var dopacity;
	var dclipwidth;
	var dclipheight;
	var step = 0;
	var speedMoving = 0;
	if (settings.step) step = settings.step;
	else step = 12;
	if (settings.speedMoving) speedMoving = settings.speedMoving;
	else speedMoving = 25;


	//длина пути по высоте и ширине
	var length_width	= 0;
	var length_height 	= 0;
	var length_left 	= 0;
	var length_top 		= 0;
	var length_borderRadius	= 0;
	var length_opacity = 0;
	var length_clipwidth = 0;
	var length_clipheight = 0;
	var cliprect1 = 0;
	var cliprect2 = 0;
	var cliprect3 = 0;
	var cliprect4 = 0;

	if (settings.length_width == undefined) length_width = 0;
	else length_width 	= parseInt(settings.length_width);
	if (settings.length_height == undefined) length_height = 0;
	else length_height 	= parseInt(settings.length_height);
	if (settings.length_left == undefined) length_left = 0;
	else length_left 	= parseInt(settings.length_left);
	if (settings.length_top == undefined) length_top = 0;
	else length_top 	= parseInt(settings.length_top);
	if (settings.length_borderRadius == undefined) length_borderRadius = 0;
	else length_borderRadius 	= parseInt(settings.length_borderRadius);
	if (settings.length_opacity == undefined) length_opacity = 0;
	else length_opacity 	= parseFloat(settings.length_opacity);
	if (settings.length_clipwidth == undefined) length_clipwidth = 0;
	else length_clipwidth 	= parseInt(settings.length_clipwidth);
	if (settings.length_clipheight == undefined) length_clipheight = 0;
	else length_clipheight 	= parseInt(settings.length_clipheight);

	var styles = getStyles(element);
	var i = 1;
	
//for (var val in styles){
	//var arrays = styles[val].split("adius");
	//a(val)
	//if (arrays.length>1) a(styles[val]);
//}
//a(style.MozBorderRadius);
//a(style.MozBorderRadiusTopleft);

	var nowTop = parseInt(styles.top);
	var nowLeft = parseInt(styles.left);
	var nowWidth = parseInt(styles.width);
	var nowHeight = parseInt(styles.height);
	var nowOpacity = parseInt(styles.opacity);
	var nowClip = styles.clip;
	var nowClipHeight = 0;

	//обработка clip
	if (nowClip != "auto" && nowClip != undefined) {
		var a_nowClip_1 = nowClip.split("(");
		if (a_nowClip_1[1] == undefined){}
		else {
			var a_nowClip_2 = a_nowClip_1[1].split(")");
			var nowClip_rect = a_nowClip_2[0];
			var cliprect = nowClip_rect.split(",");
			cliprect1 = parseInt(cliprect[0]);
			cliprect2 = parseInt(cliprect[1]);
			cliprect3 = parseInt(cliprect[2]);
			cliprect4 = parseInt(cliprect[3]);
			nowClipWidth = cliprect2;
			nowClipHeight = cliprect3;
		}
	}

	//обработка radius (кроссбраузерно)
	var nowborderRadius = 0;
	var typeborderRadius = "empty";
	if (styles.borderTopLeftRadius) {
		nowborderRadius = parseInt(styles.borderTopLeftRadius);
		typeborderRadius = "universal";
	}
	else if (styles.WebkitBorderTopLeftRadius) {
		nowborderRadius = parseInt(styles.WebkitBorderTopLeftRadius);
		typeborderRadius = "chrome";
	}
	else if (styles.MozBorderRadiusTopleft) {
		nowborderRadius = parseInt(styles.MozBorderRadiusTopleft);
		typeborderRadius = "mozilla";
	}
//a(typeborderRadius);
	

	var timeinterval = setInterval(function(){
		if (settings.length_top != undefined) {
			dtop = Math.ceil((length_top/step)*i);
			top = nowTop;
			top += parseInt(dtop);
			element.style.top = top + "px";
		}
		
		if (settings.length_left != undefined) {
			dleft = Math.ceil((length_left/step)*i);
			left = nowLeft;
			left += parseInt(dleft);
			element.style.left = left + "px";
		}
		
		if (settings.length_width != undefined) {
			dwidth = Math.ceil((length_width/step)*i);
			width = nowWidth;
			width += parseInt(dwidth);
			element.style.width = width + "px";
		}
			
		if (settings.length_height != undefined) {
			dheight = Math.ceil((length_height/step)*i);
			height = nowHeight;
			height += parseInt(dheight);
			element.style.height = height + "px";
		}
		
		if (settings.length_opacity != undefined) {
			dopacity = (length_opacity/step)*i;
			opacity = nowOpacity;
			opacity += dopacity;
			element.style.opacity = opacity + "";
			opacity = parseInt(opacity*100);
			element.style.filter = "alpha(opacity="+opacity+")";
		}
		
		if (settings.length_borderRadius != undefined) {
			dborderRadius = Math.ceil((length_borderRadius/step)*i);
			borderRadius = nowborderRadius;
			borderRadius += parseInt(dborderRadius);
			if (typeborderRadius == "universal") {
				element.style.borderTopLeftRadius = borderRadius + "px";
				element.style.borderTopRightRadius = borderRadius + "px";
				element.style.borderBottomLeftRadius = borderRadius + "px";
				element.style.borderBottomRightRadius = borderRadius + "px";
			}
			else if (typeborderRadius == "chrome") {
				element.style.WebkitBorderTopLeftRadius = borderRadius + "px";
				element.style.WebkitBorderTopRightRadius = borderRadius + "px";
				element.style.WebkitBorderBottomLeftRadius = borderRadius + "px";
				element.style.WebkitBorderBottomRightRadius = borderRadius + "px";
			}
			else if (typeborderRadius == "mozilla") {
				element.style.MozBorderRadiusTopleft = borderRadius + "px";
				element.style.MozBorderRadiusTopright = borderRadius + "px";
				element.style.MozBorderRadiusBottomleft = borderRadius + "px";
				element.style.MozBorderRadiusBottomright = borderRadius + "px";
			}
		}
		
		if (settings.length_clipwidth != undefined) {
			dclipwidth = (length_clipwidth/step)*i;
			clipwidth = nowClipHeight;
			clipwidth += dclipwidth;
			cliprect2 = clipwidth;
			element.style.clip = "rect("+cliprect1+"px,"+cliprect2+"px,"+cliprect3+"px,"+cliprect4+"px)";
		}
		if (settings.length_clipheight != undefined) {
			dclipheight = (length_clipheight/step)*i;
			clipheight = nowClipHeight;
			clipheight += dclipheight;
			cliprect3 = clipheight;
			element.style.clip = "rect("+cliprect1+"px,"+cliprect2+"px,"+cliprect3+"px,"+cliprect4+"px)";
		}
		
		i++;

		if (i > step) {
			clearInterval(timeinterval);
			timeinterval = false;
			if (callback != undefined) callback();
		}
	}, speedMoving);
}
//2014-10-08 - добавлена одноименная пользовательская функция (не аналог jquery!!!)
function animate (element, settings, callback) {
	increasePosition(element, settings, callback);
}

/*
//резко останавливает движение
//2014-07-07 - убрал использование
function increasePositionStop(callback){
	clearInterval(universal_timeinterval);
	universal_timeinterval = false;
	if (callback != undefined) callback();
}
*/


//присваивает одинаковое свойство всем элементам класса (обертка)
//classElements - массив с элементами
//settings 	- массив со свойствами, либо - имя свойства
//value 	- пустое, 				либо - значение свойства
function setSettingsByClassName (classElements, settings, value){
	if (typeof(classElements) == "string") classElements = document.getElementsByClassName(classElements);
	if (value == undefined) value = false;
	
	for (var val in classElements) {
		if (typeof(classElements[val]) == "object"){
			//большой цикл
			if (value == false) {
				for (var key in settings) {
					classElements[val].style[key] = settings[key];
				}
			}
			//обычное присвоение
			else {
				classElements[val].style[settings] = value;
			}
		}
	}
}



//jQuery
//переключает checkbox из одного состояния в другое
//element - Обертка элемента или Строка id
function checkCheckbox (element) {
	if (typeof(element) == "string") { element = $("#"+element);}

	if (element.attr("checked")){
		element.removeAttr("checked");
	}
	else {
		element.attr("checked", "checked");
	}
}
//jQuery
//вернуть значение checkbox
//element - Обертка элемента или Строка id
function getValueCheckbox (element) {
	if (typeof(element) == "string") { element = $("#"+element);}
	
	var result = (element.is(':checked')) ? '1' : '0';
	return result;
}
//jQuery
//переключает radio из одного состояния в другое
//element - Обертка элемента или Строка id
function checkRadio (element) {
	if (typeof(element) == "string") { element = $("#"+element);}

	element.attr("checked", "checked");
}
//jQuery
//вернуть значение группы radiobutton
//id - только строка id
function getValueRadio (id) {
	if (typeof(id) == "string") { 	
		var result = $('input[id="o_radio"]:checked').val();
		return result;
	}
	else {
		alert("Universal: getValueRadio() - значение должно быть строкой");
		return false;
	}
}


//num - число или строка (желательно, чтобы было число, иначе обрежется дробная часть)
//coinsTrue - использовать ли копейки. По умолчанию - false, не указываем знаки
	//coinsTrue - указать TRUE или количество знаков после запятой (обычно указываем 2)
function moneyFormat(numstring, coinsTrue){
	if (coinsTrue == undefined) coinsTrue = false;
	if (numstring == "help") {
		var result = "";
		result += "--- (help) ---\n";
		result += "   \n";
		result += "moneyFormat ( numstring, coinsTrue );\n";
		result += "   \n";
		result += "Возвращает:\n";
		result += "   строку, отформатированную по принципу денежного формата,\n";
		result += "   т.е. разбивает число на триады: 1000000 -> 1 000 000\n";
		result += "   \n";
		result += "Параметры:\n";
		result += "   - numstring - число или строка. То, что будем преобразовывать.\n";
		result += "   - coinsTrue - [не обяз., по умолч.= false] число знаков после запятой,\n";
		result += "   если указать = true, это означает 2 знака после запятой.\n";
		result += "   \n";
		
		alert(result);
		return false;
	}
	
	if (typeof(numstring) == "string") {
		numstring = parseFloat(numstring);
	}

	//после преобразования нам - сюда
	if (typeof(numstring) == "number") {
		var result = "";
		var floats = numstring;
		var integ = parseInt(floats);
		
		//преобразование целой части - разделить длинное число на триады
		function getTriades (integ) {
			var integers = parseInt(integ / 1000);
			var fractal = integ % 1000; //задняя часть

			//добавить нули спереди fractal
			fractal = fractal + "";
			var length = fractal.length;
			if (length < 3 && integers > 0) {
				fractal = "000" + fractal;
				fractal = fractal.substr(length, 3);
			}

			//проверить, передняя часть какой длины
			if (integers >= 1000) {
				integers = getTriades(integers);
			}
			
			if (integers == 0) return fractal;
			else return integers + " " + fractal;
		} 
		// --------------------------------------------------------------
		
		//у числа нет дробной части
		if (floats == integ) {
			result = getTriades(integ);
		}
		//у числа есть дробная часть
		else {
			var fractal = "";
			
			//расчет дробной части
			if (coinsTrue) {
				if (coinsTrue === true) coinsTrue = 2;
				
				floats = floats + "";
				var a_floats = floats.split(".");
				fractal = a_floats[1].substr(0, coinsTrue);
				fractal = "." + fractal;
			}
			
			result = getTriades(integ);
			result = result + fractal;
		}
		
		return result;
	}
	else {
		Error.add("moneyFormat: Аргумент numstring не является строкой или числом", "Universal.js");
		return false;
	}
}