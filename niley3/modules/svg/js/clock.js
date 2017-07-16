var Clock = {
	typeOfClock : "mehanik",		//electro - электронные, mehanik - механические
	divImgs : [],
	deltaArrows : [],		//для уменьшения нагрузки на картинку (четвертные углы между целыми переходами стрелки)
	widthImgs : 20,
	centerWidth : 100,
	centerHeight : 100,
	ie : null,
	setIE : function(string){
		Clock.ie = string;
	},
	
	start : function (){
		var interval = setInterval(function(){
			var date = new Date();
			Clock.dispatcher(date);
		}, 1000);
	},
	
	//управление электронными часами
	dispatcher : function (date) {
		var nums = Clock.numerals(date);
		if (Clock.typeOfClock == "electro") Clock.setElectroImages(nums);
		else if (Clock.typeOfClock == "mehanik") Clock.setMehanicImages(nums);
	},
	
	//вычисляет отдельно каждую цифру (для электронных часов)
	numerals : function(date){
		if (Clock.typeOfClock == "electro") {
			var Num1 = Math.floor(date.getHours()/10);
			var Num2 = date.getHours()-Num1*10;
			var Num3 = Math.floor(date.getMinutes()/10);
			var Num4 = date.getMinutes()-Num3*10;
			var Num5 = Math.floor(date.getSeconds()/10);
			var Num6 = date.getSeconds()-Num5*10;
			
			return Num1 +"|"+ Num2 +"|"+ Num3 +"|"+ Num4 +"|"+ Num5 +"|"+ Num6;
		}
		else if (Clock.typeOfClock == "mehanik") {
			var Num1 = date.getHours();
			var Num2 = date.getMinutes();
			var Num3 = date.getSeconds();
			
			return Num1 +"|"+ Num2 +"|"+ Num3;
		}
	},
	
	setElectroImages : function (nums){
		var divImg1 = document.getElementById("clock1");
		var divImg2 = document.getElementById("clock2");
		var divImg3 = document.getElementById("clock3");
		var divImg4 = document.getElementById("clock4");
		var divImg5 = document.getElementById("seconds");
		
		var res = nums.split("|");

		//чтобы лишний раз не обновлять стрелки
		if (Clock.divImgs[0] == undefined) Clock.divImgs[0] = false;
		if (Clock.divImgs[1] == undefined) Clock.divImgs[1] = false;
		if (Clock.divImgs[2] == undefined) Clock.divImgs[2] = false;
		if (Clock.divImgs[3] == undefined) Clock.divImgs[3] = false;
		if (Clock.divImgs[4] == undefined) Clock.divImgs[4] = false;
		
		//часы
		if (Clock.divImgs[0] != res[0]){
			divImg1.innerHTML = "<img src='http://"+server+"/@/img/clock/"+res[0]+".png' width='"+Clock.widthImgs+"'/>";
			Clock.divImgs[0] = res[0];
		}
		if (Clock.divImgs[1] != res[1]){
			divImg2.innerHTML = "<img src='http://"+server+"/@/img/clock/"+res[1]+".png' width='"+Clock.widthImgs+"'/>";
			Clock.divImgs[1] = res[1];
		}
		//минуты
		if (Clock.divImgs[2] != res[2]){
			divImg3.innerHTML = "<img src='http://"+server+"/@/img/clock/"+res[2]+".png' width='"+Clock.widthImgs+"'/>";
			Clock.divImgs[2] = res[2];
		}
		if (Clock.divImgs[3] != res[3]){
			divImg4.innerHTML = "<img src='http://"+server+"/@/img/clock/"+res[3]+".png' width='"+Clock.widthImgs+"'/>";
			Clock.divImgs[3] = res[3];
		}
		//секунды
		if (Clock.divImgs[4] == true){
			divImg5.innerHTML = "<img src='http://"+server+"/@/img/clock/seconds.png' width='"+Clock.widthImgs+"'/>";
			Clock.divImgs[4] = false;
		}
		else if (Clock.divImgs[4] == false){
			divImg5.innerHTML = "";
			Clock.divImgs[4] = true;
		}
	},
	
	setMehanicImages : function(nums){
		var hour = document.getElementById("hour");
		var minute = document.getElementById("minute");
		var second = document.getElementById("second");
		
		var res = nums.split("|");
		var deltaArrow = [];

		//чтобы лишний раз не обновлять стрелки
		if (Clock.divImgs[0] == undefined) Clock.divImgs[0] = false;
		if (Clock.divImgs[1] == undefined) Clock.divImgs[1] = false;
		if (Clock.divImgs[2] == undefined) Clock.divImgs[2] = false;

		//добавление к часовой - угла за минуты
		deltaArrow[0] = Math.floor(res[1]/5) * 2.5;
		//добавление к минутной - угла за секунды
		deltaArrow[1] = Math.floor(res[2]/15) * 1.5;
		
		var delta = 0;
		var resultAngle = 0;
		//часы
		if (Clock.divImgs[0] !== res[0] || Clock.deltaArrows[0] !== deltaArrow[0]){
			resultAngle = (res[0] * 30) + deltaArrow[0];
			Drag.setTransformAttrs (hour, 0, 0, resultAngle, Clock.centerWidth, Clock.centerHeight, Clock.ie);
			Clock.divImgs[0] = res[0];
			Clock.deltaArrows[0] = deltaArrow[0];
		}
		//минуты
		if (Clock.divImgs[1] != res[1] || Clock.deltaArrows[1] !== deltaArrow[1]){
			resultAngle = (res[1] * 6) + deltaArrow[1];
			Drag.setTransformAttrs (minute, 0, 0, resultAngle, Clock.centerWidth, Clock.centerHeight, Clock.ie);
			Clock.divImgs[1] = res[1];
			Clock.deltaArrows[1] = deltaArrow[1];
		}
		//секунды
		if (Clock.divImgs[2] != res[2]){
			resultAngle = res[2] * 6;
			Drag.setTransformAttrs (second, 0, 0, resultAngle, Clock.centerWidth, Clock.centerHeight, Clock.ie);
			Clock.divImgs[2] = res[2];
		}
	}
}