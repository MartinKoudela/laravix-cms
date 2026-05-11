<?php

return [
    'singular' => 'ayar',
    'plural' => 'ayarlar',
    'tabs' => [
        'general' => 'Genel',
        'seo' => 'SEO',
        'social' => 'Sosyal medya',
    ],
    'fields' => [
        'site_name' => 'Site adı',
        'site_description' => 'Site açıklaması',
        'site_logo' => 'Site logosu',
        'favicon' => 'Favicon',
        'locale' => 'Dil',
        'contact_email' => 'İletişim e-postası',
        'meta_title' => 'Meta başlık',
        'meta_description' => 'Meta açıklama',
        'og_image' => 'OG görseli',
        'google_verification' => 'Google doğrulama',
        'twitter' => 'X / Twitter',
        'linkedin' => 'LinkedIn',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'github' => 'GitHub',
        'robots_txt' => 'robots.txt',
    ],
    'actions' => [
        'save' => 'Ayarları kaydet',
        'manage' => 'Ayarları yönet',
    ],
    'messages' => [
        'saved' => 'Ayarlar kaydedildi',
    ],
    'hints' => [
        'logo' => 'Üstbilgi ve altbilgide görüntülenir.',
        'setting_key' => 'Unique setting key, e.g. "site_name", "google_analytics", "logo".',
        'setting_value' => 'The value for this setting.',
        'favicon' => 'Tarayıcı sekme simgesi. Önerilen: 32×32 veya 64×64 PNG.',
        'contact_email' => 'İletişim formlarında ve işlem e-postalarında kullanılır.',
        'meta_title' => 'İçerik düzeyinde başlık ayarlanmadığında kullanılan varsayılan sayfa başlığı.',
        'meta_description' => 'Varsayılan meta açıklaması (160 karaktere kadar).',
        'og_image' => 'Sosyal medya paylaşımı için varsayılan Open Graph görseli.',
        'locale' => '<html lang=""> etiketinde kullanılan dil kodu. Örn. en, tr, de, fr.',
        'google_verification' => 'Google Search Console meta etiketinden content değerini yapıştırın.',
        'robots_txt' => 'Varsayılan için boş bırakın (Hepsine izin ver). Site haritası URL\'si her zaman otomatik olarak eklenir.',
    ],
];
