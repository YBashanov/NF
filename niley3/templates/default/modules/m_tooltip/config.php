<?php if ( ! defined('andromed')) exit(''); 
/*
Тултип (мини-всплывающее окно)
v1.0(alvaprint)

1. Подключение модуля
includes("{$separator}templates/{$global['template']}/modules/m_tooltip/config.php");

2. Подключение шаблона в страницу - В САМОЕ НАЧАЛО - ПЕРЕД ТЕГОМ <body>
{M_TOOLTIP}


Методы объекта
- create(params)

    M_tooltip.create({
        className : "tip",      //этот класс в каком-то элементе указывает нам, что на него наложится наш тултип
        textAttr : "tip_text",  //этот атрибут того элемента содержит текст для нашего тултипа
        text : "текст, если не задан textAttr"
        dx : "center",
        dy : 20,
        css : {}, //стили внешнего блока
        cssContent : {} //стили текста (внутреннего блока)
    });

*/
module_(M_TOOLTIP);
?>