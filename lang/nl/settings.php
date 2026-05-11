<?php

return [
    'singular' => 'instelling',
    'plural' => 'instellingen',
    'tabs' => [
        'general' => 'Algemeen',
        'seo' => 'SEO',
        'social' => 'Sociale media',
    ],
    'fields' => [
        'site_name' => 'Websitenaam',
        'site_description' => 'Websitebeschrijving',
        'site_logo' => 'Websitelogo',
        'favicon' => 'Favicon',
        'locale' => 'Taal',
        'contact_email' => 'Contact-e-mail',
        'meta_title' => 'Meta-titel',
        'meta_description' => 'Meta-beschrijving',
        'og_image' => 'OG-afbeelding',
        'google_verification' => 'Google-verificatie',
        'twitter' => 'X / Twitter',
        'linkedin' => 'LinkedIn',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'github' => 'GitHub',
        'robots_txt' => 'robots.txt',
    ],
    'actions' => [
        'save' => 'Instellingen opslaan',
        'manage' => 'Instellingen beheren',
    ],
    'messages' => [
        'saved' => 'Instellingen opgeslagen',
    ],
    'hints' => [
        'logo' => 'Weergegeven in de kop- en voettekst.',
        'setting_key' => 'Unique setting key, e.g. "site_name", "google_analytics", "logo".',
        'setting_value' => 'The value for this setting.',
        'favicon' => 'Browser-tabbladpictogram. Aanbevolen: 32×32 of 64×64 PNG.',
        'contact_email' => 'Gebruikt in contactformulieren en transactionele e-mails.',
        'meta_title' => 'Standaard paginatitel wanneer er geen inhoudsniveau-titel is ingesteld.',
        'meta_description' => 'Standaard meta-beschrijving (tot 160 tekens).',
        'og_image' => 'Standaard Open Graph afbeelding voor sociaal delen.',
        'locale' => 'Taalcode gebruikt in <html lang="">. Bijv. en, nl, de, fr.',
        'google_verification' => 'Plak de content-waarde van de Google Search Console meta-tag.',
        'robots_txt' => 'Leeg laten voor standaard (Alles toestaan). Sitemap-URL wordt altijd automatisch toegevoegd.',
    ],
];
