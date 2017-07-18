if (! M_search) var M_search = {
    thisFile : "m_search/js/script",
	settings : {},
	
    start : function (){
		var input = $(".m_search > .search")

		input.val(this.getLangText("search"));

		this.settings.input = input;
		this.setListeners (input);
	},
    
	setListeners : function(element){
        var me = this;
        element.on('focus', function(e){
            if (element.val() == me.getLangText("search")) {
				element.val(me.getLangText("empty"));
			}
        });
        element.on('blur', function(e){
            if (element.val() == me.getLangText("empty")) {
				element.val(me.getLangText("search"));
			}
        });
        element.on('keydown', function(e){
            var keyCode = e.keyCode;
			if (keyCode == undefined) keyCode = window.event.keyCode;

			if (
				element.val() !== me.getLangText("search") 
				&& element.val() !== me.getLangText("empty")
			){
				if (keyCode == 13){
					me.searchClick(element.val());
				}
			}
        });
	},
	
	//действие зависит от установленных параметров
	//action = direct (прямое воздействие)
    //action = outer (вызов метода внешнего модуля)
        
	//action = ajax 
		//(ajax) path - путь до файла обработчика
		//(ajax) parent_div - блок, в который возвращаются данные после выборки из базы
	searchClick : function (value) {
        var me = this;
		if (value) {
			value = encodeURIComponent(value);

			if (me.settings.action == "direct") {
				location.href = "http://"+server+"/"+page+"/"+language+"/?search="+value;
			}
            else if (me.settings.action == "outer"){
				//прогресс
				var input = me.settings.input;
				Wait.j_imgBarStop(input);
				Wait.j_imgBar(input, me.settings.pathBgImage);
                
                M_search.settings.outerMetod({text : value}, function(data){
                    Wait.j_imgBarStop(input);
                    
                    //если ничего не найдено
                    if (data.length <= 0) {
                        me.nothing(input);
                    }
                });
            }
			else if (me.settings.action == "ajax") {
				if (me.settings.path == undefined || me.settings.path == "") {
					Error.add("M_search: Путь path не выбран");
				}
				else {
					//прогресс
					var input = me.settings.input;
					Wait.j_imgBarStop(input);
					Wait.j_imgBar(input, me.settings.pathBgImage);

                    $.post(
                        "http://"+server+"/"+me.settings.path+".php",
                        {
							"language":language,
							"value":value
						},
                        function(data){
                            Wait.j_imgBarStop(input);
                            
                            //если больше после прокрутки ничего не возвращается - принудительно ставим goLoad = false
                            if (data.length == 0) {
                                me.nothing(input);
                            }
                            else {
                                if (data.data.length <= 0) {
                                    me.nothing(input);
                                }
                                else {
                                    if (me.settings.parent_div && me.settings.parent_div != "") {
                                        //не доработано
                                    }
                                    else {
                                        Error.add("M_search: Не указан внешний метод, формирующий данные");
                                    }
                                }
                            }
                        },
                        "json"
                    );
				}
			}
			else {
				Error.add("M_search: Воздействие на url не выбрано");
			}
		}
	},
    
    //когда ничего не найдено
    nothing : function(input) {
        var me = this;
        if (! me.settings.errorStyles) {
            Error.add("M_search: Не заданы стили ошибки");
        }
        
        var memory = {};
        for(var val in me.settings.errorStyles) {
            memory[val] = input.css(val);
        }

		input.css(me.settings.errorStyles);
		input.val(me.getLangText("nothing"));
		input.blur();
        input.attr({"disabled":""});
		
		setTimeout(function(){
			input.css(memory);
			input.val(me.getLangText("search"));
            input.removeAttr("disabled");
		}, 1500);
    }
}

