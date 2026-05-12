<?php

return [
    'plural' => 'सामग्री',
    'singular' => 'सामग्री',
    'sections' => [
        'seo_group' => 'SEO',
        'publishing' => 'प्रकाशन',
        'taxonomies' => 'वर्गीकरण',
        'builder' => 'बिल्डर',
    ],
    'fields' => [
        'meta_title' => 'मेटा शीर्षक',
        'meta_description' => 'मेटा विवरण',
        'og_image' => 'OG छवि',
        'body' => 'मुख्य भाग',
        'hero_image' => 'हीरो छवि',
        'excerpt' => 'उद्धरण',
        'noindex' => 'खोज इंजन से छुपाएं',
    ],
    'stats' => [
        'recent' => 'हाल की सामग्री',
        'published' => 'प्रकाशित',
        'published_description' => 'प्रकाशित पृष्ठ और पोस्ट',
        'drafts' => 'ड्राफ्ट',
        'awaiting' => 'प्रकाशन की प्रतीक्षा में',
    ],
    'messages' => [
        'set_as_homepage' => 'होमपेज के रूप में सेट करें',
        'only_one_homepage' => 'प्रत्येक साइट पर केवल एक सामग्री होमपेज हो सकती है।',
        'save_first_for_builder' => 'ब्लॉक बिल्डर का उपयोग करने के लिए पहले सामग्री सहेजें।',
        'builder_has_content' => 'पृष्ठ में बिल्डर से सहेजी गई सामग्री है।',
        'builder_no_content' => 'पृष्ठ में अभी तक बिल्डर से कोई सामग्री नहीं है।',
    ],
    'types' => [
        'page' => 'पृष्ठ',
        'post' => 'पोस्ट',
        'archive' => 'संग्रह',
    ],
    'hints' => [
        'meta_title' => 'सेट होने पर साइट-व्यापी डिफ़ॉल्ट को ओवरराइड करता है।',
        'meta_description' => '160 अक्षर तक। साइट-व्यापी डिफ़ॉल्ट को ओवरराइड करता है।',
        'og_image' => 'इस पृष्ठ के लिए साइट-व्यापी OG छवि को ओवरराइड करता है।',
        'field_key' => 'Unique identifier for this field, e.g. "price", "stock", "subtitle".',
        'field_value' => 'The value for this field. Plugins may use this data to extend content functionality.',
    ],
    'actions' => [
        'revert' => 'वापस करें',
        'open_builder' => 'बिल्डर खोलें',
    ],
];
