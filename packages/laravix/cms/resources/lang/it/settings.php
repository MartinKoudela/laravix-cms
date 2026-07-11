<?php

return [
    'singular' => 'impostazione',
    'plural' => 'impostazioni',
    'tabs' => [
        'general' => 'Generale',
        'seo' => 'SEO',
        'social' => 'Social network',
    ],
    'fields' => [
        'site_name' => 'Nome del sito',
        'site_description' => 'Descrizione del sito',
        'site_logo' => 'Logo del sito',
        'favicon' => 'Favicon',
        'locale' => 'Lingua',
        'contact_email' => 'E-mail di contatto',
        'meta_title' => 'Meta titolo',
        'meta_description' => 'Meta descrizione',
        'og_image' => 'Immagine OG',
        'google_verification' => 'Verifica Google',
        'twitter' => 'X / Twitter',
        'linkedin' => 'LinkedIn',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'github' => 'GitHub',
        'robots_txt' => 'robots.txt',
    ],
    'actions' => [
        'save' => 'Salva impostazioni',
        'manage' => 'Gestisci impostazioni',
    ],
    'messages' => [
        'saved' => 'Impostazioni salvate',
    ],
    'hints' => [
        'logo' => 'Visualizzato nell\'intestazione e nel piè di pagina.',
        'setting_key' => 'Unique setting key, e.g. "site_name", "google_analytics", "logo".',
        'setting_value' => 'The value for this setting.',
        'favicon' => 'Icona della scheda del browser. Consigliato: PNG 32×32 o 64×64.',
        'contact_email' => 'Usato nei moduli di contatto e nelle e-mail transazionali.',
        'meta_title' => 'Titolo pagina predefinito quando non è impostato un titolo a livello di contenuto.',
        'meta_description' => 'Meta descrizione predefinita (fino a 160 caratteri).',
        'og_image' => 'Immagine Open Graph predefinita per la condivisione sui social.',
        'locale' => 'Codice lingua usato in <html lang="">. Es. en, it, de, fr.',
        'google_verification' => 'Incolla il valore content del meta tag di Google Search Console.',
        'robots_txt' => 'Lasciare vuoto per impostazione predefinita (Consenti tutto). L\'URL della sitemap viene sempre aggiunta automaticamente.',
    ],
];
