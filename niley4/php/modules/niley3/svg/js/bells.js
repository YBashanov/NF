if (! Bells) var Bells = function(){}

Bells.prototype = {
	status : {},
	
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
	
	start : function (element){
		if (typeof (element) == "string") element = document.getElementById(element);
		
		Drag.setTransformAttrs(element, 40, 0);
		if (! this.status[element.id]) this.status[element.id] = {};
		this.status[element.id].angle = 0;
		this.status[element.id].isAngleGo = false;
		
		this.setListeners(element);
	},
	
	setListeners : function (element) {
		var thisObj = this;
		
		Handler.add(element, "mouseover", function(){
			thisObj.mouseover(element);
		});
	},
	
	mouseover : function (element){
		if (this.status[element.id].isAngleGo == false ) {
			this.status[element.id].isAngleGo = true;
			
			var random = parseInt(Math.random()*10)%2;
			var direction = "";
			random == 0 ? direction = "+" : direction = "-";

			//непосредственно к ситуации
			if (this.settings.type == "short") {
				this.swing(element, 5, 5, direction);
			}
			else if (this.settings.type == "long") {
				this.swing(element, 2, 2, direction);
			}
		}
	},
	
	//размахивание, качели
	//steps - количество шагов, за которое должно совершиться колебание
	//angleLength - максимальная длина отклонения за этот период
	//direction - направление отклонения - плюс или минус
	swing : function (element, steps, angleLength, direction) {
		if (element		== undefined) alert("Bells.swind: нет элемента element");
		if (steps 		== undefined) alert("Bells.swind: не выбрано steps");
		if (angleLength == undefined) alert("Bells.swind: не выбрано angleLength");
		if (direction 	== undefined) alert("Bells.swind: не выбрано direction");
		var thisObj = this;
		
		//слагаемое
		var term = angleLength/steps;
		var step = 1;
		var startAngle = thisObj.status[element.id].angle;

		var interval = setInterval(function(){
			if (direction == "+") thisObj.status[element.id].angle = startAngle + term*step;
			if (direction == "-") thisObj.status[element.id].angle = startAngle - term*step;
		
			Drag.setTransformAttrs(element, 40, 0, thisObj.status[element.id].angle, 40);
			
			if (step >= steps) {
				clearInterval(interval);
				if (thisObj.status[element.id].angle != 0) {
					//каждое колебание уменьшается на 2 градуса
					var absAngle = Math.abs(thisObj.status[element.id].angle);
//a(thisObj.status[element.id].angle + " - " + absAngle);


//уменьшение угла
if (thisObj.settings.type == "short") 
	var deltaAngle = 1;
else 
	var deltaAngle = 0.4;
					angleLength = absAngle + absAngle - deltaAngle;
					direction == "+" ? direction = "-" : direction = "+";
					
//замедление качания
if (thisObj.settings.type == "short") 
	angleLength > 8 ? steps = angleLength : steps = angleLength*2;
else 
	steps = angleLength*5;
	
//a(angleLength);
					if (angleLength > 0.1) {
						thisObj.swing(element, steps, angleLength, direction);
					}
					else {
						thisObj.status[element.id].isAngleGo = false;
					}
				}
				else {
					thisObj.status[element.id].isAngleGo = false;
				}
			}
			else step++;
		}, 20);
	}

}