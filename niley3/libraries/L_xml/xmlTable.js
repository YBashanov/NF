var XmlTable = {
    overtd : function(classN, this_i, this_j) {
        //горизонтальное выделение
        $("."+classN+"tr"+this_i+"").attr({"id":"active"});
        
        if (this_j == 0) {
            //первый столбец - не выделяем его doubleactive
            $("."+classN+"tr"+this_i+"td"+this_j+"").attr({"id":"active"});
        }
        else {
            //вертикальное выделение
            $("."+classN+"td"+this_j+"").attr({"id":"active"});
            //выделение выбранной ячейки
            $("."+classN+"tr"+this_i+"td"+this_j+"").attr({"id":"doubleactive"});
        }
    },
    
    outtd : function(classN, this_i, this_j, style_1td) {
        $("."+classN+"tr"+this_i+"").attr({"id":"noactive"});
        $("."+classN+"td"+this_j+"").attr({"id":"noactive"});
        
        if (this_j == 0) {
            //первый столбец - строки разные, столбец = 0
            $("."+classN+"tr"+this_i+"td"+this_j+"").attr({"id":style_1td});
        }
    }
}