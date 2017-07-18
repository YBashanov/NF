var Disc = {
	thisIntervals : {}, //id :, interval : null
	thisIntervalId : undefined,
	ie : null
};


Disc.rotate = function (id){
	var num = Disc.isThisIntervalThere(id);
	if (num === false) return false;

	var id = Disc.thisIntervals[num].id;
	var x = Disc.thisIntervals[num].x;
	var y = Disc.thisIntervals[num].y;
	var angle = Disc.thisIntervals[num].angle;
	var delta = Disc.thisIntervals[num].angleDelta;
	var xR = Disc.thisIntervals[num].xR;
	var yR = Disc.thisIntervals[num].yR;

	var disc = document.getElementById(id);
	Drag.setTransformAttrs (disc, x, y, angle, xR, yR, Disc.ie);
	var interval = setInterval(function(){
		Drag.setTransformAttrs (disc, x, y, angle, xR, yR, Disc.ie);
		angle += delta;
		Disc.thisIntervals[num].angle = angle;
	}, 20);
	return interval;
}


Disc.start = function (id, x, y, delta, xR, yR, ie){
	var num = Disc.isThisIntervalThere(id);
	if (num === false) 
		num = Disc.increaseIntervalId();

	if (Disc.thisIntervals[num] == undefined || Disc.thisIntervals[num].interval == null) {
	
		if (Disc.thisIntervals[num] == undefined) Disc.thisIntervals[num] = {};
		
		Disc.thisIntervals[num].id = id;
		Disc.thisIntervals[num].x = x;
		Disc.thisIntervals[num].y = y;
		if (Disc.thisIntervals[num].angle == undefined) Disc.thisIntervals[num].angle = 0;
		Disc.thisIntervals[num].angleDelta = delta;
		Disc.thisIntervals[num].xR = xR;
		Disc.thisIntervals[num].yR = yR;
		Disc.thisIntervals[num].interval = Disc.rotate(id);

		if (ie != undefined) {
			Disc.ie = ie;
		}
	}
}


Disc.stop = function (id){
	var num = Disc.isThisIntervalThere(id);
	if (num === false) return false;

	if (Disc.thisIntervals[num].interval != null) {
		clearInterval(Disc.thisIntervals[num].interval);
		Disc.thisIntervals[num].interval = null;
	}
}


//проверяет, есть ли уже такой id в цикле (есть ли у него порядковый номер)
Disc.isThisIntervalThere = function (id){
	if (Disc.thisIntervalId >= 0) {
		for (var i=0; i<=Disc.thisIntervalId; i++){
			if (Disc.thisIntervals[i].id == id) {
				return i;
			}
		}
		return false;
	}
	else return false;
}


//увеличить общий порядковый номер
Disc.increaseIntervalId = function () {
	if (Disc.thisIntervalId == undefined) Disc.thisIntervalId = 0;
	else Disc.thisIntervalId++;

	return Disc.thisIntervalId;
}