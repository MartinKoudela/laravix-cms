<?php

return [
    'plural' => 'conținuturi',
    'singular' => 'conținut',
    'sections' => [
        'seo_group' => 'SEO',
        'publishing' => 'Publicare',
        'taxonomies' => 'Taxonomii',
        'builder' => 'Constructor de pagini',
    ],
    'fields' => [
        'meta_title' => 'Meta titlu',
        'meta_description' => 'Meta descriere',
        'og_image' => 'Imagine OG',
        'body' => 'Corp',
        'hero_image' => 'Imagine principală',
        'excerpt' => 'Extras',
        'noindex' => 'Ascunde de motoarele de căutare',
    ],
    'stats' => [
        'recent' => 'Conținut recent',
        'published' => 'Publicat',
        'published_description' => 'Pagini și postări publicate',
        'drafts' => 'Ciorne',
        'awaiting' => 'În așteptarea publicării',
    ],
    'messages' => [
        'set_as_homepage' => 'Setează ca pagină principală',
        'only_one_homepage' => 'Doar un conținut per site poate fi pagina principală.',
    ],
    'types' => [
        'page' => 'Pagină',
        'post' => 'Postare',
        'archive' => 'Arhivă',
    ],
    'hints' => [
        'meta_title' => 'Suprascrie setarea implicită a site-ului când este completat.',
        'meta_description' => 'Până la 160 de caractere. Suprascrie setarea implicită a site-ului.',
        'og_image' => 'Suprascrie imaginea OG a site-ului pentru această pagină.',
        'field_key' => 'Unique identifier for this field, e.g. "price", "stock", "subtitle".',
        'field_value' => 'The value for this field. Plugins may use this data to extend content functionality.',
    ],
    'actions' => [
        'revert' => 'Revenire',
    ],
];
