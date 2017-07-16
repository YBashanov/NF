/*
2014-10-08, первое использование - dreamplan2/
2014-10-09 - доработка (выделил отдельный модуль рабочего popup)

Я подключаю к управлению продуктами (reeste_sub3) возможность загружать фотографии через popup, 
чтобы не делать дополнительных раскрывающихся полей вложенности

В корень основного файла, как обычно, подключаются стили и скрипты ребенка:
<link href='{$base_url}templates/personal/style/s_reestr_sub3_foto.css' rel='stylesheet' type='text/css' />
<script src='{$base_url}js/personal/reestr_sub3_foto.js'></script>

Также, где-нибудь вверху основного файла подключу модуль работы со служебным popup
<link href='{$base_url}templates/personal/style/popup.css' rel='stylesheet' type='text/css' /> - дополнительная тема!
<script src='{$base_url}js/personal/popup.js'></script> - дополнительная тема!
	это модуль PopupNews


1. Переименовываю News в FotoNews
	внутри news.js

2. Делаю кнопку где-нибудь в строке родителя reeste_sub3 для появления popup (в Tr, можно также в Tr_open)
	$in2 .= "<div class='td Sub316' onclick='FotoNews.sec_open(\"{$val['id']}\")'> Открыть </div>";

3. Делаю место всплытия popup (в самое начало Head, перед остальными тегами)
	например, в шапке самого верхнего родителя
	$in2 .= "<div class='popupPlace' id='popupUp'></div>";

4. 
	news.js:
	1) FotoNews.sec_open = function(id) (добавить параметр id)
		Надо чтобы функция принимала только 1 параметр - это id
	2) переопределить все выводы данных с parent на PopupNews.popupOpen( ТУТ_ДАННЫЕ )
		закомментировать parent
	3) добавить параметр id в HTTP.post (отправка на сервер)
	4) изменить строку - uploadReady(0, "file", "reestr_sub3_foto", "simple", id, true, function(){FotoNews.sec_open(id)});
		(тут появится наш id, к тому же надо убрать функцию обратного вызова)
		повнимательней! не копируйте 100%, надо же еще и reestr_sub3_foto на что-то заменить
		
	t_news_Foreach.php
	1) изменить кнопки загрузки, иначе они будут неактивны - добавить {$id}
		<div id='uploadButton{$id}_0_file_reestr_sub3_foto' class='crButton'>Загрузить файл</div>
		<div id='uploadButton_Err{$id}_0_file_reestr_sub3_foto'></div>
	
	ajax/news_open.php
	1) добавить параметр id: $id = $regular->num($_POST["id"]);
		(а также в проверку на true)
	
	d_news.php
	1) добавить параметр выборки $whereAnd = " AND `parent_id`='{$id}'";
	
5. 
	t_news_Tr 		- также переименовать News в FotoNews
	t_news_adjust 	- также переименовать News в FotoNews

5.1. Также: 
	если мы работаем не с фото, а с обычным "скелетом" строк, то переименовать также надо и в 
	t_news_Tr_open
	t_news_Foreach
	
	В news.js необходимо на кнопку save (создать) после ajax-сохранения в res[0]==1 сделать вызов первой функции - open
	(обычно в дочерних классах этой связи нет), чтобы после создания строки мы сразу ее видели

6. После того, как выполнили первую загрузку (и создалась таблица в базе)
	- в базе надо добавить поле parent_id (поскольку такое поле при создании чистой-загрузки-файлов не создается в siteboundary)
	parent_id int(11) default 0,

7. Если работаем с картинками или tinymce,
	надо синхронизировать открытие в adjust-div'e, для этого
	- t_news_Foreach.php: верхней строке 	$in .= "<div id='ADJUST' class='adjust'></div>"; - задать отличающийся id
	- news.js 	- там, где идет ссылка на заполнение id="new_ADJUST", задать тот же самый id
	
	
	
	
	
Чтобы заработало после update (если вдруг добавил какое-то поле в siteboundary)
- обязательно сохранить коренной файл для того, чтобы сохранились подключения css, js!
иначе: придется прописывать все стили, в том числе и дочерние!

1. Делаю кнопку где-нибудь в строке родителя reeste_sub3 для появления popup (в Tr, можно также в Tr_open)
	$in2 .= "<div class='td Sub316' onclick='FotoNews.sec_open(\"{$val['id']}\")'> Открыть </div>";
	(тут надо посмотреть название функции FotoNews в подключаемом файле js, чтобы не ошибиться)

2. Делаю место всплытия popup (в самое начало Head, перед остальными тегами)
	например, в шапке самого верхнего родителя
	$in2 .= "<div class='popupPlace' id='popupUp'></div>";

3. Если работаем с картинками или tinymce,
	надо синхронизировать открытие в adjust-div'e, для этого
	- foto_news_Foreach.php: верхней строке 	$in .= "<div id='ADJUST' class='adjust'></div>"; - задать отличающийся id
	- foto_news.js 	- там, где идет ссылка на заполнение id="ADJUST", задать тот же самый id

После перехода на другой раздел, открытию мешает скрипт, который ищет предыдущий элемент, чтобы его обводку сделать белой. 
Если закомментировать это место (в FotoNews.sec_show = function (id)), тогда ничего мешать не будет
В siteboundary это место я исправил, остается только поправлять в старых файлах
*/