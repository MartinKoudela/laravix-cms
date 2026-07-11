<?php

return [
    'singular' => 'paramètre',
    'plural' => 'paramètres',
    'tabs' => [
        'general' => 'Général',
        'seo' => 'SEO',
        'social' => 'Réseaux sociaux',
    ],
    'fields' => [
        'site_name' => 'Nom du site',
        'site_description' => 'Description du site',
        'site_logo' => 'Logo du site',
        'favicon' => 'Favicon',
        'locale' => 'Langue',
        'contact_email' => 'E-mail de contact',
        'meta_title' => 'Méta-titre',
        'meta_description' => 'Méta-description',
        'og_image' => 'Image OG',
        'google_verification' => 'Vérification Google',
        'twitter' => 'X / Twitter',
        'linkedin' => 'LinkedIn',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'github' => 'GitHub',
        'robots_txt' => 'robots.txt',
    ],
    'actions' => [
        'save' => 'Enregistrer les paramètres',
        'manage' => 'Gérer les paramètres',
    ],
    'messages' => [
        'saved' => 'Paramètres enregistrés',
    ],
    'hints' => [
        'logo' => 'Affiché dans l\'en-tête et le pied de page.',
        'setting_key' => 'Unique setting key, e.g. "site_name", "google_analytics", "logo".',
        'setting_value' => 'The value for this setting.',
        'favicon' => 'Icône de l\'onglet du navigateur. Recommandé : PNG 32×32 ou 64×64.',
        'contact_email' => 'Utilisé dans les formulaires de contact et les e-mails transactionnels.',
        'meta_title' => 'Titre de page par défaut si aucun titre au niveau du contenu n\'est défini.',
        'meta_description' => 'Description méta par défaut (jusqu\'à 160 caractères).',
        'og_image' => 'Image Open Graph par défaut pour le partage sur les réseaux sociaux.',
        'locale' => 'Code de langue utilisé dans <html lang="">. Ex. : en, fr, de, cs.',
        'google_verification' => 'Collez la valeur content de la balise méta Google Search Console.',
        'robots_txt' => 'Laisser vide pour la valeur par défaut (Tout autoriser). L\'URL du sitemap est toujours ajoutée automatiquement.',
    ],
];
