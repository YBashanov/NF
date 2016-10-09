/*
Popup2 - выпадающее окно (с тенью) с любым содержимым
v 1.0 (msoundcafe)
v 1.1 (2014-05-21)
v 1.2 (2014-09-30)
v 1.3 (2014-10-06) - добавлена функция обратного вызова для закрытия popup
- добавлена кнопка Закрыть - активируется при инициализации (runonload)
- закрывается при клике по тени
! не работает в Internet Explorer 7 (проблемы со слоями)
v 1.4 (2015-08-23) - добавлен скроллинг внутри окна

Требуемые классы:
- Handler
*/

if (! Popup2) var Popup2 = {
	parentIdName: "popup",
	shadowIdName: "shadow_popup",
	htmlIdName 	: "block_popup",
	contentName : "block_popup_content",
	scrollName  : "block_popup_scroll",
	closeIdName : "block_popup_close",
	
	parentElement 	: null,
	shadowElement 	: null,
	htmlElement 	: null,
	contentElement 	: null,
	scrollElement 	: null,
	closeElement 	: null,
	
	isjquery 		: false,
	isOrientation 	: true,
	
	closeFunc : false,
	
	//isOrientation = true - включение ориентирования. При =false отключение ориентирования, 
		//необходимо вручную выставить top, left
	start : function(isButtonClose, isjquery, isOrientation){
		if (isButtonClose 	== undefined) isButtonClose = false;
		if (isjquery 		== undefined) isjquery = false;
		if (isOrientation 	== undefined) isOrientation = true;
		this.isjquery 		= isjquery;
		this.isOrientation 	= isOrientation;

		this.parentElement 	= document.getElementById(this.parentIdName);
		this.shadowElement 	= document.getElementById(this.shadowIdName);
		this.htmlElement 	= document.getElementById(this.htmlIdName);
		this.contentElement = document.getElementById(this.contentName);
		this.scrollElement  = document.getElementById(this.scrollName);
		if (isButtonClose) {
			this.closeElement = document.getElementById(this.closeIdName);
			this.closeElement.style.display = "block";
			Handler.add(this.closeElement, "click", function(){
				Popup2.hide();
			});
		}
		
		Handler.add(this.shadowElement, "click", function(){
			Popup2.hide();
		});
		
		this.setStyles();
	},
	
	setCloseFunction : function(callback) {
		this.closeFunc = callback;
	},
	
	setStyles : function(settings){
		if (settings) {
			for(var val in settings) {
				this.htmlElement.style[val] = settings[val];
			}
		}
        
        //установка стилей для scrollElement
        var height = parseInt($(this.htmlElement).css("height"));
        $(this.scrollElement).css({"height" : height});
	},
	
	getStyles : function (element) {
		if (window.getComputedStyle)
			var styles = window.getComputedStyle(element, null);
		else if (element.currentStyle)
			var styles = element.currentStyle;
		else 
			var styles = null;
		return styles;
	},
	
	show : function() {
		if (this.isjquery == false) {
			this.parentElement.style.display = "block";
		}
        else {
            $(document.body).scrollTop("0");
			$(document.body).css({"overflow":"hidden"});
            
            var $parent_element = $("#"+this.parentIdName);
            $parent_element.css({"display":"block"});
        }
	},
	
	hide : function() {
		this.parentElement.style.display = "none";
		this.clear();
		if (this.closeFunc) this.closeFunc();
        
        if (this.isjquery == true) {
            $(document.body).css({"overflow":"auto"});
        }
	},
	
	getShadowElement : function(){return this.shadowElement;},
	
	clear : function () {this.contentElement.innerHTML = "";},
	
	//setHeight = false для отмены автовыравнивания блока по центру
	setHTML : function (html, setHeight) {
		if (html == undefined) html = ""; 
		if (setHeight == undefined) setHeight = true; 
		
		this.clear();
		this.contentElement.innerHTML = html;

		if (this.isOrientation) {
			if (setHeight) {
				if (this.isjquery == false) {
					var element = this.htmlElement;

					var styles = this.getStyles(element);

					var height 		= parseInt(styles.height);	//ie - auto
					var paddingTop 	= parseInt(styles.paddingTop);
					var paddingBottom = parseInt(styles.paddingBottom);

					if (height !== false) {
						var marginTop = (height + paddingTop + paddingBottom)/2;
						element.style.marginTop = "-" + marginTop + "px";
					}
					else {
						element.style.top = "10%";
					}
				}
				else {
					var $parent_element = $("#"+this.parentIdName);
					var $html_element = $("#"+this.htmlIdName);

					$parent_element.css({"display":"block"});
					
					var height 			= parseInt($html_element.css("height"));	//ie - auto
					var paddingTop 		= parseInt($html_element.css("padding-top"));
					var paddingBottom 	= parseInt($html_element.css("padding-bottom"));

					var marginTop = (height + paddingTop + paddingBottom)/2;
					
					$html_element.css({"margin-top":"-"+marginTop+"px"});
				}
			}
		}
	},
	
	//добавляет дочерний элемент целиком, автовыравнивания блока по центру автоматическое
	setChildNode : function (childElement) {
		if (childElement == undefined) childElement = null; 
		
		this.clear();
		this.contentElement.appendChild(childElement);

		if (this.isOrientation) {
			var element = this.htmlElement;

			var styles = this.getStyles(element);

			var height 		= parseInt(styles.height);	//ie - auto
			var paddingTop 	= parseInt(styles.paddingTop);
			var paddingBottom = parseInt(styles.paddingBottom);

			if (height !== false) {
				var marginTop = (height + paddingTop + paddingBottom)/2;
				element.style.marginTop = "-" + marginTop + "px";
			}
			else {
				element.style.top = "10%";
			}
		}
	},
}