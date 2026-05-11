<?php

return [
    'singular' => 'setare',
    'plural' => 'setări',
    'tabs' => [
        'general' => 'General',
        'seo' => 'SEO',
        'social' => 'Rețele sociale',
    ],
    'fields' => [
        'site_name' => 'Numele site-ului',
        'site_description' => 'Descrierea site-ului',
        'site_logo' => 'Logo site',
        'favicon' => 'Favicon',
        'locale' => 'Limbă',
        'contact_email' => 'E-mail de contact',
        'meta_title' => 'Meta titlu',
        'meta_description' => 'Meta descriere',
        'og_image' => 'Imagine OG',
        'google_verification' => 'Verificare Google',
        'twitter' => 'X / Twitter',
        'linkedin' => 'LinkedIn',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'github' => 'GitHub',
        'robots_txt' => 'robots.txt',
    ],
    'actions' => [
        'save' => 'Salvează setările',
        'manage' => 'Gestionează setările',
    ],
    'messages' => [
        'saved' => 'Setări salvate',
    ],
    'hints' => [
        'logo' => 'Afișat în antet și subsol.',
        'setting_key' => 'Unique setting key, e.g. "site_name", "google_analytics", "logo".',
        'setting_value' => 'The value for this setting.',
        'favicon' => 'Iconiță tab browser. Recomandat: PNG 32×32 sau 64×64.',
        'contact_email' => 'Folosit în formularele de contact și e-mailuri tranzacționale.',
        'meta_title' => 'Titlul implicit al paginii când nu este setat un titlu la nivel de conținut.',
        'meta_description' => 'Descriere meta implicită (până la 160 de caractere).',
        'og_image' => 'Imagine Open Graph implicită pentru partajare pe rețele sociale.',
        'locale' => 'Codul de limbă utilizat în <html lang="">. Ex. en, ro, de, fr.',
        'google_verification' => 'Lipiți valoarea content din meta tag-ul Google Search Console.',
        'robots_txt' => 'Lăsați gol pentru implicit (Permite tot). URL-ul sitemap este întotdeauna adăugat automat.',
    ],
];
