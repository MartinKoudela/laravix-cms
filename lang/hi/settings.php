<?php

return [
    'singular' => 'सेटिंग',
    'plural' => 'सेटिंग्स',
    'tabs' => [
        'general' => 'सामान्य',
        'seo' => 'SEO',
        'social' => 'सोशल',
    ],
    'fields' => [
        'site_name' => 'साइट का नाम',
        'site_description' => 'साइट विवरण',
        'site_logo' => 'साइट लोगो',
        'favicon' => 'फ़ेविकॉन',
        'locale' => 'भाषा',
        'contact_email' => 'संपर्क ईमेल',
        'meta_title' => 'मेटा शीर्षक',
        'meta_description' => 'मेटा विवरण',
        'og_image' => 'OG छवि',
        'google_verification' => 'Google सत्यापन',
        'twitter' => 'X / ट्विटर',
        'linkedin' => 'LinkedIn',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'github' => 'GitHub',
        'robots_txt' => 'robots.txt',
    ],
    'actions' => [
        'save' => 'सेटिंग्स सहेजें',
        'manage' => 'सेटिंग्स प्रबंधित करें',
    ],
    'messages' => [
        'saved' => 'सेटिंग्स सहेजी गईं',
    ],
    'hints' => [
        'logo' => 'हेडर और फूटर में दिखाया जाता है।',
        'setting_key' => 'Unique setting key, e.g. "site_name", "google_analytics", "logo".',
        'setting_value' => 'The value for this setting.',
        'favicon' => 'ब्राउज़र टैब आइकन। अनुशंसित: 32×32 या 64×64 PNG।',
        'contact_email' => 'संपर्क फ़ॉर्म और लेनदेन ईमेल में उपयोग किया जाता है।',
        'meta_title' => 'डिफ़ॉल्ट पृष्ठ शीर्षक जब कोई सामग्री-स्तर शीर्षक सेट नहीं होता।',
        'meta_description' => 'डिफ़ॉल्ट मेटा विवरण (160 वर्णों तक)।',
        'og_image' => 'सोशल शेयरिंग के लिए डिफ़ॉल्ट Open Graph छवि।',
        'locale' => '<html lang=""> में उपयोग किया जाने वाला भाषा कोड। उदाहरण: hi, en, de, fr।',
        'google_verification' => 'Google Search Console मेटा टैग की content वैल्यू पेस्ट करें।',
        'robots_txt' => 'डिफ़ॉल्ट के लिए खाली छोड़ें (सभी को अनुमति दें)। Sitemap URL हमेशा स्वचालित रूप से जोड़ा जाता है।',
    ],
];
