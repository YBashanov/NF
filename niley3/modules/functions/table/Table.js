//часть модуля table
//13.08.12
if (! Table){
	var Table = new Object();
}

//thisObject - данный td, над которым мышь
Table.overtd = function (classN, this_i, this_j, count_tr) {
	Table.elementsTr = document.getElementsByClassName(classN+"tr"+this_i);
	Table.elementsTd = document.getElementsByClassName(classN+"td"+this_j);
	
	for (Table.x = 0; Table.x < Table.elementsTr.length; Table.x++) {
		Table.elementsTr[Table.x].id = "active";
	}
	for (Table.x = 0; Table.x < Table.elementsTd.length; Table.x++) {
		Table.elementsTd[Table.x].id = "active";
	}
	
	
	Table.elementsThis = document.getElementsByClassName(classN+"tr"+this_i+"td"+this_j);
	//doubleactive
	if (this_j == 0)
		//первый столбец - не выделяем его doubleactive
		Table.elementsThis[0].id = "active";
	else
		Table.elementsThis[0].id = "doubleactive";
	
}
//убираем мышь с ячейки
Table.outtd = function (classN, this_i, this_j, count_tr, style_1td) {
	Table.elementsTr = document.getElementsByClassName(classN+"tr"+this_i);
	Table.elementsTd = document.getElementsByClassName(classN+"td"+this_j);
	
	for (Table.x = 0; Table.x < Table.elementsTr.length; Table.x++) {
		Table.elementsTr[Table.x].id = "noactive";
	}
	for (Table.x = 0; Table.x < Table.elementsTd.length; Table.x++) {
		Table.elementsTd[Table.x].id = "noactive";
	}
	
	//первый столбец - строки разные, столбец = 0
	//сохраненный стиль столбца
	Table.elementsTd0 = document.getElementsByClassName(classN+"tr"+this_i+"td0");
	Table.elementsTd0[0].id = style_1td;
}