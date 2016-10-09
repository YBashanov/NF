if (! M_tooltip) var M_tooltip = {
    //здесь вызывать start не обязательно
    start : function (){
        this.div = $(".m_tooltip");
    },
    
    /* создать компонент и хранить в памяти до последующего к нему обращения
        className - имя класса того элемента, при наведении на который будет появляться тултип
        textAttr  - имя атрибута, в котором будет лежать текст для наполнения тултипа
        text      - строка, если мы хотим задать текст явно (если нет атрибута, заданного в textAttr)
        dx        - int / "center" - default - выравнивание тултипа по центру относительно мыши
        dy        - int (5px - default)- показывает, насколько опустить или поднять тултип
        
        css       - {}, стили внешнего блока
        cssContent- {}, стили текста (внутреннего блока)
    */
    create : function(params){
        if (params == undefined)            params = {};
        if (params.className == undefined)  params.className = "default";
        if (params.text == undefined)       params.text = "Задай параметр textAttr";
        if (params.dx == undefined)         params.dx = "center";
        if (params.dy == undefined)         params.dy = 5;
        if (params.css == undefined)        params.css = false;
        if (params.cssContent == undefined) params.cssContent = false;

        if (! this.div){
            this.start();
        }
        
        this.div.append(this._createComponent(params));
    },
    
    show : function(element){
        element.css({"border":"4px solid #f00"});
    },
    
    //установить стили вручную - сразу
    css : function (className, css){
        if (className == undefined) className = "default";

        if (css == undefined) {}
        else {
            this._get(className).component.css(css);
        }
    },
    cssContent : function (className, css){
        if (className == undefined) className = "default";

        if (css == undefined) {}
        else {
            $(this._get(className).component.children()[0]).css(css);
        }
    },
    
    
    _components : {},
    
    _get : function(className){
        if (this._components[className])
            return this._components[className];
        else 
            return false;
    },
    
    _set : function(className, params){
        if (this._components[className] == undefined) {
            this._components[className] = {};
        }
        
        if (params == undefined) {}
        else {
            this._components[className] = params;
        }
    },
    
    //создать компонент
    _createComponent : function(params){
        var me = this;
        if (this._get(params.className) == false) {
            this._set(params.className, params);
        }
        else {
            Error.add("create: Компонент с таким именем уже существует, className:" + params.className, this.thisFile);
        }
    
        var parent  = $("<div class='tooltip'>");
        var content = $("<div class='content'>");
        parent.append(content);

        this._get(params.className).component = parent;
        this._createListeners(params);
        
        if (params.css) {
            this.css(params.className, params.css);
        }
        if (params.cssContent) {
            this.cssContent(params.className, params.cssContent);
        }
        
        return parent;
    },
    
    _createListeners : function(params){
        var me = this;
        if (params.className) {
            $("."+params.className).on("mouseover", function(e){
                var element = $(this);
                var text = element.attr(params.textAttr);
                var textToTip = params.text;
                if (text) {
                    textToTip = text;
                }

                var tip = me._get(params.className).component;
                tip.children()[0].innerHTML = textToTip;
                
                var dx = 0;
                if (params.dx == "center") {
                    dx = -(parseInt(tip.css("width"))/2);
                }
                else {
                    dx = parseInt(params.dx);
                }
                
                tip.css({
                    display : "block", 
                    left : e.clientX + dx + "px",
                    top : e.clientY + parseInt(params.dy) + "px"
                });
            });
            $("."+params.className).on("mousemove", function(e){
                var tip = me._get(params.className).component;
                
                var dx = 0;
                if (params.dx == "center") {
                    dx = -(parseInt(tip.css("width"))/2);
                }
                else {
                    dx = parseInt(params.dx);
                }
                
                tip.css({
                    display : "block",
                    left : e.clientX + dx + "px",
                    top : e.clientY + parseInt(params.dy) + "px"
                });
            });
            $("."+params.className).on("mouseout", function(e){
                var tip = me._get(params.className).component;
                tip.hide();
            });
        }
    }
}




