<?php if ( ! defined('andromed')) exit(''); 
/*
шаблон для отбражения всплывающего окна
- возможность наполнять это окно своей html-версткой
    Popup2.setHTML();
- показать окно
    Popup2.show();


1. в _content_management указать:
    include "{$separator}templates/{$global['template']}/modules/m_popup/config.php";

2. на странице верстки прописать: (желательно в корневом файле v_index.html)
    {M_POPUP}

3. в нужном месте runonload.js указываем: (либо просто где-то на странице ниже определения пути до скрипта)

        Надо сначала решить, какая "сцепка" будет. И установить параметр position во второй строке в стилях style.css
        #popup{
            position:absolute; - вручную
            position:fixed; - жесткая


    3.1. Для сцепки вручную: (блоку надо установить положение на экране вручную)

    Popup2.start(false, true, false);
    Popup2.setStyles({
        "width" : "1000px",
        "height" : getWindowSize()[1] - 100,
        "top" : "50px"
    });

    3.2. Для жесткой (резиновой) сцепки: (блок авто-центрируется по центру)

    Popup2.start(false, true, true);
    Popup2.setStyles({"width" : "600px", "marginLeft" : "-300px"});
*/


module_(M_POPUP);
?>
