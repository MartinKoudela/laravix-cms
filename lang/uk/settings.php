<?php

return [
    'singular' => 'налаштування',
    'plural' => 'налаштування',
    'tabs' => [
        'general' => 'Загальне',
        'seo' => 'SEO',
        'social' => 'Соціальні мережі',
    ],
    'fields' => [
        'site_name' => 'Назва сайту',
        'site_description' => 'Опис сайту',
        'site_logo' => 'Логотип сайту',
        'favicon' => 'Favicon',
        'locale' => 'Мова',
        'contact_email' => 'Контактна пошта',
        'meta_title' => 'Мета-заголовок',
        'meta_description' => 'Мета-опис',
        'og_image' => 'OG-зображення',
        'google_verification' => 'Верифікація Google',
        'twitter' => 'X / Twitter',
        'linkedin' => 'LinkedIn',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'github' => 'GitHub',
        'robots_txt' => 'robots.txt',
    ],
    'actions' => [
        'save' => 'Зберегти налаштування',
        'manage' => 'Керувати налаштуваннями',
    ],
    'messages' => [
        'saved' => 'Налаштування збережено',
    ],
    'hints' => [
        'logo' => 'Відображається у шапці та підвалі.',
        'setting_key' => 'Unique setting key, e.g. "site_name", "google_analytics", "logo".',
        'setting_value' => 'The value for this setting.',
        'favicon' => 'Іконка вкладки браузера. Рекомендовано: PNG 32×32 або 64×64.',
        'contact_email' => 'Використовується у контактних формах та транзакційних листах.',
        'meta_title' => 'Стандартний заголовок сторінки, якщо не встановлено заголовок на рівні вмісту.',
        'meta_description' => 'Стандартний мета-опис (до 160 символів).',
        'og_image' => 'Стандартне зображення Open Graph для публікації у соцмережах.',
        'locale' => 'Код мови для <html lang="">. Наприклад: en, uk, de, fr.',
        'google_verification' => 'Вставте значення атрибуту content з мета-тегу Google Search Console.',
        'robots_txt' => 'Залиште порожнім для стандартного значення (Дозволити все). URL карти сайту додається автоматично.',
    ],
];
