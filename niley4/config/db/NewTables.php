<?php if ( ! defined('andromed')) exit('');
$db_tables = array(

//errors - сюда записываются ошибки
//стандартная таблица ошибок

		"{$config['prefix']}errors"=>"CREATE TABLE `{$config['prefix']}errors` (
		`id`				int(11) NOT NULL auto_increment,
		`internalCode` 		int(2) default 0,
		`code`				int(11) default 0,
		`time`				char(22) default 0,
		`message`			text,
		`classWhichCalled`	char(50) default 0,
		`ip`				char(21) default 0,
		`browser`			varchar(99) default 0,
		`counter_number` 	int(11) default 0,
		PRIMARY KEY (id)) ENGINE=InnoDB DEFAULT CHARSET=utf8",



	/*users_online
            id - не автоинкремент, т.к мы будем самостоятельно писать туда номер (в зависимости от сесии)
            sid - идентификатор сессий
            secret_number - пользователь предъявит не только номер заказа, но и свой
                "персональный" секретный код для идентификации и получения заказа
            user_info - браузер
            time_create - самое первое создание данной записи. Совпадает с time_update, если это - не перезапись
            time_update - начало кукис, когда пользователь открыл страницу сайта
            time_online - постоянная перезапись этого параметра говорит о том, что пользователь в онлайне
    */
		"{$config['prefix']}users_online"=>"CREATE TABLE `{$config['prefix']}users_online` (
		`id`		int(11) NOT NULL,
		`deleted` 	int(1) default 0,

		`sid`			varchar(50) default '',
		`secret_number` char(6) default '',
		`user_info`		varchar(50) default '',
		`time_create`	int(11) default 0,
		`time_update`	int(11) default 0,
		`time_online`	int(11) default 0,
		PRIMARY KEY (id))ENGINE=InnoDB DEFAULT CHARSET=utf8",

	/*
    users
        при авторизации происходит запись в users_online
            status
                0 - только зарегистрировался, не подтвердил свой аккаунт. Ссылка - на половина хеша, половина пароля
                1 - подтвердил аккаунт, рядовой пользователь. Повторное подверждение недоступно.
                3 - модератор (старший модератор)
                5 - администратор
            hash - случайное 20значное число (личное), которое записывается 1 раз
                Используется в активации.
            login - уникальное!
            pass - пароль
                Используется в активации.
        личная информация:
            name
            surname
            address
            phone - обновляется в логах
            mail
            foto	- знак того, что фото закачано
        регистрируемая информация:
            time_create
            time_update - при авторизации
            time_last - перезаписывается из time_update
            user_id_update - с чьей стороны было обновление информации
            ip_create
            ip_update - записывается при авторизации
            ip_last - перезаписывается из now_ip
            u_online_id	- id из users_online, при котором регистрировался данный пользователь
            remark 		комментарии о пользователе

        настраиваемые параметры
            language - язык интерфейса
    */
		"{$config['prefix']}users"=>"CREATE TABLE `{$config['prefix']}users` (
		`id`		int(11) NOT NULL auto_increment,
		`deleted` 	int(1) default 0,
		`status`	int(1) default 0,

		`hash`		char(20) default '',
		`login`		varchar(50) default '',
		`pass`		char(32) default '',
		`name`		varchar(50) default '',
		`surname`	varchar(50) default '',
		`address`	varchar(255) default '',
		`phone`		varchar(50) default '',
		`mail`		varchar(70) default '',
		`foto`		int(1) default 0,

		`time_create`	int(11) default 0,
		`time_update`	int(11) default 0,
		`time_last`		int(11) default 0,
		`user_create` 	int(11) default 0,
		`user_update` 	int(11) default 0,
		`ip_create`		char(16) default '',
		`ip_update`		char(16) default '',
		`ip_last`		char(16) default '',
		`u_online_id`	int(11) default 0,
		`remark`		mediumtext,

		`language`		char(5) default 'ru',
		PRIMARY KEY (id))ENGINE=InnoDB DEFAULT CHARSET=utf8",


	/*
    users_log
        логи действий зарегистрированных пользователей (кроме покупок)
            user_id
            user_online_id - для связки с онлайн-таблицей users_online, пометка о том, какой пользователь из онлайн решил войти

            status. 1-вход, 9-выход, 2-обновление информации
            user_info - обновляемая информация
                (телефон!) - если пользователь часто меняет телефон
            time_create - время создания записи
            ip	- айпи
    */
		"{$config['prefix']}users_log"=>"CREATE TABLE `{$config['prefix']}users_log` (
		`id`		int(11) NOT NULL auto_increment,
		`deleted` 	int(1) default 0,

		`user_id`			int(11) default 0,
		`user_online_id`	int(11) default 0,
		`status` 			int(1) default 0,
		`user_info`			varchar(255) default '',
		`time_create`		int(11) default 0,
		`ip`				char(16) default '',
		PRIMARY KEY (id))ENGINE=InnoDB DEFAULT CHARSET=utf8",

	/*
    seo_lynks
            site_url		создан с целью заменить некрасивые url
                            вида /uslugi/?id=22&сat=57
                            после замены все get-параметры остаются доступными через redirect_cpu.php + L_line
            site_redirect 	вида /uslugi/category/ без знаков вопроса,
                            т.к. при послередиректной проверке лишние символы обрезаются
                            get-параметры могут существовать, только в случае совпадения, get в site_uri имеет больший приоритет,
                            чем в site_redirect
            is_replace_page	1/0, показывает, будет ли взятие параметра page из site_url (т.е. неполная замена)
                            Если = 1, то в page записывается тот параметр, который стоит в site_url первым в слешевом ряду
                            вернее, использоваться будет параметр в $page - из 1го столбца
                            (когда мы хотим параметры, использованные в столбце site_uri использовать после редиректа)

                Не разрабатывал ввиду ненужной обработки сервером: - 2014-06-11
                            При замене page хочу в цикле проверять наличие строк во втором столбце. Только проверки get-параметров не будет
                            возможным, т.к. нет точной формы get (там ведь переменные)
                            Т.е если в url будет 2 и более ячеек array_line, каждая ячейка будет делать свой запрос на редирект
                            Это нам НАДО?

                Не функционирует (пока): - 2014-06-11
            is_softly_replace	мягкая ли замена? По умолчанию - нет, жесткая
                            Жесткая замена - точная замена строки из site_uri на строку site_redirect
                                При этом если в адресе uri есть дополнительные параметры, тогда такая строка не подлежит замене.
                                Равенство должно быть точным
                            Мягкая замена - это такая замена, при которой используется только часть адреса uri, указанная в site_uri.
                                Если часть адреса совпадает, происходит замена. При этом оставшаяся часть (хвост) добавляется в новый uri, если
                                установлен флажок "Добавить хвост"
                В разработке: - 2014-06-11
            paste_tail		добавить хвост. По умолчанию - нет
            is_softly_check	мягкая ли проверка? По умолчанию - нет, жесткая
                            Жесткая проверка - состояние, когда команды на редирект нет (т.к. ее нет в таблице), и происходит анализ второго
                                столбца - site_redirect.

    */
		"{$config['prefix']}seo_lynks"=>"CREATE TABLE `{$config['prefix']}seo_lynks` (
		`id`			int(11) NOT NULL auto_increment,
		`deleted` 		int(1) default 0,

		`time_create`	int(11) default 0,
		`time_update`	int(11) default 0,
		`user_create`	int(11) default 0,
		`user_update`	int(11) default 0,

		`site_uri`			varchar(255) default '',
		`site_redirect`		varchar(255) default '',
		`is_replace_page`	tinyint(1) default 0,
		`is_softly_replace`	tinyint(1) default 0,
		`priority`			int(6) default 1,
		`remark`			varchar(255) default '',
		`title_ru`			varchar(255) default '',
		`keywords_ru`		varchar(255) default '',
		`description_ru` 	varchar(255) default '',
		`title_en`			varchar(255) default '',
		`keywords_en`		varchar(255) default '',
		`description_en` 	varchar(255) default '',
		PRIMARY KEY (id))ENGINE=InnoDB DEFAULT CHARSET=utf8",


	/*
    files

    */
	/*
            "{$config['prefix']}files"=>"CREATE TABLE `{$config['prefix']}files` (
            `id`			int(11) NOT NULL auto_increment,
            `deleted` 		int(1) default 0,

            `time_create`	int(11) default 0,
            `time_update`	int(11) default 0,
            `user_create`	int(11) default 0,
            `user_update`	int(11) default 0,
            `file`			varchar(40) default '',
            `file_ext` 		varchar(80) default '',
            `file_is` 		tinyint(1) default 0,
            PRIMARY KEY (id))ENGINE=InnoDB DEFAULT CHARSET=utf8",
    */

);
?>