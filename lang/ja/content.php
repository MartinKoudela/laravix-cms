<?php

return [
    'plural' => 'コンテンツ',
    'singular' => 'コンテンツ',
    'sections' => [
        'seo_group' => 'SEO',
        'publishing' => '公開設定',
        'taxonomies' => 'タクソノミー',
        'builder' => 'ビルダー',
    ],
    'fields' => [
        'meta_title' => 'メタタイトル',
        'meta_description' => 'メタ説明',
        'og_image' => 'OG画像',
        'body' => '本文',
        'hero_image' => 'メイン画像',
        'excerpt' => '抜粋',
        'noindex' => '検索エンジンから非表示',
    ],
    'stats' => [
        'recent' => '最新コンテンツ',
        'published' => '公開済み',
        'published_description' => '公開済みページと投稿',
        'drafts' => '下書き',
        'awaiting' => '公開待ち',
    ],
    'messages' => [
        'set_as_homepage' => 'ホームページに設定',
        'only_one_homepage' => 'サイトごとにホームページに設定できるコンテンツは1つだけです。',
    ],
    'types' => [
        'page' => 'ページ',
        'post' => '投稿',
        'archive' => 'アーカイブ',
    ],
    'hints' => [
        'meta_title' => '設定するとサイト全体のデフォルトを上書きします。',
        'meta_description' => '最大160文字。サイト全体のデフォルトを上書きします。',
        'og_image' => 'このページのサイト全体のOG画像を上書きします。',
        'field_key' => 'Unique identifier for this field, e.g. "price", "stock", "subtitle".',
        'field_value' => 'The value for this field. Plugins may use this data to extend content functionality.',
    ],
    'actions' => [
        'revert' => '元に戻す',
    ],
];
