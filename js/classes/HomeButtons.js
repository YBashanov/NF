var HomeButtons = {
	buttons : 5,
	activeImageId : "but",
	timeIncreaseIntervals : [],	//увеличение
	timeReductionIntervals : [],//уменьшение
	maxWidth : 149,				//максимальное увеличение
	minWidth : 143,
	maxHeight : 40,
	minHeight : 28,
	stepHeight : 2,	
	stepWidth : 4,				//px - т.к. медленное изменение x, y - по 1 пикселу
	maxFontSize : 16,
	minFontSize : 16,
	colorFontMin : "#000033",
	colorFontMax : "#ffffff"
}


HomeButtons.listenerButtons = function () {
	for (var i=1; i<=HomeButtons.buttons; i++) {
		var element = document.getElementById (HomeButtons.activeImageId + i);
		HomeButtons.addHandlers (element, i);
	}
}
runOnLoad.funcs[1] = HomeButtons.listenerButtons;



HomeButtons.addHandlers = function (element, i) {
	var styles = Popup.getStyles(element);
	HomeButtons.timeIncreaseIntervals[i] = false;
	HomeButtons.timeReductionIntervals[i] = false;

	var child = element.children[0];
	if (child) {
		var stylesChild = Popup.getStyles(child);
	}

	Handler.add (element, "mouseover", function(e){	
		if (HomeButtons.timeReductionIntervals[i] != false) {
			clearInterval(HomeButtons.timeReductionIntervals[i]);
			HomeButtons.timeReductionIntervals[i] = false;
		}
		var height = parseInt(styles.height);
		//var height = 0;
		var marginTop = 0;
		var paddingTop = 0;
		if (height < HomeButtons.maxHeight) {
			if (HomeButtons.timeIncreaseIntervals[i] == false) {
				HomeButtons.timeIncreaseIntervals[i] = setInterval(function(){
					width = parseInt(styles.width);
					width += HomeButtons.stepWidth;
					element.style.width = width + "px";
					
					height = parseInt(styles.height);
					height += HomeButtons.stepHeight;
					element.style.height = height + "px";
					
					element.style.top = styles.top;
					var newTop = parseInt(styles.top) - 1;
					element.style.top = newTop + "px";
					
					element.style.left = styles.left;
					var newLeft = parseInt(styles.left) - 2;
					element.style.left = newLeft + "px";
					
					if (child) {
						marginTop = parseInt(stylesChild.marginTop);
						marginTop += 1;
						child.style.marginTop = marginTop + "px";
						child.style.fontSize = HomeButtons.maxFontSize + "px";
						child.style.color = HomeButtons.colorFontMax;
					}
					
					if (height >= HomeButtons.maxHeight) {
						clearInterval(HomeButtons.timeIncreaseIntervals[i]);
						HomeButtons.timeIncreaseIntervals[i] = false;
						
						//element.style.backgroundImage = "url(/@/backg/buttons/2.gif)";
					}
				}, 15);
			}
		}
	});
	
	
	Handler.add (element, "mouseout", function(e){
		if (HomeButtons.timeIncreaseIntervals[i] != false) {
			clearInterval(HomeButtons.timeIncreaseIntervals[i]);
			HomeButtons.timeIncreaseIntervals[i] = false;
		}
		var height = parseInt(styles.height);
		//var height = 0;
		var paddingTop = 0;
		if (height > HomeButtons.minHeight) {
			if (HomeButtons.timeReductionIntervals[i] == false) {
				HomeButtons.timeReductionIntervals[i] = setInterval(function(){
					width = parseInt(styles.width);
					width -= HomeButtons.stepWidth;
					element.style.width = width + "px";
					
					height = parseInt(styles.height);
					height -= HomeButtons.stepHeight;
					element.style.height = height + "px";
					
					element.style.top = styles.top;
					var newTop = parseInt(styles.top) + 1;
					element.style.top = newTop + "px";
					
					element.style.left = styles.left;
					var newLeft = parseInt(styles.left) + 2;
					element.style.left = newLeft + "px";
					
					if (child) {
						marginTop = parseInt(stylesChild.marginTop);
						marginTop -= 1;
						child.style.marginTop = marginTop + "px";
						child.style.fontSize = HomeButtons.minFontSize + "px";
						child.style.color = HomeButtons.colorFontMin;
					}
					
					if (height <= HomeButtons.minHeight) {
						clearInterval(HomeButtons.timeReductionIntervals[i]);
						HomeButtons.timeReductionIntervals[i] = false;
					}
				}, 15);
			}
		}
	});
}