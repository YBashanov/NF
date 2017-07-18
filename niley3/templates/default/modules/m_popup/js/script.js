if (! M_popup) var M_popup = {
    //здесь вызывать start не обязательно
    start : function (){
        this.div = $(".m_popup");
    },

    //создать окно и поместить его в главный div
    create : function(params){
        if (params == undefined)            params = {};
        if (params.name == undefined)       params.name = "default";
        if (params.closeButton == undefined)params.closeButton = false;
        if (params.isAutoMarginTop == undefined)params.isAutoMarginTop = true; //расчитывать margin-top автоматически
        if (params.css == undefined)        params.css = false;
        if (params.shadowCss == undefined)  params.shadowCss = false;

        if (! this.div) {
            this.start();
        }

        this.div.append(this._createBlock(params));
    },
    
    //показать сформированное окно
    show : function(name) {
        if (name == undefined) name = "default";
        
        if (this._getPopup(name)) {
            this._getPopup(name).popup.css({display : "block"});
            
            //если установлен авто-расчет стиля margin-top
            if (this._getPopup(name).params.isAutoMarginTop) {
                var block = this._getPopup(name).block;

                var height 			= parseInt(block.css("height"));	//ie - auto
                var paddingTop 		= parseInt(block.css("padding-top"));
                var paddingBottom 	= parseInt(block.css("padding-bottom"));

                var marginTop = (height + paddingTop + paddingBottom)/2;
                block.css({"margin-top":"-"+marginTop+"px"});
            }
        }
        else {
            Error.add("show: Popup-окно с таким именем не создано, name: "+name, this.thisFile);
        }
    },
    
    //скрыть сформированное окно
    hide : function(name) {
        if (name == undefined) name = "default";
        
        if (this._getPopup(name)) {
            this._getPopup(name).popup.css({display : "none"});
        }
        else {
            Error.add("hide: Popup-окно с таким именем не создано, name: "+name, this.thisFile);
        }
    },
    
    //наполнить окно текстом, html
    html : function(name, html){
        if (name == undefined) name = "default";
        if (html == undefined) html = "";
        
        if (this._getPopup(name)) {
            this._getPopup(name).content.html(html);
        }
        else {
            Error.add("html: Popup-окно с таким именем не создано, name: "+name, this.thisFile);
        }
    },
    
    //установить стили вручную - сразу
    css : function (name, css){
        if (name == undefined) name = "default";

        if (css == undefined) {}
        else {
            if (css.width && ! css["margin-left"]) {
                css["margin-left"] = "-" + parseInt(css.width)/2 + "px";
            }
            this._getPopup(name).params.css = css;
            this._getPopup(name).block.css(css);
            
            if (css.width){
                this._getPopup(name).scroll.css({width : parseInt(css.width) + 30 + "px"});
            }
            
            //если четко задана высота, выполнить пересчет и для дочернего элемента
            if (css.height) {
                var block = this._getPopup(name).block;
                var paddingTop 		= parseInt(block.css("padding-top"));
                var paddingBottom 	= parseInt(block.css("padding-bottom"));
                
                var cont_height = parseInt(css.height) - (paddingTop + paddingBottom);
                this._getPopup(name).content.css({height: cont_height + "px"});
            }
        }
    },
    
    //здесь все созданные popup-окна
    // popup - окно с набором div-ов
    // content - блок, куда будет вставляться текст
    _popups : {}, 
    
    _getPopup : function(name){
        if (this._popups[name])
            return this._popups[name];
        else 
            return false;
    },
    
    _setPopup : function(name, params){
        if (this._popups[name] == undefined) {
            this._popups[name] = {};
        }
        
        if (params == undefined) {}
        else {
            this._popups[name] = params;
        }
    },
    
    //создать полный popup-блок
    _createBlock : function(params){
        var me = this;
        if (this._getPopup(params.name) == false) {
            this._setPopup(params.name);
        }
        else {
            Error.add("create: Окно с таким именем уже существует, name:" + params.name, this.thisFile);
        }
    
        var parent  = $("<div class='parent'>");
        var shadow  = $("<div class='shadow'>");
        var block   = $("<div class='block'>");
        if (params.closeButton) {
            var button  = $("<div class='button'>");
            var img     = $("<img src='"+base_url+"templates/"+template+"/modules/m_popup/image/popup2_close.png'>");
        }
        var scroll  = $("<div class='scroll'>");
        var content = $("<div class='content'>");
        
        if (params.closeButton) {
            button.append(img);
            block.append(button);
            button.on('click', function(){
                me.hide(params.name);
            });
        }
        
        if (params.shadowCss != false) {
            shadow.css(params.shadowCss);
        }
        
        block.append(scroll);
        scroll.append(content);
        parent.append(shadow);
        parent.append(block);
        
        shadow.on('click', function(){
            me.hide(params.name);
        });
        
        this._getPopup(params.name).popup   = parent;
        this._getPopup(params.name).block   = block;
        this._getPopup(params.name).scroll  = scroll;
        this._getPopup(params.name).content = content;
        this._getPopup(params.name).params  = params;
        
        if (params.css) {
            this.css(params.name, params.css);
        }
        
        return parent;
    },
    
    _setShadowCss : function(params){
        
    }
}