<?php

return [
    'plural' => 'treści',
    'singular' => 'treść',
    'sections' => [
        'seo_group' => 'SEO',
        'publishing' => 'Publikowanie',
        'taxonomies' => 'Taksonomie',
        'builder' => 'Kreator stron',
    ],
    'fields' => [
        'meta_title' => 'Meta tytuł',
        'meta_description' => 'Meta opis',
        'og_image' => 'Obraz OG',
        'body' => 'Treść',
        'hero_image' => 'Główny obraz',
        'excerpt' => 'Fragment',
        'noindex' => 'Ukryj przed wyszukiwarkami',
    ],
    'stats' => [
        'recent' => 'Ostatnie treści',
        'published' => 'Opublikowane',
        'published_description' => 'Opublikowane strony i wpisy',
        'drafts' => 'Szkice',
        'awaiting' => 'Oczekuje na publikację',
    ],
    'messages' => [
        'set_as_homepage' => 'Ustaw jako stronę główną',
        'only_one_homepage' => 'Tylko jedna treść na witrynę może być stroną główną.',
    ],
    'types' => [
        'page' => 'Strona',
        'post' => 'Wpis',
        'archive' => 'Archiwum',
    ],
    'hints' => [
        'meta_title' => 'Zastępuje domyślne ustawienie witryny, gdy jest ustawione.',
        'meta_description' => 'Do 160 znaków. Zastępuje domyślne ustawienie witryny.',
        'og_image' => 'Zastępuje domyślny obraz OG witryny dla tej strony.',
        'field_key' => 'Unique identifier for this field, e.g. "price", "stock", "subtitle".',
        'field_value' => 'The value for this field. Plugins may use this data to extend content functionality.',
    ],
    'actions' => [
        'revert' => 'Przywróć',
    ],
];
