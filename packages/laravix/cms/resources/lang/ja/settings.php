<?php

return [
    'singular' => '設定',
    'plural' => '設定',
    'tabs' => [
        'general' => '一般',
        'seo' => 'SEO',
        'social' => 'ソーシャルメディア',
    ],
    'fields' => [
        'site_name' => 'サイト名',
        'site_description' => 'サイトの説明',
        'site_logo' => 'サイトロゴ',
        'favicon' => 'ファビコン',
        'locale' => '言語',
        'contact_email' => '連絡先メール',
        'meta_title' => 'メタタイトル',
        'meta_description' => 'メタ説明',
        'og_image' => 'OG画像',
        'google_verification' => 'Google認証',
        'twitter' => 'X / Twitter',
        'linkedin' => 'LinkedIn',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'github' => 'GitHub',
        'robots_txt' => 'robots.txt',
    ],
    'actions' => [
        'save' => '設定を保存',
        'manage' => '設定を管理',
    ],
    'messages' => [
        'saved' => '設定が保存されました',
    ],
    'hints' => [
        'logo' => 'ヘッダーとフッターに表示されます。',
        'setting_key' => 'Unique setting key, e.g. "site_name", "google_analytics", "logo".',
        'setting_value' => 'The value for this setting.',
        'favicon' => 'ブラウザのタブアイコン。推奨: 32×32 または 64×64 PNG。',
        'contact_email' => 'お問い合わせフォームとトランザクションメールで使用されます。',
        'meta_title' => 'コンテンツレベルのタイトルが未設定の場合に使用されるデフォルトのページタイトル。',
        'meta_description' => 'デフォルトのメタ説明（最大160文字）。',
        'og_image' => 'ソーシャルシェア用のデフォルト Open Graph 画像。',
        'locale' => '<html lang=""> で使用する言語コード。例: ja, en, de, fr。',
        'google_verification' => 'Google Search Console のメタタグの content 値を貼り付けてください。',
        'robots_txt' => 'デフォルト（全て許可）の場合は空にしてください。サイトマップURLは常に自動的に追加されます。',
    ],
];
