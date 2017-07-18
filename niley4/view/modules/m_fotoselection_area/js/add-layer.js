if (! Fotosarea) var Fotosarea = {};

Fotosarea.add_layer = function(){
    var target = $('#target');
    
    //f(target.parent().children()[1]);
    
    //если есть выделенные границы (т.е. если мы выбрали элемент)
    if (target[0]) {
        // var backg = target.parent().children()[2];
        // 
        // $(backg).css({
        //     "width" : parseInt(target.css("width")), 
        //     "height" : parseInt(target.css("height"))
        // });
        // this.last_backg = backg;
        var myclass = $('.jcrop-tracker').parent().children();

        var mytest = $("<div class='mytest'>");
        $(myclass).append(mytest);
        
        
        
        //f(myclass[4], 'string');//img

    }
}