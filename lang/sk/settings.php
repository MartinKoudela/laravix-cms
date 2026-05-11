<?php

return [
    'singular' => 'nastavenie',
    'plural' => 'nastavenia',
    'tabs' => [
        'general' => 'Všeobecné',
        'seo' => 'SEO',
        'social' => 'Sociálne siete',
    ],
    'fields' => [
        'site_name' => 'Názov webu',
        'site_description' => 'Popis webu',
        'site_logo' => 'Logo webu',
        'favicon' => 'Favicon',
        'locale' => 'Jazyk',
        'contact_email' => 'Kontaktný e-mail',
        'meta_title' => 'Meta názov',
        'meta_description' => 'Meta popis',
        'og_image' => 'OG obrázok',
        'google_verification' => 'Google overenie',
        'twitter' => 'X / Twitter',
        'linkedin' => 'LinkedIn',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'github' => 'GitHub',
        'robots_txt' => 'robots.txt',
    ],
    'actions' => [
        'save' => 'Uložiť nastavenia',
        'manage' => 'Správa nastavení',
    ],
    'messages' => [
        'saved' => 'Nastavenia uložené',
    ],
    'hints' => [
        'logo' => 'Zobrazené v hlavičke a päte.',
        'setting_key' => 'Unique setting key, e.g. "site_name", "google_analytics", "logo".',
        'setting_value' => 'The value for this setting.',
        'favicon' => 'Ikona záložky prehliadača. Odporúčané: 32×32 alebo 64×64 PNG.',
        'contact_email' => 'Používa sa v kontaktných formulároch a transakčných e-mailoch.',
        'meta_title' => 'Predvolený názov stránky, ak nie je nastavený názov na úrovni obsahu.',
        'meta_description' => 'Predvolený meta popis (až 160 znakov).',
        'og_image' => 'Predvolený Open Graph obrázok pre zdieľanie na sociálnych sieťach.',
        'locale' => 'Kód jazyka použitý v <html lang="">. Napr. en, sk, de, fr.',
        'google_verification' => 'Vložte hodnotu atribútu content z meta tagu Google Search Console.',
        'robots_txt' => 'Nechajte prázdne pre predvolené nastavenie (Povoliť všetko). URL sitemapy je vždy pridaná automaticky.',
    ],
];
