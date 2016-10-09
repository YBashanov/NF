/* 
2014-01-15
2014-06-15
2 типа:
1. табличное голосование (table)
	такое, в котором отмечается, сколько накликано по тому или иному "очку-маркеру"
	очки не суммируются, суммируется именно вес кликов по данному очку

2. суммируемое голосование (summa). 
	такое, в котором суммируются все очки. Сколько накликали, таков и будет результат
	Неравенство проверки НЕ СТРОГОЕ!
*/

var Voting = function(){}

Voting.prototype = {
	//numberVote - номер голосования
	//parameters - строка вида "16-30|31-40|..." (для типа summa)
	setParameters : function(numberVote, type, parameters) {
		if (type == undefined) type = "table";
		if (parameters == undefined) parameters = "";

		this.vals = {};//сохранение результатов табличного голосования
		this.numberVote = numberVote;
		this.type = type;
		this.parameters = parameters;
	},
	
	start : function () {
		$('.radio_0_'+this.numberVote).slideUp(); 
		$('.radio_1_'+this.numberVote).slideDown();	
	},
	
	//id - крайний класс (блок) с вопросом вида radio_349
	finish : function (id) {
		var key = this.change_result();
		$('.'+id+'_'+this.numberVote).slideUp();
		$('.finish_'+key+"_"+this.numberVote).slideDown();
		
		this.resultToServer(key);
	},
	
	//анализ результата
	change_result : function(){
		var testvalue = 0;
		var key = 0;
		
		if (this.type == "table") {
			//выделение из общего массива результатов наиболее весомого 
			for (var val in this.vals) {
				if (testvalue < this.vals[val]) {
					testvalue = this.vals[val];
					key = val;
				}
			}
		}
		else if (this.type == "summa") {
			this.vals[0];
			
			var variants = this.parameters.split("|");
			var intervals = {};

			for (var val in variants) {
				intervals[val] = variants[val].split("-");
			}
			for (val in intervals) {
				if (intervals[val][0] <= this.vals[0] && this.vals[0] <= intervals[val][1]) {
					key = parseInt(val) + 1;
					break;
				}
			}
		}
a(this.vals[0]);
		//возвращает уже конкретный номер div-a, который следует показать (с результатом)
		return key;
	},
	
	//выбор выбранного radio и сохранение значения
	change_radio : function (id){
		var input_collect = $('[name = "'+id+'_'+this.numberVote+'"]');;
		var check = false;
		var val = false;

		for ( i=0; i<input_collect.length; i++ ){
			check = input_collect[i].checked;
			if ( check == true ) {
				val = input_collect[i].value;
			}
		}

		if ( val ) {
			if (this.type == "table") {
				this.save_table (val);
			}
			else if (this.type == "summa") {
				this.save_summa (val);
			}
			$('.'+id+'_'+this.numberVote).slideUp();
			var new_id = this.transform(id);
			$('.'+new_id+'_'+this.numberVote).slideDown(); 
		}
		else {
			alert('Ошибка: Выберите один из вариантов');
		}
	},
	
	//увеличить значение class на 1 для перехода к след. блоку с вопросом и ответами
	transform : function(id){
		var number = id.substr(6, id.length-6);
		number++;
		new_id = 'radio_'+number;
		return new_id;
	}, 
	
	//увеличить значение одной из ячеек (тип - таблица)
	save_table : function (key){
		if (this.vals[key] == undefined) {
			this.vals[key] = 1;
		}
		else {
			this.vals[key]++;
		}
	},
	
	//прибавить число (тип - сумма)
	save_summa : function (val){
		if (this.vals[0] == undefined) this.vals[0] = 0;
		this.vals[0] += parseInt(val);
	},
	
	//клик по тексту. Команда для radio - стать выбранным
	change_this_radio : function (i, iterator, vote_number){
		var thisradio = $("#rb_"+i+"_"+iterator+"_"+vote_number);
		thisradio.attr('checked', 'checked');
	},
	
	//отправка результатов на сервер
	//в типе summa на сервер посылается не сумма, а тип результата. Он тоже может быть числом от 1 до 9..
	//анализ проводится в функции change_result
	resultToServer : function (key){
		$.post(
			'http://'+server+'/ajax/listen/modules/voting_save.php',
			'numberVote=' + this.numberVote + '&key=' + key,
			function(data){},
			'html'
		);
	}
}




