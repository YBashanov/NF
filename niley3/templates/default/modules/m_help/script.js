/*
Help - окно отображается сбоку

некорректно работает в Safari: куки с запятыми не записываются
*/

if (! Help) var Help = {
	buttonClick : false,
	parent : false,
	arrow : false,
	open_status : false,
	interval : false,
	age : 120, //2 минуты
	
	start : function(id){
		if (id == undefined) id = "help";
		this.parent = $("#"+id);
		this.parent.css({
			"left": "-170px"
		});
		
		this.arrow = $("#arrow");
		var thisObj = this;
		this.arrow.on("click", function(){thisObj.clicks()});
		
		if (this.getCloseCookie() == false) {
			this.setCookie();
		}
	},
	
	clicks : function() {
		if (this.open_status == false) {
			this.open_status = true;
			this.parent.animate({"left":"0px"});
			
			if (this.getCloseCookie() == false) {
				this.setCloseCookie();
				if (this.interval) {
					clearInterval(this.interval);
				}
			}
			this.arrow_replace();
		}
		else if (this.open_status == true) {
			this.open_status = false;
			this.parent.animate({"left":"-170px"});
			this.arrow_replace();
		}
	}, 
	
	arrow_replace : function() {
		if (this.open_status == true) {
			$("#arrowOpen").css({"display":"none"});
			$("#arrowClos").css({"display":"block"});
		}
		else if (this.open_status == false) {
			$("#arrowOpen").css({"display":"block"});
			$("#arrowClos").css({"display":"none"});
		}
	},
	
	setCookie : function(){
		var thisObj = this;

		var date = new Date();
		var s_datenow = date.toUTCString();

		Cookie.set_cookie("contrtime", s_datenow);
		var s_contrtime = Cookie.get_cookie("contrtime");
		var i=0;//Safari
		
		thisObj.interval = setInterval(function(){
			var date2 = new Date();
			s_datenow = date2.toUTCString();

			var seconds = Date.subtract(s_datenow, s_contrtime);
			if (seconds) {
				if (seconds > thisObj.age) {
					clearInterval(thisObj.interval);
					thisObj.interval = false;
					thisObj.clicks();
				}
			}
			//Safari
			else {
				i++;
				if (i > thisObj.age/2) {
					clearInterval(thisObj.interval);
					thisObj.interval = false;
					thisObj.clicks();
				}
			}
		}, 1000);
	},
	
	setCloseCookie : function() {
		Cookie.set_cookie("contrtime_is", "1");
	},
	
	getCloseCookie : function() {
		return Cookie.get_cookie("contrtime_is");
	},
	
	
	timeout_error : false,
	//отправить
	send : function(){
		if (Help.buttonClick == false) {
			Help.buttonClick = true;
			
			var parent 	= $("#help_parent");
			var name 	= $("#help_name");
			var mail 	= $("#help_mail");
			var phone 	= $("#help_phone");
			var nameErr = $("#help_nameErr");
			var mailErr = $("#help_mailErr");
			var phoneErr= $("#help_phoneErr");
			
			Error.remember("help", [nameErr, mailErr, phoneErr]);
			
			if (Help.timeout_error) {
				Error.reset("help");
				clearTimeout(Help.timeout_error);
				Help.timeout_error = false;
			}
			
			var sendTrue = true;
			
			var nameText = Regular.ext(name.val());
			var mailText = Regular.mail(mail.val());
			var phoneText = Regular.ext(phone.val());

			Language.setSStyles("color:#f00");
			
			if (name.val()){
				if (nameText == false) {
					sendTrue = false;
					nameErr.html(Language.getText("SymbolsNot"));
				}
			}
			else {
				sendTrue = false;
				nameErr.html(Language.getText("FieldEmpty"));
			}
			
			
			if (mail.val()){
				if (mailText == false) {
					sendTrue = false;
					mailErr.html(Language.getText("FormatMailNot"));
				}
			}
			else {
				sendTrue = false;
				mailErr.html(Language.getText("FieldEmpty"));
			}
			
			
			if (phone.val()){
				if (phoneText == false) {
					sendTrue = false;
					phoneErr.html(Language.getText("SymbolsNot"));
				}
			}
			else {
				sendTrue = false;
				phoneErr.html(Language.getText("FieldEmpty"));
			}
			
			if (sendTrue) {
				HTTP.post (
					"http://"+server+"/templates/default/modules/m_help/ajax/help_message_send.php",
					{
						"name":nameText,
						"phone":phoneText,
						"mail":mailText
					},
					function (data){
						var res = data.split("|");
						if (res[0] == 1) {
							var html_parent = parent.html();
							parent.html(res[1]);
							
							setTimeout(function(){
								Help.clicks();
								parent.html(html_parent);
							}, 3000);
							
							Help.buttonClick = false;
						}
						else if (res[0] == 2) {
							var html_parent = parent.html();
							parent.html(res[1]);
							setTimeout(function(){
								parent.html(html_parent);
							}, 3000);
							
							Help.buttonClick = false;
						}
						else {
							Error.add(data, "m_help/script.js");
							Help.buttonClick = false;
						}
					}
				);
			}
			else {
				Help.timeout_error = setTimeout(function(){
					Error.reset("help");
					Help.timeout_error = false;
				}, 3000);
				
				Help.buttonClick = false;
			}
		}
	}
}












