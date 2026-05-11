<?php

return [
    'plural' => 'контент',
    'singular' => 'контент',
    'sections' => [
        'seo_group' => 'SEO',
        'publishing' => 'Публикация',
        'taxonomies' => 'Таксономии',
        'builder' => 'Конструктор',
    ],
    'fields' => [
        'meta_title' => 'Мета-заголовок',
        'meta_description' => 'Мета-описание',
        'og_image' => 'OG-изображение',
        'body' => 'Тело',
        'hero_image' => 'Главное изображение',
        'excerpt' => 'Отрывок',
        'noindex' => 'Скрыть от поисковиков',
    ],
    'stats' => [
        'recent' => 'Последний контент',
        'published' => 'Опубликовано',
        'published_description' => 'Опубликованные страницы и посты',
        'drafts' => 'Черновики',
        'awaiting' => 'Ожидает публикации',
    ],
    'messages' => [
        'set_as_homepage' => 'Сделать главной страницей',
        'only_one_homepage' => 'Только один контент на сайте может быть главной страницей.',
    ],
    'types' => [
        'page' => 'Страница',
        'post' => 'Пост',
        'archive' => 'Архив',
    ],
    'hints' => [
        'meta_title' => 'Переопределяет стандартное значение сайта при установке.',
        'meta_description' => 'До 160 символов. Переопределяет стандартное значение сайта.',
        'og_image' => 'Переопределяет OG-изображение сайта для этой страницы.',
        'field_key' => 'Unique identifier for this field, e.g. "price", "stock", "subtitle".',
        'field_value' => 'The value for this field. Plugins may use this data to extend content functionality.',
    ],
    'actions' => [
        'revert' => 'Откатить',
    ],
];
