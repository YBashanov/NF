<?php if ( ! defined('andromed')) exit(''); 
/*
Вертикальная фотогалерея и область выделения изображения

    При клике на какое-либо изображение, на нем появляется выделение, которое можно перемещать по изображению.
	При клике также картинка, на которую кликнули, перемещается в центр блока (экрана)
    При начале прокрутки выделение пропадает.
    
    
1. в _content_management - прописать
include "{$separator}templates/{$global['template']}/modules/m_fotoselection_area/config.php";

2. в html прописать:
{M_FOTOSELECTION_AREA}

3. настройки путей до папок и файлов-обработчиков
	js/config.js
	
	
	
Заметки по Jscop
	jcrop_api.destroy(); - уничтожает выделяемую область (дает возможность иметь ее в одном экземпляре)
	jcrop_api.release(); - спрятать (но не удалить) область
	jcrop_api.setOptions({aspectRatio : quotWH }); 	- если quotWH > 0 - жестко выставить соотношения сторон
	jcrop_api.setOptions({aspectRatio : 0 }); 		- можно свободно выбирать выделяемую область
*/
module_(M_FOTOSELECTION_AREA);
?>
