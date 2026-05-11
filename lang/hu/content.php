<?php

return [
    'plural' => 'tartalmak',
    'singular' => 'tartalom',
    'sections' => [
        'seo_group' => 'SEO',
        'publishing' => 'Közzététel',
        'taxonomies' => 'Taxonómiák',
        'builder' => 'Oldalszerkesztő',
    ],
    'fields' => [
        'meta_title' => 'Meta cím',
        'meta_description' => 'Meta leírás',
        'og_image' => 'OG kép',
        'body' => 'Törzs',
        'hero_image' => 'Főkép',
        'excerpt' => 'Kivonat',
        'noindex' => 'Elrejtés a keresőmotorok elől',
    ],
    'stats' => [
        'recent' => 'Legújabb tartalom',
        'published' => 'Közzétett',
        'published_description' => 'Közzétett oldalak és bejegyzések',
        'drafts' => 'Vázlatok',
        'awaiting' => 'Közzétételre vár',
    ],
    'messages' => [
        'set_as_homepage' => 'Beállítás főoldalként',
        'only_one_homepage' => 'Csak egy tartalom lehet főoldal webhelyenként.',
    ],
    'types' => [
        'page' => 'Oldal',
        'post' => 'Bejegyzés',
        'archive' => 'Archívum',
    ],
    'hints' => [
        'meta_title' => 'Felülírja a webhely alapértelmezését, ha be van állítva.',
        'meta_description' => 'Legfeljebb 160 karakter. Felülírja a webhely alapértelmezését.',
        'og_image' => 'Felülírja a webhely OG-képét ezen az oldalon.',
        'field_key' => 'Unique identifier for this field, e.g. "price", "stock", "subtitle".',
        'field_value' => 'The value for this field. Plugins may use this data to extend content functionality.',
    ],
    'actions' => [
        'revert' => 'Visszaállítás',
    ],
];
