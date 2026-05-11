<?php

return [
    'singular' => 'إعداد',
    'plural' => 'الإعدادات',
    'tabs' => [
        'general' => 'عام',
        'seo' => 'SEO',
        'social' => 'التواصل الاجتماعي',
    ],
    'fields' => [
        'site_name' => 'اسم الموقع',
        'site_description' => 'وصف الموقع',
        'site_logo' => 'شعار الموقع',
        'favicon' => 'أيقونة الموقع',
        'locale' => 'اللغة',
        'contact_email' => 'البريد للتواصل',
        'meta_title' => 'العنوان التعريفي',
        'meta_description' => 'الوصف التعريفي',
        'og_image' => 'صورة OG',
        'google_verification' => 'التحقق من جوجل',
        'twitter' => 'X / تويتر',
        'linkedin' => 'لينكدإن',
        'facebook' => 'فيسبوك',
        'instagram' => 'إنستغرام',
        'github' => 'جيت هاب',
        'robots_txt' => 'robots.txt',
    ],
    'actions' => [
        'save' => 'حفظ الإعدادات',
        'manage' => 'إدارة الإعدادات',
    ],
    'messages' => [
        'saved' => 'تم حفظ الإعدادات',
    ],
    'hints' => [
        'logo' => 'يظهر في الرأس والتذييل.',
        'setting_key' => 'Unique setting key, e.g. "site_name", "google_analytics", "logo".',
        'setting_value' => 'The value for this setting.',
        'favicon' => 'أيقونة تبويب المتصفح. يُنصح باستخدام: PNG بحجم 32×32 أو 64×64.',
        'contact_email' => 'يُستخدم في نماذج الاتصال والبريد الإلكتروني التعاملي.',
        'meta_title' => 'عنوان الصفحة الافتراضي عند عدم تعيين عنوان على مستوى المحتوى.',
        'meta_description' => 'وصف meta الافتراضي (حتى 160 حرفاً).',
        'og_image' => 'صورة Open Graph الافتراضية للمشاركة على وسائل التواصل الاجتماعي.',
        'locale' => 'رمز اللغة المستخدم في <html lang="">. مثال: ar, en, de, fr.',
        'google_verification' => 'الصق قيمة content من وسم meta الخاص بـ Google Search Console.',
        'robots_txt' => 'اتركه فارغاً للإعداد الافتراضي (السماح للجميع). يُضاف رابط خريطة الموقع دائماً تلقائياً.',
    ],
];
