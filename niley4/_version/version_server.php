<?php

// НА СЕРВЕРЕ (где лежит фреймворк):
$version = [
	'3.2.35' => '...', //тут список файлов


	// У каждой новой версии будет свой список измененных файлов.
	// Клиент подключается к серверу и загружает себе эти файлы
	'3.2.36' => [
        'files' => [
            'js/classes/Debug.js', //пример описания одного файла
            'libraries/L_content/', //пример описания всей папки (все файлы папки)
        ],
        'description' => [
            'Добавлена поддержка командной строки',
            'Файлы контента теперь грузятся быстрее',
            'Даты формируются в привычном формате'
        ]
    ],
    '3.2.37' => [
        'files' => [],
        'description' => [
            '1. ajax запросы пропускать через 1 файл',
            '2. данные о контенте брать из базы',
            '',
            '',
            '',
        ]
    ]
];
