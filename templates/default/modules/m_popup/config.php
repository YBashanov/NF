<?php if ( ! defined('andromed')) exit(''); 
/*
Popup2 - выпадающее окно (с тенью) с любым содержимым
v 1.0 (msoundcafe)
v 1.1 (2014-05-21)
v 1.2 (2014-09-30)
v 1.3 (2014-10-06) - добавлена функция обратного вызова для закрытия popup
- добавлена кнопка Закрыть - активируется при инициализации (runonload)
- закрывается при клике по тени
! не работает в Internet Explorer 7 (проблемы со слоями)
v 1.4 (2015-08-23) - добавлен скроллинг внутри окна
v 2.0 (2015-11-29) - перевод на "чистые" объекты, и переход на структуру правильного построения модуля
    объект теперь называется M_popup

Методы объекта:
- create(params)
- show(name)
- hide(name)
- html(name, html)  - html(name)
- css(name, params)    


1. в _content_management указать:
    includes ("{$separator}templates/{$global['template']}/modules/m_popup/config.php");

2. на странице верстки прописать: (желательно в корневом файле v_index.html)
    {M_POPUP}

*/

module_(M_POPUP);
?>
