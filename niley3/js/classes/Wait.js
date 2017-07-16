/*
2016-01-20 - Расширен метод img()
*/
var Wait = {
	waitWorld : "Подождите",
	waitSymbol : ".",
	waitInterval : false,
	
	plus : "<div class='plus'>+</div>",
	minus : "<div class='minus'>-</div>",
	
    //css - объект стилей. Можно установить абсолютное позиционирование
	img : function (width, css) {
        var style = "";
		if (width == undefined) width = 20;
		if (css == undefined) css = {};
        else {
            style = "style='";
            for(var val in css){
                style += val + ":" + css[val] + ";";
            }
            style += "'";
        }
		return "<img src='http://"+server+"/templates/default/image/wait/wait.gif' "+style+" width='"+width+"'/>";
	},
	
	imgBar : function (element, path_to_img) {
		if (typeof(element) == "string") element = document.getElementById(element);
		if (element) {
			if (path_to_img == undefined) path_to_img = "wait/input_wait.png";

			element.style.backgroundImage = "url(http://"+server+"/templates/default/image/"+path_to_img+")";
			element.style.backgroundPosition = "0px 0px";

			var i=0;
			Wait.waitInterval = setInterval(function(){
				element.style.backgroundPosition = i+"px 0px";
				i++;
			}, 50);
		}
		else return false;
	},
	
	imgBarStop : function (element) {
		clearInterval(Wait.waitInterval);
		Wait.waitInterval = false;
		if (element) {
			element.style.backgroundImage = "url()";
			element.style.backgroundPosition = "0px 0px";
		}
	},
    
    //с использованием jquery
    j_imgBar : function (element, path_to_img) {
		if (element) {
			if (path_to_img == undefined) path_to_img = "wait/input_wait.png";

			element.css({
                backgroundImage : "url(http://"+server+"/"+path_to_img+")",
                backgroundPosition : "0px 0px"
            });

			var i=0;
			Wait.waitInterval = setInterval(function(){
				element.css({backgroundPosition : i+"px 0px"});
				i++;
			}, 50);
		}
		else return false;
	},
	
    //с использованием jquery
	j_imgBarStop : function (element) {
		clearInterval(Wait.waitInterval);
		Wait.waitInterval = false;
		if (element) {
            element.css({
                backgroundImage : "url()",
                backgroundPosition : "0px 0px"
            });
		}
	},
	
	//размещает полосу ожидания внутри элемента, по центру
	//element - внутри которого собираемся расположить полосу
	//width, height - если заданы, то они используются. Если нет, используются размеры элемента element
	//возвращает весь удаленный контент элемента
	imgBar_center : function (element, width, height){
		if (typeof(element) == "string") element = document.getElementById(element);
		var styleElement = getStyles(element);

		var shadowDiv = document.createElement("div");
		shadowDiv.style.position = "absolute";
		shadowDiv.style.top = "0px";
		shadowDiv.style.left = "0px";
		if (width == undefined) shadowDiv.style.width = styleElement.width; else shadowDiv.style.width = width + "px";
		if (height== undefined) shadowDiv.style.height= styleElement.height;else shadowDiv.style.height= height+ "px";
		shadowDiv.style.backgroundColor = "#000";
		shadowDiv.style.opacity = "0.6";
		shadowDiv.style.filter = "alpha(opacity=60)";
		
		var div = document.createElement("div");
		//div.style.position = "absolute";
		div.style.top = "0px";
		div.style.left = "0px";
		if (width == undefined) div.style.width = styleElement.width; else div.style.width = width + "px";
		if (height== undefined) div.style.height= styleElement.height;else div.style.height= height+ "px";

		//if (div.style.width <= 200) divWait.style.width = "50px";
		
		var divWait = document.createElement("div");
		divWait.style.border = "1px solid #ccc";
		divWait.style.width = "200px";
		divWait.style.height = "16px";
		divWait.style.left = (parseInt(div.style.width)/2) + "px";
		divWait.style.marginLeft = "-100px";
		divWait.style.top = (parseInt(div.style.height)/2) + "px";
		divWait.style.marginTop = "-8px";
		Wait.imgBar(divWait);

		div.appendChild(divWait);
		
		var html = element.innerHTML;
		element.innerHTML = "";
		element.appendChild(shadowDiv);
		element.appendChild(div);

		return html;
	},
	
	text : function (str){
		if (str == undefined) str = "Подождите...";
		return str;
	},
	
	td : function (str, className){
		if (className == undefined) className = "";
		return "<div class='td tdSum"+className+"'>"+str+"</div><div class='cle'></div>";
	},
	
	//подождите... бегут точки
	start : function(element){
		var i = 0;
		var remember = Wait.waitWorld;
		Wait.waitInterval = setInterval(function(){
			remember += Wait.waitSymbol;
			element.innerHTML = remember;
			i++;
			if (i >= 4) {
				remember = Wait.waitWorld;
				i = 0;
			}
		}, 500);
	},
	stop : function(){
		clearInterval(Wait.waitInterval);
		Wait.waitInterval = false;
	}
}