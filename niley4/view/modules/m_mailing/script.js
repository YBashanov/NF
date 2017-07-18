var Mailing = {
	wait : "ждите...",
	path : "http://"+server+"/templates/default/modules/m_mailing/ajax/",
	buttonOrig : "<div class='pen_text' id='pen_text' onclick='Mailing.show()'>Подписаться на рассылку</div><div class='pen_img'></div>",
	origHtml : "",
	
	show : function() {
		var $mailing = $("#mailing");
		Mailing.origHtml = $mailing.html();

		$mailing.html(Mailing.wait);
		
		HTTP.post (
			Mailing.path + "show.php",
			{},
			function (data){
				var res = data.split("|");
				if (res[0] == 1) {
					$mailing.html(res[1]);
				}
				else if (res[0] == 2) {
					$mailing.html("<span class='red'>"+res[1]+"</span>");
				}
				else Error.add(data);
			}
		);
	},

	save : function() {
		var $mailing = $("#mailing");
		var $mail = $("#mailing_mail");

		var mailText = Regular.mail($mail.val());

		if (! $mail.val()) {
			$mailing.html(Language.getText("FieldEmpty"));
			setTimeout(function (){
				$mailing.html(Mailing.origHtml);
			}, 3000);
			return false;
		}
		else if (mailText == false) {
			$mailing.html(Language.getText("FormatMailNot"));
			setTimeout(function (){
				$mailing.html(Mailing.origHtml);
			}, 3000);
			return false;
		}

		$mailing.html(Mailing.wait);
		
		HTTP.post (
			Mailing.path + "save.php",
			{
				"mail": mailText
			},
			function (data){
				var res = data.split("|");
				if (res[0] == 1) {
					$mailing.html(res[1]);
				}
				else if (res[0] == 2) {
					$mailing.html(res[1]);
				}
				else Error.add(data);
			}
		);
	}
}