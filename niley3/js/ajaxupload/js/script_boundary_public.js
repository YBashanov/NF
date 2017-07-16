//удаление знаков &
delete_amp = function(string, delete_string){
	var string1;
	var string2;
	//пока есть эти знаки, они уничтожаются
	while ( string.indexOf(delete_string) != -1 ){
		string1 = string.substring(0, string.indexOf(delete_string));
		string2 = string.substring(string.indexOf(delete_string)+delete_string.length);
		string = string1.concat(string2);
	}
	return string;
}
var messagesTrue = false; //открываем и закрываем вспомогательные комментарии (и alert)

/*
id - айди строки в одной из таблиц
cell - ячейка 
table - sections, products, ...
type - simple, one, triple, none, cover
idParent - 
reboot - true/false
rebootFunction - функция перезагрузки (прописывается как статичная переменная)
*/
function uploadReady(id, cell, table, type, idParent, reboot, rebootFunction) {

	if (idParent == undefined) idParent = 0;
	if (reboot == undefined) reboot = false;
	
	var img_load = "http://"+server+"/templates/default/image/wait/wait.gif";
	var button_sections = $("#uploadButton"+idParent+"_"+id+"_"+cell+"_"+table);
	var html = button_sections.html();
	var error = $("#uploadButton_Err"+idParent+"_"+id+"_"+cell+"_"+table);

	var uploadFile = "http://"+server+"/ajax/listen/mediaUpload_boundary_public.php";
	

	$.ajax_upload(button_sections, {
		action : uploadFile,
		name : 'up_foto',
		data : {
			"idParent" : idParent,
			"id" : id,
			"cell" : cell,
			"table" : table,
			"type" : type,
			"language" : language
		},
		onSubmit : function(file, ext) {
			// показываем картинку загрузки файла
			button_sections.html("Ждите <img src='"+img_load+"' width='12'/>");
			this.disable();
		},
		onSuccess: function(file, response) {
			button_sections.html(html);
			this.enable();
			//необходимо вернуть правильный URL, чтобы на странице происходило корректное отображение изменений
			response = delete_amp(response, 'amp;');	
			error.html("<span class='green'>"+response+"</span>");
			if (reboot == true) {
				setTimeout(function(){
					rebootFunction();
				}, 500);
			}
		},
		onError:function(file, response) {
			button_sections.html(html);
			this.enable();
			response = delete_amp(response, 'amp;');
			error.html("<span class='red'>"+response+"</span>");
		}
	});
}