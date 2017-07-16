if (! Fotosarea) var Fotosarea = {
    //путь до модуля
    my_path : "http://"+server+"/templates/default/modules/m_fotoselection_area/",

	//папка фоток-исходников
	img_target : "http://"+server+"/uploads/",

	//файл получения имен файлов-фоток из базы данных
	get_images : "http://"+server+"/templates/default/modules/m_fotoselection_area/ajax/get_images.php",

	//путь, ориентированный от ajax-файла action.php, папка для обрезанных изображений
	crop : '../../../../../uploads/fotos_resize/',

	//файл скрипта-резчика фотографии
	file_action : 'http://'+server+'/templates/default/modules/m_fotoselection_area/ajax/action.php',

	//величина шага (высота) для авто-скроллинга при клике (между верхними частями изображений)
	//scrollStepHeight : 0, //определяется автоматически в скрипте
    heights         : [],   //высота каждого блока (высота ведь всегда разная)

	divUp 			: 120, 	//высота верхнего блока
	divText			: 40,	//высота блока, вмещающего надпись (считая и padding)
	divDown 		: 1000,	//высота нижнего блока
	scrollDelta 	: 130, 	//часть, которая отнимается от верхнего блока при скроллинге (отступ сверху)
    width           : 800,  //общая ширина картинок
    milliseconds    : 500,  //время скроллинга, через которое появляется выделение (время анимации)
    
    
    images          : {}, //все картинки, как массив объектов
    lastElement     : null, //крайняя выбранная фотка
    last_backg      : null  //крайний элемент, в который вставлялся backg
}