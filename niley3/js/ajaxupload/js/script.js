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

//id - айди строки в одной из таблиц
//table - sections, products, ...
//type - model2d, model3d, image, ...
function uploadReady(id, table, type, idParent) {
//a(id + "-" + table+ "-" + type+ "-" + idParent);
	if (idParent == undefined) idParent = 0;

	var img_load = "http://"+server+"/templates/default/image/wait.gif";
	if (
		table == "foto"||
		table == "bannery"||
		table == "productmonth"||
		table == "magazines"
	) {
		var button_sections = $("#uploadButton"+idParent+"_"+id);
		var html = button_sections.html();
		var error = $("#uploadButton_Err"+idParent+"_"+id);

		var uploadFile = "";
		if (type == "pdf"){
			var a_id = id.split("_");
			id = a_id[0];
			uploadFile = "http://"+server+"/ajax/listen/pdfUpload.php";
		}
		else if (type == "cover"){
			var a_id = id.split("_");
			id = a_id[0];
			uploadFile = "http://"+server+"/ajax/listen/imageUpload.php";
		}
		else error.html("");
	}
	else{
		Error.add("Такой таблицы нет среди разрешенных", "script.js");
		return false;
	}

	$.ajax_upload(button_sections,
		{
			action : uploadFile,
			name : 'up_foto',
			data : {
				"idParent" : idParent,
				"id" : id,
				"table" : table,
				"type" : type
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
if ( messagesTrue ){
alert(response);
}				
				error.html("<span class='green'>"+response+"</span>");
				if (
					table == "foto"||
					table == "bannery"||
					table == "productmonth"
				) {
					setTimeout(function(){
						Foto.open_Sec();
					}, 2000);
					
					if (type == "excel"){
						Korzina.rekvizitiTrue = 1;
						Korzina.rekvizitiHide("none");
						error.html("Вы можете загрузить файл Excel");
					}
				}
			},
			onError:function(file, response) {
				button_sections.html(html);
				this.enable();
				response = delete_amp(response, 'amp;');
if ( messagesTrue ){
alert(response);
}
				error.html("<span class='red'>"+response+"</span>");
				if (
					table == "foto"||
					table == "bannery"||
					table == "productmonth"||
					table == "magazines"
				) {
					
				}
			}
		}
	);
}