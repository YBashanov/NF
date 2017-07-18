/*
Popup2 - выпадающее окно (с тенью) с любым содержимым

-- является УСТАРЕВШЕЙ --

v 1.0 (msoundcafe)
v 1.1 (2014-05-21)
- добавлена кнопка Закрыть - активируется при инициализации (runonload)
- закрывается при клике по тени
! не работает в Internet Explorer 7 (проблемы со слоями)

Требуемые классы:
- Handler
*/

if (! Popup2) var Popup2 = {
	parentIdName: "popup",
	shadowIdName: "shadow_popup",
	htmlIdName 	: "block_popup",
	contentName : "block_popup_content",
	closeIdName : "block_popup_close",
	
	parentElement 	: null,
	shadowElement 	: null,
	htmlElement 	: null,
	contentElement 	: null,
	closeElement 	: null,
	
	isjquery 		: false,
	
	start : function(isButtonClose, isjquery){
		if (isButtonClose == undefined) isButtonClose = false;
		if (isjquery 	== undefined) isjquery = false;
		this.isjquery = isjquery;
	
		this.parentElement 	= document.getElementById(this.parentIdName);
		this.shadowElement 	= document.getElementById(this.shadowIdName);
		this.htmlElement 	= document.getElementById(this.htmlIdName);
		this.contentElement = document.getElementById(this.contentName);
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
	
	setStyles : function(settings){
		if (settings) {
			for(var val in settings) {
				this.htmlElement.style[val] = settings[val];
			}
		}
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
		//else - для jquery весь процесс происходит в файле setHTML
	},
	
	hide : function() {
		this.parentElement.style.display = "none";
		this.clear();
	},
	
	getShadowElement : function(){return this.shadowElement;},
	
	clear : function () {this.contentElement.innerHTML = "";},
	
	//setHeight = false для отмены автовыравнивания блока по центру
	setHTML : function (html, setHeight) {
		if (html == undefined) html = ""; 
		if (setHeight == undefined) setHeight = true; 
		
		this.clear();
		this.contentElement.innerHTML = html;
		
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
	},
	
	//добавляет дочерний элемент целиком, автовыравнивания блока по центру автоматическое
	setChildNode : function (childElement) {
		if (childElement == undefined) childElement = null; 
		
		this.clear();
		this.contentElement.appendChild(childElement);

		if (true) {
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