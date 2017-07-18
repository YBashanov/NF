/* 
2014-07-01
Библиотека, расширяющая возможности Галерея Гармошка
Собрание функционала clickCallback

Необходимые классы:
- Gallery_p_harm
- 
*/
if (! Gallery_p_harm_L) var Gallery_p_harm_L = {};


//возвращает путь до большой картинки 
//в перспективе надо первую строчку убрать, т.к. в ней путь берется со строго определенной структуры элементов
Gallery_p_harm_L.getImgName_fromElement = function(element){
	var fullpath = element.childNodes[0].childNodes[0].src; //убрать, заменить. Определять 1й путь вне функции

	var slashes = fullpath.split("/");
	var points = slashes[slashes.length-1].split(".");
	var downspaces = points[points.length-2].split("_");
	var name = downspaces[1];
	var ext = points[1];
	
	var path = "";
	for (var i=0; i<slashes.length-1; i++) {
		path += slashes[i] + "/";
	}
	path += name + "." + ext;
	
	return path;
}




//objThis - экземпляр объекта Gallery_p_harm
//element - элемент, по которому был клик
//необходимо установить id div-a, куда вернется большая фотография
Gallery_p_harm_L.click_idBigFoto = function (objThis, element){
	if (element == undefined) element = objThis.firstElement;
	//
	var parent = objThis.settings.idBigFoto;
	Wait.imgBar_center(parent);
	var path = Gallery_p_harm_L.getImgName_fromElement(element);
	parent.innerHTML = "<img src='"+path+"' />";
}



//используем окно popup, куда располагаем большую фотографию и минигалерею под ней.
//прокрутка благодаря скроллу и стрелкам влево-вправо
//Требуется:
// - HTTP
// - Wait
Gallery_p_harm_L.galleryProp = {}; //сохранение состояний галерей. Требуется также добавить сюда зависимость от номера галереи!!!
Gallery_p_harm_L.galleryInPopup = function(objThis, element){
	//при клике вызывается окно popup
	var popup = document.getElementById("popup");
	var parentdiv = document.getElementById("block_popup_content");

	//сохранить предыдущее состояние
	var html = parentdiv.innerHTML;
	
	//отображаем всё на экране
	popup.style.display = "block";

	//получаем id строки базы
	var id = element.childNodes[0].childNodes[0].id;
	
	
	if (! Gallery_p_harm_L.galleryProp[id]) {
		Gallery_p_harm_L.galleryProp[id] = {};
		Gallery_p_harm_L.galleryProp[id].id = id;
		Gallery_p_harm_L.galleryProp[id].html = "";
		
		parentdiv.style.height = "10px";
		Wait.imgBar(parentdiv);
		HTTP.post (
			"http://"+server+"/ajax/listen/modules/galleryInPopup_show.php",
			{
				"id":id
			},
			function (data){
				var res = data.split("|");
				if (res[0] == 1) {
					Wait.imgBarStop(parentdiv);
					parentdiv.style.height = "auto";
					parentdiv.innerHTML = res[1];
					Gallery_p_harm_L.galleryProp[id].html = res[1];
				}
				else if (res[0] == 2) {
					parentdiv.innerHTML = "<span class='red'>"+res[1]+"</span>";
					Popup.close();
				}
				else Error.add(data);
			}
		);
	}
	else {
		parentdiv.innerHTML = Gallery_p_harm_L.galleryProp[id].html;
	}
}

















