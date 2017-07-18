if (! M_upload) var M_upload = {

    //params = {
    //  [id]    - айди строки в одной из таблиц
    //  [table] - таблица 
    //  [cell]  - имя ячейки, например file 
    //  [type]  - тип загрузчика (pdf, cover)
    //  [idParent] - если загрузка идет в дочернюю таблицу, нам надо знать связь с родительской
    //  [callback] - callback-метод
    //  [error]    - этот callback-метод вызовется, если произошла ошибка
    //}
    start : function(params){
        var me = this;

        if (Error) {}
        else {
            alert(me.thisFile + ': нет вспомогательного класса Error');
            return;
        }
        
        if ( ! $) {
            Error.add(me.thisFile + ": Требуется подключение jquery", me.thisFile);
            return;
        }
        
        if (params == undefined) params = {};
        if (params.id == undefined)     params.id = 0;
        if (params.table == undefined)  params.table = "temp";
        if (params.cell == undefined)   params.cell = "file";
        if (params.type == undefined)   params.type = "cover";
        if (params.idParent == undefined) params.idParent = 0;

        
        
        var button = $("#upButton_"+params.idParent+"_"+params.id);
        if (button) {}
        else {
            Error.add(me.thisFile + ".ready: Нет такой кнопки: " + "#upButton_"+params.idParent+"_"+params.id, me.thisFile);
            return;
        }
        
        var error = $("#upButtonErr_"+params.idParent+"_"+params.id);
        if (error) {}
        else {
            Error.add(M_upload.thisFile + ".ready: Некуда выводить ошибки, нет: " + "#upButtonErr_"+params.idParent+"_"+params.id, M_upload.thisFile);
            return;
        }
        
        var html = button.html();
        
        me._upload(button,
            {
                action : me.uploadFile,
                name : 'up_foto',
                data : {
                    "idParent" : params.idParent,
                    "id"    : params.id,
                    "table" : params.table,
                    "cell"  : params.cell,
                    "type"  : params.type
                },
                onSubmit : function(file, ext) {
                    // показываем картинку загрузки файла
                    button.html("Ждите <img src='"+me.wait+"' width='12'/>");
                    this.disable();
                },
                onSuccess: function(file, response, data) {
                    button.html(html);
                    this.enable();
                    //необходимо вернуть правильный URL, чтобы на странице происходило корректное отображение изменений
                    response = me.delete_amp(response, 'amp;');
			
                    error.html("<span class='green'>"+response+"</span>");

                    if (params.callback == undefined) {}
                    else {
                        params.callback.call(this, data);
                    }
                },
                onError:function(file, response, data) {
                    button.html(html);
                    this.enable();
                    response = me.delete_amp(response, 'amp;');
        
                    error.html("<span class='red'>"+response+"</span>");
                    
                    if (params.error == undefined) {}
                    else {
                        params.error.call(this, data);
                    }
                }
            }
        );
    },
    
    
    //удаление знаков &
    delete_amp : function(string, delete_string){
        var string1;
        var string2;
        //пока есть эти знаки, они уничтожаются
        while ( string.indexOf(delete_string) != -1 ){
            string1 = string.substring(0, string.indexOf(delete_string));
            string2 = string.substring(string.indexOf(delete_string)+delete_string.length);
            string = string1.concat(string2);
        }
        return string;
    },
    
    _upload : function(button, options){
		button = $(button);

		if (button.size() != 1 ){
			Error.add(M_upload.thisFile + "Кнопка загрузки не активирована", M_upload.thisFile);
			return false;
		}
		return new _M_upload(button, options);
	},
    
    _get_uid : function(){
        var uid = 0;
        return function(){
            return uid++;
        }
    }
    
}