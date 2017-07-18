var Search = {
	//объект изменяет url, а что дальше - не его забота
	
	//статьи
	usingCalendar : function(day, month, year){
		//очистка URL
		var newURL = "";
		if (get) {
			for (var key in get) {
				if (
					key !== "searchTime"
				) {
					if (get[key] != "")
						newURL += key+"="+get[key]+"&";
				}
			}			
		}
		var suffix = "";

		month = month-1;
		
		var date = new Date();
		date.setDate(day);
		date.setMonth(month);
		date.setFullYear(year);
		date.setHours(0);
		date.setMinutes(0);
		date.setSeconds(0);
		var time = Math.floor(date.getTime()/1000);

		if (time != 0) 	suffix += "searchTime="+time+"&";

		location.href = "http://"+server+"/search/?"+newURL + suffix;
	}
}