var Line = {
	toArray : function(url){
		if (url) {
			var get = {};
			var a1_url = url.split("&");
			for (var i=0; i<a1_url.length; i++){
				if (a1_url[i]) {
					var a2_url = a1_url[i].split("=");
					get[a2_url[0]] = a2_url[1];
				}
			}
			return get;
		}
		else return false;
	}
}