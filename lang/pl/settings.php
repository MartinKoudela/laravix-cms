<?php

return [
    'singular' => 'ustawienie',
    'plural' => 'ustawienia',
    'tabs' => [
        'general' => 'Ogólne',
        'seo' => 'SEO',
        'social' => 'Media społecznościowe',
    ],
    'fields' => [
        'site_name' => 'Nazwa witryny',
        'site_description' => 'Opis witryny',
        'site_logo' => 'Logo witryny',
        'favicon' => 'Favicon',
        'locale' => 'Język',
        'contact_email' => 'E-mail kontaktowy',
        'meta_title' => 'Meta tytuł',
        'meta_description' => 'Meta opis',
        'og_image' => 'Obraz OG',
        'google_verification' => 'Weryfikacja Google',
        'twitter' => 'X / Twitter',
        'linkedin' => 'LinkedIn',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'github' => 'GitHub',
        'robots_txt' => 'robots.txt',
    ],
    'actions' => [
        'save' => 'Zapisz ustawienia',
        'manage' => 'Zarządzaj ustawieniami',
    ],
    'messages' => [
        'saved' => 'Ustawienia zapisane',
    ],
    'hints' => [
        'logo' => 'Wyświetlane w nagłówku i stopce.',
        'setting_key' => 'Unique setting key, e.g. "site_name", "google_analytics", "logo".',
        'setting_value' => 'The value for this setting.',
        'favicon' => 'Ikona karty przeglądarki. Zalecane: PNG 32×32 lub 64×64.',
        'contact_email' => 'Używane w formularzach kontaktowych i wiadomościach transakcyjnych.',
        'meta_title' => 'Domyślny tytuł strony gdy nie ustawiono tytułu na poziomie treści.',
        'meta_description' => 'Domyślny opis meta (do 160 znaków).',
        'og_image' => 'Domyślny obraz Open Graph do udostępniania w mediach społecznościowych.',
        'locale' => 'Kod języka używany w <html lang="">. Np. en, pl, de, fr.',
        'google_verification' => 'Wklej wartość atrybutu content z meta tagu Google Search Console.',
        'robots_txt' => 'Pozostaw puste dla domyślnego ustawienia (Zezwól na wszystko). Adres URL mapy witryny jest zawsze dodawany automatycznie.',
    ],
];
