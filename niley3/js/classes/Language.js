/*
v 1.0 (2014-06-10)
*/
var Language = {
	s_styles : "",

	langText : {
		"ru" : {
			"FileLoadNot" 	: "Файл не загружен",
			"FileDeleteQ" 	: "Удалить файл?",
			"FieldEmpty" 	: "Пустое поле",
			"FormatNot" 	: "Неверный формат",
			"FormatMailNot" : "Неверный формат e-mail",
			"FormatMailNotCorrect" : "Некорректный формат e-mail",
			"SymbolsNot" 	: "Неверные символы",
			"SymbolsMin" 	: "Мало символов",
			"SymbolsMax" 	: "Много символов",
			"NumOnly"		: "Только цифры",
			"FloatOnly"		: "Только число",
			"Changes"		: "Выберите",
		},
		"en" : {
			"FileLoadNot" 	: "",
			"FileDeleteQ" 	: "",
			"FieldEmpty" 	: "",
			"FormatNot" 	: "",
			"FormatMailNot" : "",
			"FormatMailNotCorrect" : "",
			"SymbolsNot" 	: "",
			"SymbolsMin" 	: "",
			"SymbolsMax" 	: "",
			"NumOnly"		: "",
			"FloatOnly"		: "",
			"Changes"		: ""
		}
	},
	
	getText : function (key) {
		var text = this.langText[language][key];
		text = "<span style='" + this.s_styles + "'>" + text + "</span>";
		return text;
	},
	
	setText : function (key, value) {
		this.langText[language][key] = value;
	},
	
	//установить строковые стили
	setSStyles : function (str) {
		this.s_styles = str;
	},
	//сброс строковых стилей
	resetSStyles : function(){
		this.s_styles = "";
	}
}