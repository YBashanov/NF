if (! Fotosarea) var Fotosarea = {};
Fotosarea.jcrop_api;
Fotosarea.x1;
Fotosarea.y1;
Fotosarea.x2;
Fotosarea.y2;

// x1, y1, x2, y2 - Координаты для обрезки изображения
// var x1, y1, x2, y2; 
// var Fotosarea.jcrop_api;

Fotosarea.create_selected_area = function(){
	var width = $('#target').width();
	var height = $('#target').height();
    var mode = "free"; //ratio (соотношение)
    
    //применение сохраненных ранее установок на другом изображении
    var setSelect = [];
    if (Fotosarea.mode == "ratio") {
        setSelect = [Fotosarea.c.x, Fotosarea.c.y, Fotosarea.c.x2, Fotosarea.c.y2];
        mode    = Fotosarea.mode;
        width   = Fotosarea.c.w;
        height  = Fotosarea.c.h;
    }
    else {
        setSelect = [0, 0, width, height];
    }

	$('#target').Jcrop(
		{
			setSelect: setSelect,
			onChange:   showCoords,
			onSelect:   showCoords
		},
		function(){
			Fotosarea.jcrop_api = this;
			setSize(width, height);
		}
	);

	function setSize(w, h) {
		if (w && h) {
			var quotWH = w/h;
		}
        
Fotosarea.mode = mode;

        if (mode == "ratio") {
            Fotosarea.jcrop_api.setOptions({aspectRatio : quotWH });
        }
        else if (mode == "free") {
            Fotosarea.jcrop_api.setOptions({aspectRatio : 0 });
        }
	}
	// Снять выделение
    $('#release').click(function(e) {
		Fotosarea.release();
    }); 

	$('#size_w').keyup(function(e){
		var w = e.target.value;
		var h = $('#size_h').val();
        
        if (w && h)
            mode = "ratio";
        else
            mode = "free";
        
		setSize(w, h);
	});
	$('#size_h').keyup(function(e){
		var w = $('#size_w').val();
		var h = e.target.value;
        
        if (w && h)
            mode = "ratio";
        else
            mode = "free";
            
		setSize(w, h);
	});

	// Изменение координат
	function showCoords(c){
		Fotosarea.x1 = c.x; $('#x1').val(c.x);		
		Fotosarea.y1 = c.y; $('#y1').val(c.y);		
		Fotosarea.x2 = c.x2; $('#x2').val(c.x2);		
		Fotosarea.y2 = c.y2; $('#y2').val(c.y2);
        
Fotosarea.c = c;

		//$('#size_w').val(c.w);
		//$('#size_h').val(c.h);
		
		if(c.w > 0 && c.h > 0){
			$('#crop').show();
		} else{
			$('#crop').hide();
		}
	}	
}

Fotosarea.release = function(){
	Fotosarea.jcrop_api.release();
	Fotosarea.jcrop_api.destroy();
	$('#crop').hide();
}

// Обрезка изображение и вывод результата
Fotosarea.setSendListener = function(){
	$('#crop').click(function(e) {
		var img = $('#target').attr('src');
		var getdata = $('#target').data();
//a(getdata);
        $('#cropresult').html(Wait.img());
		Functs.post(
			Fotosarea.file_action,
			{
				'x1': Fotosarea.x1, 'x2': Fotosarea.x2, 'y1': Fotosarea.y1, 'y2': Fotosarea.y2, 
				'id': getdata.id
			}, 
			//function(file) {
			function(data) {
//a(data);
				$('#cropresult').html('<img src="'+pagePrefix + Fotosarea.crop + data.file+'">');
			}
		);
		
    });   
}
















