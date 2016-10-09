if (! Error) var Error = {
	add : function(text, file){
		if (file == undefined) file = false;
		a(text);
		HTTP.post (
			"http://"+server+"/error/listen.php",
			{
				"text":text,
				"file":file
			},
			function (data){}
		);
	},
	clear : function(){
		var parent = document.getElementById("_clearAllErrors");
		var html = parent.innerHTML;
		parent.innerHTML = "Подождите...";
		
		HTTP.post (
			"http://"+server+"/error/clear.php",
			{},
			function (data){
				var res = data.split("|");
				if (res[0] == 1){
					parent.innerHTML = "<span class='green'>Успешно</span>";
				}
				else if (res[0] == 2){
					parent.innerHTML = "<span class='red'>Не вышло</span>";
				}
				else {
					Error.add(data, "Error.js");
				}
			}
		);
	},
	
	//запомнить значения (содержимое) группы объектов ошибок (сохраняет html)
	// !!! использует jquery !!! 
	memory : {},
	//name - имя группы, модуля, плагина, раздела (под которым запоминаем эти ячейки)
	//array - массив элементов, в которые мы выводим ошибки
	remember : function(name, array){
		if (name == undefined) {
			alert("Error.remember: объявите имя группы");
			return false;
		}
		if (array) {
			if (Error.memory[name]) {
				//alert("Error.remember: такое имя группы уже задано: "+ name);
				return false;
			}
			else {
				Error.memory[name] = {};
				Error.memory[name].html = {};
				Error.memory[name].elements = {};
				Error.memory[name].length = array.length;
			}
    
			//запомнить
			for (var i=0; i<array.length; i++){
				Error.memory[name].html[i] = array[i].html();
				Error.memory[name].elements[i] = array[i];
			}
		}
		else {
			alert("Error.remember: не задан массив группы");
			return false;
		}
	},
	
	//возврат начальных значений html из выбранной группы
	reset : function(name){
		if (name == undefined) {
			alert("Error.reset: объявите имя группы");
			return false;
		}
    
		if (! Error.memory[name]) {
			alert("Error.remember: под данным именем группы нет сохранений: "+ name);
			return false;
		}
    
		for (var i=0; i<Error.memory[name].length; i++){
			Error.memory[name].elements[i].html(Error.memory[name].html[i]);
		}
	}
}
else {
    alert("Объект Error уже где-то используется. Ошибка!");
}