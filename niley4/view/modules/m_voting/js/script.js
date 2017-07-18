if (! M_voting) var M_voting = {
    url_save : "http://"+server+"/templates/"+template+"/modules/m_voting/ajax/vote_save.php",
    image_now : null,
    setAccess : false, //разрешение на голосование. Если = 0 - можно голосовать
    
    //из другого скрипта - установка чисел -------
    //type = scroll, click
	set : function(data) {        
        $("#place_vote_plus").html(data.vote_plus);
        $("#place_vote_minus").html(data.vote_minus);

        this.image_now = data;
	},
    //замена черно белых картинок цветными
    //type = true - на цветные
    coloring : function(type){
        if (type === undefined) type = true;
        
        if (type == true) {
            $("#button_vote_plus").css({'background-image':M_voting.getBgColor()[0]});
            $("#button_vote_minus").css({'background-image':M_voting.getBgColor()[1]});
            this.setAccess = true;
        }
        else {
            $("#button_vote_plus").css({'background-image':M_voting.getBgGray()[0]});
            $("#button_vote_minus").css({'background-image':M_voting.getBgGray()[1]});
            this.setAccess = false;
        }
    },
    // -------------------------------------------
    
    
    start : function(){
        this.listeners();
    },
    
    listeners : function(){
        var me = this;
        $("#button_vote_plus").on('click', function(e){
            if (me.setAccess)
                me.vote_plus(e);
        });
        $("#button_vote_minus").on('click', function(e){
            if (me.setAccess)
                me.vote_minus(e);
        });
    },
    vote_plus : function(e){
        if (this.image_now){
            this.sendPost(e, {vote : "vote_plus"});
        }
    },
    vote_minus : function(e){
        if (this.image_now){
            this.sendPost(e, {vote : "vote_minus"});
        }
    },
    
    //params = {}
    // !! ----------------- голосование вынести в отдельный модуль, т.к. будет еще и php-файл
    sendPost : function(e, params){
        var me = this;
        if (! params) params = {};
        
        var params_send = $.extend(params, {
            id : this.image_now.id
        });


        var button = $(e.target);
        var html = button.html();
        var background = button.css('background-image');

        button.html(Wait.img());
        button.css({'background-image':'none'});
        
        $.post(
            this.url_save,
            params_send,
            function(json){
                if (json.status == 1){
                    button.parent().find(".vote_num").html(json.result);
                    me.adapter_setParams(me.image_now.id, {vote_plus : json.vote_plus, vote_minus : json.vote_minus});
                }
                button.html(html);
                button.css({'background-image':background});
            },
            "json"
        );
    },
    
    //сохранение параметров голосования во внешнем модуле
    adapter_setParams : function(id, params){
        if (Fotosarea) {
            Fotosarea.setImageParams(id, params);
        }
        else {
            c('M_voting.adapter_setParams: нет объекта Fotosarea');
        }
    }
}