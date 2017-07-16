var runOnLoad = {}

runOnLoad.funcs = [];//массив функций, которые должны быть вызваны после загрузки документа

runOnLoad.loaded = false;//функции еще не запускались

//запускает все зарегистрированные функции в порядке их регистрации.
//допускается вызвать runOnLoad.run() более одного раза: повторные вызовы игнорируются. 
// Это позволяет вызвать runOnLoad из функций инициализации для регистрации других функций.
runOnLoad.run = function() {
	if (runOnLoad.loaded)
		return;//если функция уже запускалась, ничего не делать

	for (var i = 0; i < runOnLoad.funcs.length; i++) {
		try {
			runOnLoad.funcs[i]();
		}
		catch (e) {
			//исключение, возникшее в одной из функций, не должно делать невозможным 
			// запуск оставшихся
		}
	}
	
	runOnLoad.loaded = true;
	delete runOnLoad.funcs;
	delete runOnLoad.run;
}

//зарегистрировать run как обработчик
if (window.addEventListener)
	window.addEventListener("load", runOnLoad.run, false);
else if (window.attachEvent) 
	window.attachEvent("onload", runOnLoad.run);
else
	window.onload = runOnLoad.run;
	
	
/*
//
runOnLoad.funcs[0] = function(){
	alert(33);
}*/