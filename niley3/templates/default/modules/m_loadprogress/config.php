<?php if ( ! defined('andromed')) exit(''); 

//шаблон для отбражения неуправляемого прогресс-бара загрузки элементов страницы (или просто страницы)
	

/*
в content_management.php надо прописать:

	include "{$separator}templates/{$global['template']}/modules/m_loadprogress/config.php";
	

в runonload.js надо прописать:

	var divpage = document.getElementById("page"); //загруженная страница - отобразить
	divpage.style.display = "block";
	var loadprogress = document.getElementById("loadprogress"); //скрыть прогресс-бар
	loadprogress.style.display = "none";
	
	
в structure.css надо прописать:

	div.page{
		display:none;
	}


в главном родительском div-e прописать (например, в v_index.html):
	
	id='page'
	
	а также в коде (например, сразу после <body>):
	{M_LOADPROGRESS}

	
дополнительно - (в поздних версиях сайтов можно не указывать - эти настройки уже установлены)
если делаем общий прелоадер, тогда нужно указать еще
	- в v_enter.html
	class='page to_enter'

	- в structure.css
	div.page.to_enter{
		display:block;
	}
	
	чтобы при входе в админку не возникал пустой экран браузера
*/

module_(M_LOADPROGRESS);
?>
