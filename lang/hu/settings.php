<?php

return [
    'singular' => 'beállítás',
    'plural' => 'beállítások',
    'tabs' => [
        'general' => 'Általános',
        'seo' => 'SEO',
        'social' => 'Közösségi média',
    ],
    'fields' => [
        'site_name' => 'Webhely neve',
        'site_description' => 'Webhely leírása',
        'site_logo' => 'Webhely logója',
        'favicon' => 'Favicon',
        'locale' => 'Nyelv',
        'contact_email' => 'Kapcsolati e-mail',
        'meta_title' => 'Meta cím',
        'meta_description' => 'Meta leírás',
        'og_image' => 'OG kép',
        'google_verification' => 'Google ellenőrzés',
        'twitter' => 'X / Twitter',
        'linkedin' => 'LinkedIn',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'github' => 'GitHub',
        'robots_txt' => 'robots.txt',
    ],
    'actions' => [
        'save' => 'Beállítások mentése',
        'manage' => 'Beállítások kezelése',
    ],
    'messages' => [
        'saved' => 'Beállítások mentve',
    ],
    'hints' => [
        'logo' => 'Fejlécben és láblécben jelenik meg.',
        'setting_key' => 'Unique setting key, e.g. "site_name", "google_analytics", "logo".',
        'setting_value' => 'The value for this setting.',
        'favicon' => 'Böngésző fül ikon. Ajánlott: 32×32 vagy 64×64 PNG.',
        'contact_email' => 'Kapcsolati űrlapokon és tranzakciós e-mailekben használatos.',
        'meta_title' => 'Alapértelmezett oldal cím, ha nincs tartalom szintű cím megadva.',
        'meta_description' => 'Alapértelmezett meta leírás (legfeljebb 160 karakter).',
        'og_image' => 'Alapértelmezett Open Graph kép a közösségi megosztáshoz.',
        'locale' => 'Nyelvi kód a <html lang=""> elemhez. Pl. en, hu, de, fr.',
        'google_verification' => 'Illessze be a Google Search Console meta tag content értékét.',
        'robots_txt' => 'Hagyja üresen az alapértelmezetthez (Mindent engedélyez). A sitemap URL mindig automatikusan hozzáadódik.',
    ],
];
