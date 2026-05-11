<?php

return [
    'singular' => 'Einstellung',
    'plural' => 'Einstellungen',
    'tabs' => [
        'general' => 'Allgemein',
        'seo' => 'SEO',
        'social' => 'Soziale Netzwerke',
    ],
    'fields' => [
        'site_name' => 'Website-Name',
        'site_description' => 'Website-Beschreibung',
        'site_logo' => 'Website-Logo',
        'favicon' => 'Favicon',
        'locale' => 'Sprache',
        'contact_email' => 'Kontakt-E-Mail',
        'meta_title' => 'Meta-Titel',
        'meta_description' => 'Meta-Beschreibung',
        'og_image' => 'OG-Bild',
        'google_verification' => 'Google-Verifizierung',
        'twitter' => 'X / Twitter',
        'linkedin' => 'LinkedIn',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'github' => 'GitHub',
        'robots_txt' => 'robots.txt',
    ],
    'actions' => [
        'save' => 'Einstellungen speichern',
        'manage' => 'Einstellungen verwalten',
    ],
    'messages' => [
        'saved' => 'Einstellungen gespeichert',
    ],
    'hints' => [
        'logo' => 'Wird in Kopf- und Fußzeile angezeigt.',
        'setting_key' => 'Unique setting key, e.g. "site_name", "google_analytics", "logo".',
        'setting_value' => 'The value for this setting.',
        'favicon' => 'Browser-Tab-Icon. Empfohlen: 32×32 oder 64×64 PNG.',
        'contact_email' => 'Wird in Kontaktformularen und Transaktions-E-Mails verwendet.',
        'meta_title' => 'Standard-Seitentitel, wenn kein inhaltsspezifischer Titel festgelegt ist.',
        'meta_description' => 'Standard-Meta-Beschreibung (bis zu 160 Zeichen).',
        'og_image' => 'Standard Open-Graph-Bild für Social Sharing.',
        'locale' => 'Sprachcode in <html lang="">. Z. B. en, de, cs, fr.',
        'google_verification' => 'Fügen Sie den content-Wert des Google Search Console Meta-Tags ein.',
        'robots_txt' => 'Leer lassen für Standardeinstellung (Alles erlauben). Sitemap-URL wird immer automatisch hinzugefügt.',
    ],
];
