<?php

return [
    'plural' => 'innehåll',
    'singular' => 'innehåll',
    'sections' => [
        'seo_group' => 'SEO',
        'publishing' => 'Publicering',
        'taxonomies' => 'Taxonomier',
        'builder' => 'Sidbyggare',
    ],
    'fields' => [
        'meta_title' => 'Metatitel',
        'meta_description' => 'Metabeskrivning',
        'og_image' => 'OG-bild',
        'body' => 'Brödtext',
        'hero_image' => 'Huvudbild',
        'excerpt' => 'Utdrag',
        'noindex' => 'Dölj från sökmotorer',
    ],
    'stats' => [
        'recent' => 'Senaste innehåll',
        'published' => 'Publicerad',
        'published_description' => 'Publicerade sidor och inlägg',
        'drafts' => 'Utkast',
        'awaiting' => 'Väntar på publicering',
    ],
    'messages' => [
        'set_as_homepage' => 'Ange som startsida',
        'only_one_homepage' => 'Endast ett innehåll per webbplats kan vara startsida.',
    ],
    'types' => [
        'page' => 'Sida',
        'post' => 'Inlägg',
        'archive' => 'Arkiv',
    ],
    'hints' => [
        'meta_title' => 'Åsidosätter webbplatsens standard när det är angivet.',
        'meta_description' => 'Upp till 160 tecken. Åsidosätter webbplatsens standard.',
        'og_image' => 'Åsidosätter webbplatsens OG-bild för den här sidan.',
        'field_key' => 'Unique identifier for this field, e.g. "price", "stock", "subtitle".',
        'field_value' => 'The value for this field. Plugins may use this data to extend content functionality.',
    ],
    'actions' => [
        'revert' => 'Återställ',
    ],
];
