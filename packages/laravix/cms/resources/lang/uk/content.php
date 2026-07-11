<?php

return [
    'plural' => 'контент',
    'singular' => 'контент',
    'sections' => [
        'seo_group' => 'SEO',
        'publishing' => 'Публікація',
        'taxonomies' => 'Таксономії',
        'builder' => 'Конструктор сторінок',
    ],
    'fields' => [
        'meta_title' => 'Мета-заголовок',
        'meta_description' => 'Мета-опис',
        'og_image' => 'OG-зображення',
        'body' => 'Тіло',
        'hero_image' => 'Головне зображення',
        'excerpt' => 'Уривок',
        'noindex' => 'Приховати від пошукових систем',
    ],
    'stats' => [
        'recent' => 'Останній контент',
        'published' => 'Опубліковано',
        'published_description' => 'Опубліковані сторінки та записи',
        'drafts' => 'Чернетки',
        'awaiting' => 'Очікує публікації',
    ],
    'messages' => [
        'set_as_homepage' => 'Встановити як головну',
        'only_one_homepage' => 'Лише один контент на сайті може бути головною сторінкою.',
        'save_first_for_builder' => 'Спочатку збережіть вміст, щоб використовувати конструктор блоків.',
        'builder_has_content' => 'Сторінка має збережений вміст з конструктора.',
        'builder_no_content' => 'Сторінка ще не має вмісту з конструктора.',
    ],
    'types' => [
        'page' => 'Сторінка',
        'post' => 'Запис',
        'archive' => 'Архів',
    ],
    'hints' => [
        'meta_title' => 'Замінює стандартне значення сайту, якщо вказано.',
        'meta_description' => 'До 160 символів. Замінює стандартне значення сайту.',
        'og_image' => 'Замінює стандартне OG-зображення сайту для цієї сторінки.',
        'field_key' => 'Unique identifier for this field, e.g. "price", "stock", "subtitle".',
        'field_value' => 'The value for this field. Plugins may use this data to extend content functionality.',
    ],
    'actions' => [
        'revert' => 'Відновити',
        'open_builder' => 'Відкрити конструктор',
    ],
];
