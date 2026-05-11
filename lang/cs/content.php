<?php

return [
    'plural' => 'obsahy',
    'singular' => 'obsah',
    'sections' => [
        'seo_group' => 'SEO',
        'publishing' => 'Publikování',
        'taxonomies' => 'Taxonomie',
        'builder' => 'Stavitel',
    ],
    'fields' => [
        'meta_title' => 'Meta název',
        'meta_description' => 'Meta popis',
        'og_image' => 'OG obrázek',
        'body' => 'Tělo',
        'hero_image' => 'Hlavní obrázek',
        'excerpt' => 'Úryvek',
        'noindex' => 'Skrýt před vyhledávači',
    ],
    'stats' => [
        'recent' => 'Nedávný obsah',
        'published' => 'Publikováno',
        'published_description' => 'Publikované stránky a příspěvky',
        'drafts' => 'Koncepty',
        'awaiting' => 'Čeká na publikování',
    ],
    'messages' => [
        'set_as_homepage' => 'Nastavit jako úvodní stránku',
        'only_one_homepage' => 'Pouze jeden obsah na webu může být úvodní stránka.',
    ],
    'types' => [
        'page' => 'Stránka',
        'post' => 'Příspěvek',
        'archive' => 'Archiv',
    ],
    'hints' => [
        'meta_title' => 'Přepíše výchozí nastavení webu, pokud je vyplněno.',
        'meta_description' => 'Až 160 znaků. Přepíše výchozí nastavení webu.',
        'og_image' => 'Přepíše výchozí OG obrázek webu pro tuto stránku.',
        'field_key' => 'Unique identifier for this field, e.g. "price", "stock", "subtitle".',
        'field_value' => 'The value for this field. Plugins may use this data to extend content functionality.',
    ],
    'actions' => [
        'revert' => 'Vrátit',
    ],
];
