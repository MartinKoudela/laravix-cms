<?php

return [
    'plural' => 'محتويات',
    'singular' => 'محتوى',
    'sections' => [
        'seo_group' => 'SEO',
        'publishing' => 'النشر',
        'taxonomies' => 'التصنيفات',
        'builder' => 'منشئ الصفحات',
    ],
    'fields' => [
        'meta_title' => 'العنوان التعريفي',
        'meta_description' => 'الوصف التعريفي',
        'og_image' => 'صورة OG',
        'body' => 'المحتوى',
        'hero_image' => 'الصورة الرئيسية',
        'excerpt' => 'المقتطف',
        'noindex' => 'إخفاء عن محركات البحث',
    ],
    'stats' => [
        'recent' => 'المحتوى الأخير',
        'published' => 'منشور',
        'published_description' => 'الصفحات والمنشورات المنشورة',
        'drafts' => 'المسودات',
        'awaiting' => 'في انتظار النشر',
    ],
    'messages' => [
        'set_as_homepage' => 'تعيين كصفحة رئيسية',
        'only_one_homepage' => 'يمكن لمحتوى واحد فقط في كل موقع أن يكون الصفحة الرئيسية.',
        'save_first_for_builder' => 'احفظ المحتوى أولاً لاستخدام منشئ الكتل.',
        'builder_has_content' => 'تحتوي الصفحة على محتوى محفوظ من المنشئ.',
        'builder_no_content' => 'لا تحتوي الصفحة على محتوى من المنشئ بعد.',
    ],
    'types' => [
        'page' => 'صفحة',
        'post' => 'منشور',
        'archive' => 'أرشيف',
    ],
    'hints' => [
        'meta_title' => 'يتجاوز الإعداد الافتراضي للموقع عند التعيين.',
        'meta_description' => 'حتى 160 حرفًا. يتجاوز الإعداد الافتراضي للموقع.',
        'og_image' => 'يتجاوز صورة OG الافتراضية للموقع لهذه الصفحة.',
        'field_key' => 'Unique identifier for this field, e.g. "price", "stock", "subtitle".',
        'field_value' => 'The value for this field. Plugins may use this data to extend content functionality.',
    ],
    'actions' => [
        'revert' => 'استعادة',
        'open_builder' => 'فتح المنشئ',
    ],
];
