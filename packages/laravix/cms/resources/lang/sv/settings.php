<?php

return [
    'singular' => 'inställning',
    'plural' => 'inställningar',
    'tabs' => [
        'general' => 'Allmänt',
        'seo' => 'SEO',
        'social' => 'Sociala medier',
    ],
    'fields' => [
        'site_name' => 'Webbplatsnamn',
        'site_description' => 'Webbplatsbeskrivning',
        'site_logo' => 'Webbplatslogotyp',
        'favicon' => 'Favicon',
        'locale' => 'Språk',
        'contact_email' => 'Kontakt-e-post',
        'meta_title' => 'Metatitel',
        'meta_description' => 'Metabeskrivning',
        'og_image' => 'OG-bild',
        'google_verification' => 'Google-verifiering',
        'twitter' => 'X / Twitter',
        'linkedin' => 'LinkedIn',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'github' => 'GitHub',
        'robots_txt' => 'robots.txt',
    ],
    'actions' => [
        'save' => 'Spara inställningar',
        'manage' => 'Hantera inställningar',
    ],
    'messages' => [
        'saved' => 'Inställningar sparade',
    ],
    'hints' => [
        'logo' => 'Visas i sidhuvud och sidfot.',
        'setting_key' => 'Unique setting key, e.g. "site_name", "google_analytics", "logo".',
        'setting_value' => 'The value for this setting.',
        'favicon' => 'Webbläsarflik-ikon. Rekommenderas: 32×32 eller 64×64 PNG.',
        'contact_email' => 'Används i kontaktformulär och transaktionella e-postmeddelanden.',
        'meta_title' => 'Standard sidtitel som används när ingen innehållsnivå-titel är inställd.',
        'meta_description' => 'Standard meta-beskrivning (upp till 160 tecken).',
        'og_image' => 'Standard Open Graph-bild för delning i sociala medier.',
        'locale' => 'Språkkod som används i <html lang="">. T.ex. en, sv, de, fr.',
        'google_verification' => 'Klistra in content-värdet från Google Search Console meta-taggen.',
        'robots_txt' => 'Lämna tomt för standard (Tillåt allt). Sitemap-URL läggs alltid till automatiskt.',
    ],
];
