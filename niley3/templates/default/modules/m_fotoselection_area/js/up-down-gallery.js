if (! Fotosarea) var Fotosarea = {};
Fotosarea.memoryParams = {page : 0};
Fotosarea.totalHeight = 0;
Fotosarea.totalHeightToLoad = 0;
Fotosarea.goLoad = true;

Fotosarea.scrollAccess = true; //проверка, один раз скролл мы пропускаем (это особенность реализации, есть небольшой косячок, когда скроллинг проматывается немного вниз)

Fotosarea.startUDGallery = function(){
	Fotosarea.parent = $('#slider');
	Fotosarea.body = $('body');
    //создадим холст (div), куда будем помещать картинки
    Fotosarea.canvas = $("<div id='canvas'>");
    Fotosarea.parent.append(Fotosarea.canvas);
    
    Fotosarea.body.scroll(Fotosarea.start_animate);
}
Fotosarea.clearCanvas = function(){
    var canvas = Fotosarea.canvas;
    if (canvas) {
        canvas.html("");
        Fotosarea.totalHeight = 0;
    }
    Fotosarea.images = {};
    Fotosarea.heights = [];
}

Fotosarea.waitShow = function(){
    var leftEdge = (getWindowSize()[0] - 1200)/2;
    var topHalf = getWindowSize()[1]/2;
    var div = $("<div style='position:fixed; top:"+(topHalf-30)+"px; left:"+(leftEdge+570)+"px'>");
    Fotosarea.parent.append(div);
    Fotosarea.wait = div;
    div.html(Wait.img(60));
}
Fotosarea.waitHide = function(){
    if (Fotosarea.wait) {
        Fotosarea.wait.remove();
    }
}



Fotosarea.simple = function(){
    Fotosarea.memoryParams = {
        page : 0,
        i : 0
    }
    Fotosarea._sendPost();
}
//выбор Тематики или Помещения
Fotosarea.sort = function(table, id){
    Fotosarea.clearCanvas();
    //при каждом выборе выставлять картинки в начало
    Fotosarea.body.stop().animate({'scrollTop': 0});
    
    Fotosarea.memoryParams = {
        page : 0,
        i : 0,
        table : table,
        id : id
    }
    Fotosarea.is_div_up = false;
    Fotosarea._sendPost();
}
//адаптер для поиска по тексту - вызывается из внешнего модуля
Fotosarea.search = function(params, callback){
    Fotosarea.memoryParams = {
        page : 0,
        i : 0,
        text : params.text
    }
    Fotosarea.is_div_up = false;
    Fotosarea._sendPost({outerCallback : callback, type : "search"});
}

Fotosarea._sendPost = function(params){
    if (params == undefined) params = {};
    Fotosarea.waitShow();

    Functs.post(
        Fotosarea.get_images,
        Fotosarea.memoryParams,
        function(data){

            Fotosarea.waitHide();

			//если больше после прокрутки ничего не возвращается - принудительно ставим goLoad = false
			if (data.length == 0) {
				Fotosarea.goLoad = false;
			}
			else {
                if (data.data) {
                    if (data.data.length > 0) {
                        if (params.type == "search") {
                            Fotosarea.clearCanvas();
                            Fotosarea.body.stop().animate({'scrollTop': 0});
                        }
                        Fotosarea._afterPost(data.data);
                    }
				}
			}
            
            //для внешнего модуля
            if (params)
            if (params.outerCallback){
                params.outerCallback(data);
            }
        }
    );
}



Fotosarea.is_div_up = false;
//расставит все фотографии вертикально внутри созданного тут же длинного холста
Fotosarea._afterPost = function (images){
    var canvas = Fotosarea.canvas;

    //поместим все картинки на холст
    var div;
    var div_up;
    var div_preload;
    var div_bg;
    var div_title;
    var image;
    var totalHeight = Fotosarea.totalHeight;
    var height = 0;
    
    for(var i=0; i<images.length; i++) {
//a(images[i]);
        if (images[i].d_file && images[i].file_ext) {
            div         	= $("<div class='block'>");
            if (Fotosarea.is_div_up==false) div_up = $("<div class='div_up'>");
            image       	= $("<img class='img' src='"+Fotosarea.img_target + images[i].d_file +"."+ images[i].file_ext +"' />");
            div_title   	= $("<div class='text'>");
            div_title.html(images[i].text);
			div_download   	= $("<div class='download'>");
            div_download.html("<a target='_blank' href='"+Fotosarea.img_target + "image_download.php?file="+images[i].original_file +"&ext="+ images[i].file_ext +"'><img src='"+Fotosarea.my_path+"image/download.png'/></a>");
			div_cle   		= $("<div class='cle'>");
            if (Fotosarea.is_div_up==false) {
                div.append(div_up);
                Fotosarea.is_div_up = true;
            }
            div.append(image);
            div.append(div_title);
            div.append(div_download);
            div.append(div_cle);
            canvas.append(div);

            height = parseInt(images[i].height);
            totalHeight += height;


			image.on('click', Fotosarea.listener_click);

            //вычисление высоты каждого блока - для дальнейшего ровного скролла
            Fotosarea.heights[Fotosarea.memoryParams.i] = 0;
            Fotosarea.heights[Fotosarea.memoryParams.i] += (height + Fotosarea.divText); //- это высота div-a, вмещающего картинку и надпись (надпись = 60)
            Fotosarea.heights[Fotosarea.memoryParams.i] += parseInt(div.css('margin-top'));
            Fotosarea.heights[Fotosarea.memoryParams.i] += parseInt(div.css('margin-bottom'));
            Fotosarea.heights[Fotosarea.memoryParams.i] -= 4; //два лишних border, которые вешаются скриптом
			Fotosarea.memoryParams.i++;

			var totScroHeight = 0;
			for(var j=0; j<Fotosarea.memoryParams.i; j++) {
				totScroHeight += Fotosarea.heights[j];
			}

            image.data({
				"id":images[i].id, 
				"scroll":(totScroHeight - Fotosarea.heights[j-1]), 
				"articul":images[i].id, 
				"vote_plus" : images[i].vote_plus, 
				"vote_minus": images[i].vote_minus
			});
            var key = images[i].id + "_id";
            Fotosarea.images[key] = image;
        }
    }

    //установим холсту высоту
    canvas.css({"height" : totalHeight});
    Fotosarea.totalHeight = totalHeight;
    Fotosarea.totalHeightToLoad = totalHeight - height;

    Fotosarea.goLoad = true;
}



//клик по картинке
Fotosarea.listener_click = function(e){
    var data = Fotosarea.getImage($(e.target).data().id).data();

    var scrollHeight = parseInt(data.scroll);
    
	Fotosarea.body.stop().animate({'scrollTop': (scrollHeight + (Fotosarea.divUp - Fotosarea.scrollDelta))}, Fotosarea.milliseconds, "swing", function(){
		//отобразить область
		Fotosarea.draw_area(e);
        
        
        Fotosarea.scrollAccess = false;
        
		//добавить артикул
        if (Articul)
            Articul.set(data.articul);
        
        //добавить голосования
        if (M_voting) {
            M_voting.set(data);
            M_voting.coloring(true);
        }
	});
}
	
//отобразить выделение области
Fotosarea.draw_area = function(e) {
	if (Fotosarea.lastElement) {
		Fotosarea.jcrop_api.destroy();	
		$(Fotosarea.lastElement).removeAttr("id");
        
		$(Fotosarea.lastElement).attr("src", Fotosarea.lastSrc);
		
        //уменьшаем возможный слой backg, если он есть
        if (Fotosarea.last_backg) {
            $(Fotosarea.last_backg).css({
                "width" : 0, 
                "height" : 0
            });
            Fotosarea.last_backg = null;
        }
	}
	Fotosarea.lastElement = e.target;
	Fotosarea.lastSrc = e.target.src;
	$(Fotosarea.lastElement).attr("id", "target");

	Fotosarea.create_selected_area();
}
	
//если пошла анимация после остановки (метод на перспективу) - снять выделение области
Fotosarea.start_animate = function(e) {
    var totalHeight = Fotosarea.totalHeightToLoad;
    var scrollTop = Fotosarea.body.scrollTop();
    if (scrollTop > totalHeight && Fotosarea.goLoad){
        if (Fotosarea.goLoad) {
            Fotosarea.goLoad = false;
            Fotosarea.memoryParams.page++;
            Fotosarea._sendPost();
        }
    }
    
    if (Fotosarea.scrollAccess == true) {
        //автоматически обновляем голосование при прокрутке
        var i = Fotosarea.getImageI(scrollTop);
        var data = Fotosarea.getImageToI(i).data();

        if (M_voting) {
            M_voting.set(data);
            M_voting.coloring(false);
        }

        if (Articul) {
            var articul = 0;
            if (data.articul) articul = data.articul;
            Articul.set(articul);
        }
    }
    else {
        Fotosarea.scrollAccess = true;
    }
    
    if (Cost)
        Cost.reset();
}



//получить порядковый номер фотки из массива Fotosarea.heights, по признаку scrollTop
Fotosarea.getImageI = function(scrollTop){
    if (Fotosarea.heights) {
        var go = true;
        var i = 0;
        var delta = -200;//запас по высоте, смещение координаты прокрутки, чтобы фотка в центре меняла голосование
        var sum = delta;
        
        if (Fotosarea.heights[0]) {
            while(go) {
                if (Fotosarea.heights[i]) {
                    sum += Fotosarea.heights[i];
                    i++;

                    if (sum >= scrollTop) {
                        go = false;
                    }
                }
                else go = false;
            }
            return (i-1);
        }
    }
    return false;
}
//получить изображение по порядковому номеру в массиве
Fotosarea.getImageToI = function(i) {
    var j=0;
    var image = {};

    for (var val in Fotosarea.images) {
        if (j >= i) {
            image = Fotosarea.images[val];
            break;
        }
        j++;
    }
    return image;
}



//для адаптеров ----------------------
Fotosarea.getImage = function(id){
    var key = id + "_id";
    return Fotosarea.images[key];
}
//сохранение параметров голосования в этом модуле
Fotosarea.setImageParams = function(id, params){
    if (params == undefined) params = {};
    var key = id + "_id";
    Fotosarea.images[key].data(params);
}
//заменить картинку на ребристый холст
Fotosarea.setBack = function(src){
	if (Fotosarea.lastElement) {
		$(".jcrop-holder > img.img").attr({
			"src" : "http://"+server+"/uploads/fotos_canvas/"+src,
			"opacity" : "0.5"
		});
		(".jcrop-handle").attr("opacity","1");
	}
}
// -----------------------------------















