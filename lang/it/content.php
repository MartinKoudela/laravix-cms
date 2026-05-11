<?php

return [
    'plural' => 'contenuti',
    'singular' => 'contenuto',
    'sections' => [
        'seo_group' => 'SEO',
        'publishing' => 'Pubblicazione',
        'taxonomies' => 'Tassonomie',
        'builder' => 'Costruttore di pagine',
    ],
    'fields' => [
        'meta_title' => 'Meta titolo',
        'meta_description' => 'Meta descrizione',
        'og_image' => 'Immagine OG',
        'body' => 'Corpo',
        'hero_image' => 'Immagine principale',
        'excerpt' => 'Estratto',
        'noindex' => 'Nascondi dai motori di ricerca',
    ],
    'stats' => [
        'recent' => 'Contenuto recente',
        'published' => 'Pubblicato',
        'published_description' => 'Pagine e post pubblicati',
        'drafts' => 'Bozze',
        'awaiting' => 'In attesa di pubblicazione',
    ],
    'messages' => [
        'set_as_homepage' => 'Imposta come pagina principale',
        'only_one_homepage' => 'Solo un contenuto per sito può essere la pagina principale.',
    ],
    'types' => [
        'page' => 'Pagina',
        'post' => 'Post',
        'archive' => 'Archivio',
    ],
    'hints' => [
        'meta_title' => 'Sostituisce il valore predefinito del sito quando impostato.',
        'meta_description' => 'Fino a 160 caratteri. Sostituisce il valore predefinito del sito.',
        'og_image' => 'Sostituisce l\'immagine OG del sito per questa pagina.',
        'field_key' => 'Unique identifier for this field, e.g. "price", "stock", "subtitle".',
        'field_value' => 'The value for this field. Plugins may use this data to extend content functionality.',
    ],
    'actions' => [
        'revert' => 'Ripristina',
    ],
];
