if (! News) var News = {};

	News.thisFile = "mailing.js";
	News.serverpath = "personal";
	News.buttonClick = false;
	News.deletedText = "Удалить строку?";
	News.styleClass = "Def";
	News.styleClassAdj = "DefAdj";
	News.lastIdAdjOpen = false;
	News.historyClicks = {};
	
	//формирование истории очередных кликов по разделам-подразделам
	News.getHistoryClicks = function(){
		var str = "";
		if (News.historyClicks){
			for(var val in News.historyClicks) {
				str += val + "=" + News.historyClicks[val] + "//";
			}
		}
		return str;
	}
	
	
	News.sec_open = function(parent, parent_id, callback){
		if (News.buttonClick == false) {
			News.buttonClick = true;
			
			if (! parent_id) var parent_id = 0;
			if (! parent) var parent = document.getElementById("news");
			if (callback == undefined) var callback = false;
			var html = parent.innerHTML;
			parent.innerHTML = Wait.img();


			HTTP.post (
				"http://"+server+"/ajax/listen/"+News.serverpath+"/mailing_open.php",
				{
					"url":url,
					"parent_id" : parent_id
				},
				function (data){
					var res = data.split("|");
					if (res[0] == 1) {
						parent.innerHTML = res[1];
						callback();
					}
					else if (res[0] == 2) {
						parent.innerHTML = "<span class=\"red\">"+res[1]+"</span>";
					}
					else if (res[0] == 9) {
						location.href = "http://" + server;
					}
					else Error.add(data);
				}
			);
			
			News.buttonClick = false;
		}
	};
	
	News.sec_show = function (id, parent_id){
		if (News.buttonClick == false) {
			News.buttonClick = true;
			
			if (! parent_id) var parent_id = 0;
			var parent = document.getElementById("trSec"+id);
			var html = parent.innerHTML;
			parent.innerHTML = Wait.td("Подождите", News.styleClass);
			
			HTTP.post (
				"http://"+server+"/ajax/listen/"+News.serverpath+"/mailing_show.php",
				{
					"id":id,
					"parent_id" : parent_id
				},
				function (data){
					var res = data.split("|");
					if (res[0] == 1) {
						parent.innerHTML = res[1];
					}
					else if (res[0] == 2) {
						parent.innerHTML = Wait.td("<span class=\"red\">"+res[1]+"</span>", News.styleClass);
						setTimeout(function(){
							parent.innerHTML = html;
						}, 2000);
					}
					else if (res[0] == 9) {
						location.href = "http://" + server;
						return false;
					}
					else Error.add(data);
				}
			);
						
			News.buttonClick = false;
		}
	};
	
	News.sec_save = function(id, parent_id){
		if (News.buttonClick == false) {
			News.buttonClick = true;
			
			if (! parent_id) var parent_id = 0;
			
			
			var dataTrue = true;
			if (dataTrue) {
				var parent = document.getElementById("trSec"+id);
				var html = parent.innerHTML;
				parent.innerHTML = Wait.td("Подождите", News.styleClass);

				HTTP.post (
					"http://"+server+"/ajax/listen/"+News.serverpath+"/mailing_save.php",
					{
						"id":id,
						"parent_id" : parent_id,
					},
					function (data){
						var res = data.split("|");
						if (res[0] == 1) {
							if (id == 0)News.sec_open();
							else parent.innerHTML = res[1];
						}
						else if (res[0] == 2) {
							parent.innerHTML = Wait.td("<span class=\"red\">"+res[1]+"</span>", News.styleClass);
							setTimeout(function(){
								parent.innerHTML = html;
							}, 2000);
						}
						else if (res[0] == 9) {
							location.href = "http://" + server;
							return false;
						}
						else Error.add(data, News.thisFile);
					}
				);
			}
			
			News.buttonClick = false;
		}
	};
	
	News.sec_deleted = function (id, parent_id) {
		if (News.buttonClick == false) {
			News.buttonClick = true;
			
			if (confirm(News.deletedText)){
			
				if (! parent_id) var parent_id = 0;
				var parent = document.getElementById("trSec"+id);
				var html = parent.innerHTML;
				parent.innerHTML = Wait.td("Подождите", News.styleClass);


				var dataTrue = true;
				if (dataTrue) {
					HTTP.post (
						"http://"+server+"/ajax/listen/"+News.serverpath+"/mailing_deleted.php",
						{
							"id":id,
							"parent_id" : parent_id
						},
						function (data){
							var res = data.split("|");
							if (res[0] == 1) {
								parent.innerHTML = Wait.td("<span class=\"green\">"+res[1]+"</span>", News.styleClass);
								setTimeout(function(){
									removeElement(parent);
								}, 2000);
							}
							else if (res[0] == 2) {
								parent.innerHTML = Wait.td("<span class=\"red\">"+res[1]+"</span>", News.styleClass);
								setTimeout(function(){
									parent.innerHTML = html;
								}, 2000);
							}
							else if (res[0] == 9) {
								location.href = "http://" + server;
								return false;
							}
							else Error.add(data, News.thisFile);
						}
					);
				}
			}
			
			News.buttonClick = false;
		}
	};
	
	News.sec_clickCheckbox = function (id, cell) {
		if (News.buttonClick == false) {
			News.buttonClick = true;
			if (cell == undefined) cell = "status";
			
			var parent = document.getElementById(cell + ""+id);
			var html = parent.innerHTML;
			parent.innerHTML = "...";
			
			var dataTrue = true;
			if (dataTrue) {
				HTTP.post (
					"http://"+server+"/ajax/listen/"+News.serverpath+"/mailing_clickCheckbox.php",
					{
						"id":id,
						"cell":cell
					},
					function (data){
						var res = data.split("|");
						if (res[0] == 1) {
							parent.innerHTML = res[1];
						}
						else if (res[0] == 2) {
							parent.innerHTML = "<span class=\"red\">"+res[1]+"</span>";
							setTimeout(function(){
								parent.innerHTML = html;
							}, 2000);
						}
						else if (res[0] == 9) {
							location.href = "http://" + server;
							return false;
						}
						else Error.add(data, News.thisFile);
					}
				);
			}
			
			News.buttonClick = false;
		}
	};
	
	News.sec_adjOpen = function (id, cell, language){
		if (News.buttonClick == false) {
			News.buttonClick = true;

			if (language == undefined) language = "ru";
			if (cell == undefined) cell = "text";
			
			if (id) {
				var adjust = document.getElementById("adjust");
				var htmlAdjust = adjust.innerHTML;
				adjust.innerHTML = Wait.td("Подождите", News.styleClassAdj);
				
				
				//делаем окантовку
				if (News.lastIdAdjOpen != false) {
					//убираем окантовку
					var tr = document.getElementById(News.lastIdAdjOpen);
					tr.style.borderLeft = "0px";
				}
				var tr = document.getElementById("trSec"+id);
				tr.style.borderLeft = "2px solid #00f";
				News.lastIdAdjOpen = "trSec"+id;
				
				var historyClicks = News.getHistoryClicks();
			

				HTTP.post (
					"http://"+server+"/ajax/listen/"+News.serverpath+"/mailing_adjust_show.php",
					{
						"id":id,
						"cell":cell,
						"url":url,
						"language":language,
						"historyClicks":historyClicks,
					},
					function (data){
						var res = data.split("|");
						if (res[0] == 1) {
							if (id) {
								adjust.innerHTML = res[1];

tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave",

	theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect",
	theme_advanced_buttons2 : "cut,copy,pasteword,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
	theme_advanced_buttons3 : "tablecontrols,|,sub,sup,|,charmap,emotions,iespell,media,|,fullscreen",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,
});

							}
						}
						else if (res[0] == 2) {
							adjust.innerHTML = Wait.td("<span class='red'>"+res[1]+"</span>", News.styleClassAdj);
							setTimeout(function(){
								adjust.innerHTML = html;
							}, 2000);
						}
						else if (res[0] == 9) {
							location.href = "http://" + server;
							return false;
						}
						else Error.add(data);
					}
				);
			}
			News.buttonClick = false;
		}
	};
	
	News.sec_adjClose = function (id){
		if (News.buttonClick == false) {
			News.buttonClick = true;

			if (id) {
				var adjust = document.getElementById("adjust");
				var htmlAdjust = adjust.innerHTML;
				adjust.innerHTML = "";
				
				
				//убираем окантовку
				var tr = document.getElementById(News.lastIdAdjOpen);
				tr.style.borderLeft = "0px";
				News.lastIdAdjOpen = false;
			}
			
			News.buttonClick = false;
		}
	};