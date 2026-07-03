<?php

return [
    'plural' => 'obsahy',
    'singular' => 'obsah',
    'sections' => [
        'seo_group' => 'SEO',
        'publishing' => 'Publikovanie',
        'taxonomies' => 'Taxonómie',
        'builder' => 'Staviteľ stránok',
    ],
    'fields' => [
        'meta_title' => 'Meta názov',
        'meta_description' => 'Meta popis',
        'og_image' => 'OG obrázok',
        'body' => 'Telo',
        'hero_image' => 'Hlavný obrázok',
        'excerpt' => 'Úryvok',
        'noindex' => 'Skryť pred vyhľadávačmi',
    ],
    'stats' => [
        'recent' => 'Nedávny obsah',
        'published' => 'Publikované',
        'published_description' => 'Publikované stránky a príspevky',
        'drafts' => 'Koncepty',
        'awaiting' => 'Čaká na publikovanie',
    ],
    'messages' => [
        'set_as_homepage' => 'Nastaviť ako úvodnú stránku',
        'only_one_homepage' => 'Iba jeden obsah na webe môže byť úvodná stránka.',
        'save_first_for_builder' => 'Najprv uložte obsah, aby ste mohli používať blokový staviteľ.',
        'builder_has_content' => 'Stránka má uložený obsah z buildera.',
        'builder_no_content' => 'Stránka zatiaľ nemá žiadny obsah z buildera.',
    ],
    'types' => [
        'page' => 'Stránka',
        'post' => 'Príspevok',
        'archive' => 'Archív',
    ],
    'types_plural' => [
        'page' => 'Stránky',
        'post' => 'Príspevky',
        'archive' => 'Archívy',
    ],
    'hints' => [
        'meta_title' => 'Prepíše predvolené nastavenie webu, ak je vyplnené.',
        'meta_description' => 'Až 160 znakov. Prepíše predvolené nastavenie webu.',
        'og_image' => 'Prepíše predvolený OG obrázok webu pre túto stránku.',
        'field_key' => 'Unique identifier for this field, e.g. "price", "stock", "subtitle".',
        'field_value' => 'The value for this field. Plugins may use this data to extend content functionality.',
    ],
    'actions' => [
        'revert' => 'Vrátiť',
        'open_builder' => 'Otvoriť Builder',
    ],
];
