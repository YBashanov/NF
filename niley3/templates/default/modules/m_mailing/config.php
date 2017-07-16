<?php if ( ! defined('andromed')) exit(''); 

/*
Подписаться на рассылку

1. в _content_management - прописать
include "{$separator}templates/{$global['template']}/modules/m_mailing/config.php";

2. в html прописать:
{M_MAILING}

3. перекинуть в корень файлы "для панели управления"
прописать путь через меню панели управления, путь: mailing

4. база данных должна создаться из панели управления
*/


module_(M_MAILING);
?>
