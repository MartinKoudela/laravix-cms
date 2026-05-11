<?php

return [
    'plural' => 'contenus',
    'singular' => 'contenu',
    'sections' => [
        'seo_group' => 'SEO',
        'publishing' => 'Publication',
        'taxonomies' => 'Taxonomies',
        'builder' => 'Constructeur de pages',
    ],
    'fields' => [
        'meta_title' => 'Méta-titre',
        'meta_description' => 'Méta-description',
        'og_image' => 'Image OG',
        'body' => 'Corps',
        'hero_image' => 'Image principale',
        'excerpt' => 'Extrait',
        'noindex' => 'Masquer des moteurs de recherche',
    ],
    'stats' => [
        'recent' => 'Contenu récent',
        'published' => 'Publié',
        'published_description' => 'Pages & articles publiés',
        'drafts' => 'Brouillons',
        'awaiting' => 'En attente de publication',
    ],
    'messages' => [
        'set_as_homepage' => 'Définir comme page d\'accueil',
        'only_one_homepage' => 'Un seul contenu par site peut être la page d\'accueil.',
    ],
    'types' => [
        'page' => 'Page',
        'post' => 'Article',
        'archive' => 'Archive',
    ],
    'hints' => [
        'meta_title' => 'Remplace la valeur par défaut du site lorsqu\'il est défini.',
        'meta_description' => 'Jusqu\'à 160 caractères. Remplace la valeur par défaut du site.',
        'og_image' => 'Remplace l\'image OG du site pour cette page.',
        'field_key' => 'Unique identifier for this field, e.g. "price", "stock", "subtitle".',
        'field_value' => 'The value for this field. Plugins may use this data to extend content functionality.',
    ],
    'actions' => [
        'revert' => 'Rétablir',
    ],
];
