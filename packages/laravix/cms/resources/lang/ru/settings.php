<?php

return [
    'singular' => 'настройка',
    'plural' => 'настройки',
    'tabs' => [
        'general' => 'Общее',
        'seo' => 'SEO',
        'social' => 'Социальные сети',
    ],
    'fields' => [
        'site_name' => 'Название сайта',
        'site_description' => 'Описание сайта',
        'site_logo' => 'Логотип сайта',
        'favicon' => 'Favicon',
        'locale' => 'Язык',
        'contact_email' => 'Контактный email',
        'meta_title' => 'Мета-заголовок',
        'meta_description' => 'Мета-описание',
        'og_image' => 'OG-изображение',
        'google_verification' => 'Подтверждение Google',
        'twitter' => 'X / Twitter',
        'linkedin' => 'LinkedIn',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'github' => 'GitHub',
        'robots_txt' => 'robots.txt',
    ],
    'actions' => [
        'save' => 'Сохранить настройки',
        'manage' => 'Управление настройками',
    ],
    'messages' => [
        'saved' => 'Настройки сохранены',
    ],
    'hints' => [
        'logo' => 'Отображается в шапке и подвале.',
        'setting_key' => 'Unique setting key, e.g. "site_name", "google_analytics", "logo".',
        'setting_value' => 'The value for this setting.',
        'favicon' => 'Иконка вкладки браузера. Рекомендуется: PNG 32×32 или 64×64.',
        'contact_email' => 'Используется в контактных формах и транзакционных письмах.',
        'meta_title' => 'Заголовок страницы по умолчанию, если не задан заголовок на уровне контента.',
        'meta_description' => 'Мета-описание по умолчанию (до 160 символов).',
        'og_image' => 'Изображение Open Graph по умолчанию для публикации в соцсетях.',
        'locale' => 'Код языка для <html lang="">. Например: en, ru, de, fr.',
        'google_verification' => 'Вставьте значение атрибута content из мета-тега Google Search Console.',
        'robots_txt' => 'Оставьте пустым для значения по умолчанию (Разрешить всё). URL карты сайта добавляется автоматически.',
    ],
];
