/**
    Класс, в котором будут собиратся обертки для стандартных функций других библиотек
    Расширяет и дополняет функционал других библиотек
*/
if (! Functs) var Functs = {};


/*
    Расширяет классический jquery метод post
    принимает json-объект, тут же проверят на корректность формата json
*/
Functs.post = function(path, params, callback){
    $.post(
        path,
        params,
        function(json){
            var data = {};
            try {
                data = JSON.parse(json);
            }
            catch(e){
                //a(json);
                c('Functs.post: json некорректен, message: '+e.message);
            }
			callback(data);
        },
        "html"
    );
}


