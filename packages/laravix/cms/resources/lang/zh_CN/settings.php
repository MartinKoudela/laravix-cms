<?php

return [
    'singular' => '设置',
    'plural' => '设置',
    'tabs' => [
        'general' => '常规',
        'seo' => 'SEO',
        'social' => '社交媒体',
    ],
    'fields' => [
        'site_name' => '站点名称',
        'site_description' => '站点描述',
        'site_logo' => '站点Logo',
        'favicon' => '网站图标',
        'locale' => '语言',
        'contact_email' => '联系邮箱',
        'meta_title' => 'Meta标题',
        'meta_description' => 'Meta描述',
        'og_image' => 'OG图片',
        'google_verification' => 'Google验证',
        'twitter' => 'X / Twitter',
        'linkedin' => '领英',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'github' => 'GitHub',
        'robots_txt' => 'robots.txt',
    ],
    'actions' => [
        'save' => '保存设置',
        'manage' => '管理设置',
    ],
    'messages' => [
        'saved' => '设置已保存',
    ],
    'hints' => [
        'logo' => '显示在页眉和页脚中。',
        'setting_key' => 'Unique setting key, e.g. "site_name", "google_analytics", "logo".',
        'setting_value' => 'The value for this setting.',
        'favicon' => '浏览器标签图标。推荐：32×32 或 64×64 PNG。',
        'contact_email' => '用于联系表单和交易邮件。',
        'meta_title' => '当未设置内容级标题时使用的默认页面标题。',
        'meta_description' => '默认元描述（最多160个字符）。',
        'og_image' => '用于社交分享的默认 Open Graph 图片。',
        'locale' => '<html lang=""> 中使用的语言代码。例如：zh, en, de, fr。',
        'google_verification' => '粘贴 Google Search Console meta 标签的 content 值。',
        'robots_txt' => '留空表示默认（允许所有）。网站地图URL始终自动附加。',
    ],
];
