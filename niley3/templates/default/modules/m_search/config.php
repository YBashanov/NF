<?php if ( ! defined('andromed')) exit(''); 
/*
поле ПОИСК, реагирующее на клавишу Enter
    объект изменяет url, а что дальше - не его забота
    
    При загрузке input внутри появляется надпись "поиск..."
    При клике по input, надпись "поиск..." пропадает
    Если ничего не найдено - появляется надпись "Ничего не найдено", через 1,5 секунд она пропадает, и вместо нее появляется надпись "поиск..."

    
Требуются javascript-классы
- Handler
- Wait

v1.0 - начало 2014
v1.1 - 2014-05-27
v1.2 - 2015-11-22 - вынес в отдельный модуль



1. в _content_management - прописать
include "{$separator}templates/{$global['template']}/modules/m_search/config.php";

2. в html прописать:
{M_SEARCH}
	
3. дополнительные настройки модуля в файле js/config.js

*/
module_(M_SEARCH);
?>
