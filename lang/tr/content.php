<?php

return [
    'plural' => 'içerikler',
    'singular' => 'içerik',
    'sections' => [
        'seo_group' => 'SEO',
        'publishing' => 'Yayınlama',
        'taxonomies' => 'Taksonomiler',
        'builder' => 'Sayfa oluşturucu',
    ],
    'fields' => [
        'meta_title' => 'Meta başlık',
        'meta_description' => 'Meta açıklama',
        'og_image' => 'OG görseli',
        'body' => 'Gövde',
        'hero_image' => 'Ana görsel',
        'excerpt' => 'Özet',
        'noindex' => 'Arama motorlarından gizle',
    ],
    'stats' => [
        'recent' => 'Son içerikler',
        'published' => 'Yayınlandı',
        'published_description' => 'Yayınlanan sayfalar ve yazılar',
        'drafts' => 'Taslaklar',
        'awaiting' => 'Yayın bekliyor',
    ],
    'messages' => [
        'set_as_homepage' => 'Ana sayfa olarak ayarla',
        'only_one_homepage' => 'Her sitede yalnızca bir içerik ana sayfa olabilir.',
        'save_first_for_builder' => 'Blok oluşturucuyu kullanmak için önce içeriği kaydedin.',
        'builder_has_content' => 'Sayfanın oluşturucudan kaydedilmiş içeriği var.',
        'builder_no_content' => 'Sayfanın henüz oluşturucudan içeriği yok.',
    ],
    'types' => [
        'page' => 'Sayfa',
        'post' => 'Yazı',
        'archive' => 'Arşiv',
    ],
    'hints' => [
        'meta_title' => 'Ayarlandığında site geneli varsayılanı geçersiz kılar.',
        'meta_description' => 'En fazla 160 karakter. Site geneli varsayılanı geçersiz kılar.',
        'og_image' => 'Bu sayfa için site geneli OG görselini geçersiz kılar.',
        'field_key' => 'Unique identifier for this field, e.g. "price", "stock", "subtitle".',
        'field_value' => 'The value for this field. Plugins may use this data to extend content functionality.',
    ],
    'actions' => [
        'revert' => 'Geri Al',
        'open_builder' => 'Oluşturucuyu Aç',
    ],
];
