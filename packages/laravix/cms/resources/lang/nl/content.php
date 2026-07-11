<?php

return [
    'plural' => 'inhoud',
    'singular' => 'inhoud',
    'sections' => [
        'seo_group' => 'SEO',
        'publishing' => 'Publicatie',
        'taxonomies' => 'Taxonomieën',
        'builder' => 'Paginabouwer',
    ],
    'fields' => [
        'meta_title' => 'Meta-titel',
        'meta_description' => 'Meta-beschrijving',
        'og_image' => 'OG-afbeelding',
        'body' => 'Inhoud',
        'hero_image' => 'Hoofdafbeelding',
        'excerpt' => 'Samenvatting',
        'noindex' => 'Verbergen voor zoekmachines',
    ],
    'stats' => [
        'recent' => 'Recente inhoud',
        'published' => 'Gepubliceerd',
        'published_description' => 'Gepubliceerde pagina\'s en berichten',
        'drafts' => 'Concepten',
        'awaiting' => 'Wacht op publicatie',
    ],
    'messages' => [
        'set_as_homepage' => 'Instellen als startpagina',
        'only_one_homepage' => 'Slechts één inhoud per website kan de startpagina zijn.',
        'save_first_for_builder' => 'Sla eerst de inhoud op om de blokbouwer te gebruiken.',
        'builder_has_content' => 'De pagina heeft opgeslagen inhoud van de builder.',
        'builder_no_content' => 'De pagina heeft nog geen inhoud van de builder.',
    ],
    'types' => [
        'page' => 'Pagina',
        'post' => 'Bericht',
        'archive' => 'Archief',
    ],
    'hints' => [
        'meta_title' => 'Overschrijft de websitebrede standaard indien ingesteld.',
        'meta_description' => 'Tot 160 tekens. Overschrijft de websitebrede standaard.',
        'og_image' => 'Overschrijft de websitebrede OG-afbeelding voor deze pagina.',
        'field_key' => 'Unique identifier for this field, e.g. "price", "stock", "subtitle".',
        'field_value' => 'The value for this field. Plugins may use this data to extend content functionality.',
    ],
    'actions' => [
        'revert' => 'Terugzetten',
        'open_builder' => 'Builder openen',
    ],
];
