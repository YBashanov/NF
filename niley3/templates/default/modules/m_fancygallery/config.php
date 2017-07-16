<?php if ( ! defined('andromed')) exit(''); 

/*
Галерея - просмотр фотографий с небольшими иконками сбоку (3 иконки)
При клике на большую картинку, появляется на весь экран огромная. 
Огромную можно листать влево-вправо (кнопки спрятаны слева и справа на ней самой)

Взято из dveryoptom.com
v1 - 2014-10-08, первая реализация - http://dreamplan2/

1. Подключение модуля
	include "{$separator}templates/{$global['template']}/modules/m_fancygallery/config.php";

2. Подключение шаблона в страницу
	{M_FANCYGALLERY}

3. Заменить в динамических разделах html строку-путь до uploads/папка
	(т.е. прописать путь до папки с изображениями)
	
4. Добавить данных (двумерный вложенный массив) в $m_images_data
	(классический массив $db->select)
*/


module_(INDEX_THUMBS, array(), "m_fancygallery/index_thumbs");
module__dynamic(INDEX_THUMBS, "m_fancygallery/index_thumbs", $m_images_data);

module_(INDEX_IMAGES, array(), "m_fancygallery/index_images");
module__dynamic(INDEX_IMAGES, "m_fancygallery/index_images", $m_images_data);

module_(M_FANCYGALLERY);
?>

