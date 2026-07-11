<?php

return [
    'plural' => 'Inhalte',
    'singular' => 'Inhalt',
    'sections' => [
        'seo_group' => 'SEO',
        'publishing' => 'Veröffentlichung',
        'taxonomies' => 'Taxonomien',
        'builder' => 'Seitenersteller',
    ],
    'fields' => [
        'meta_title' => 'Meta-Titel',
        'meta_description' => 'Meta-Beschreibung',
        'og_image' => 'OG-Bild',
        'body' => 'Inhalt',
        'hero_image' => 'Hauptbild',
        'excerpt' => 'Auszug',
        'noindex' => 'Vor Suchmaschinen verbergen',
    ],
    'stats' => [
        'recent' => 'Neueste Inhalte',
        'published' => 'Veröffentlicht',
        'published_description' => 'Veröffentlichte Seiten & Beiträge',
        'drafts' => 'Entwürfe',
        'awaiting' => 'Wartet auf Veröffentlichung',
    ],
    'messages' => [
        'set_as_homepage' => 'Als Startseite festlegen',
        'only_one_homepage' => 'Nur ein Inhalt pro Website kann die Startseite sein.',
        'save_first_for_builder' => 'Speichern Sie zuerst den Inhalt, um den Builder zu verwenden.',
        'builder_has_content' => 'Die Seite hat gespeicherten Inhalt aus dem Builder.',
        'builder_no_content' => 'Die Seite hat noch keinen Inhalt aus dem Builder.',
    ],
    'types' => [
        'page' => 'Seite',
        'post' => 'Beitrag',
        'archive' => 'Archiv',
    ],
    'hints' => [
        'meta_title' => 'Überschreibt die websiteweite Standardeinstellung, wenn gesetzt.',
        'meta_description' => 'Bis zu 160 Zeichen. Überschreibt die websiteweite Standardeinstellung.',
        'og_image' => 'Überschreibt das websiteweite OG-Bild für diese Seite.',
        'field_key' => 'Unique identifier for this field, e.g. "price", "stock", "subtitle".',
        'field_value' => 'The value for this field. Plugins may use this data to extend content functionality.',
    ],
    'actions' => [
        'revert' => 'Zurücksetzen',
        'open_builder' => 'Builder öffnen',
    ],
];
