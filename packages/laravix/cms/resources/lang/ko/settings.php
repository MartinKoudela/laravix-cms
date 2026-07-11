<?php

return [
    'singular' => '설정',
    'plural' => '설정',
    'tabs' => [
        'general' => '일반',
        'seo' => 'SEO',
        'social' => '소셜',
    ],
    'fields' => [
        'site_name' => '사이트 이름',
        'site_description' => '사이트 설명',
        'site_logo' => '사이트 로고',
        'favicon' => '파비콘',
        'locale' => '언어',
        'contact_email' => '연락처 이메일',
        'meta_title' => '메타 제목',
        'meta_description' => '메타 설명',
        'og_image' => 'OG 이미지',
        'google_verification' => '구글 사이트 인증',
        'twitter' => 'X / 트위터',
        'linkedin' => 'LinkedIn',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'github' => 'GitHub',
        'robots_txt' => 'robots.txt',
    ],
    'actions' => [
        'save' => '설정 저장',
        'manage' => '설정 관리',
    ],
    'messages' => [
        'saved' => '설정이 저장되었습니다',
    ],
    'hints' => [
        'logo' => '헤더와 푸터에 표시됩니다.',
        'setting_key' => 'Unique setting key, e.g. "site_name", "google_analytics", "logo".',
        'setting_value' => 'The value for this setting.',
        'favicon' => '브라우저 탭 아이콘. 권장: 32×32 또는 64×64 PNG.',
        'contact_email' => '연락 양식과 거래 이메일에 사용됩니다.',
        'meta_title' => '콘텐츠 수준 제목이 없을 때 사용되는 기본 페이지 제목입니다.',
        'meta_description' => '기본 메타 설명 (최대 160자).',
        'og_image' => '소셜 공유를 위한 기본 Open Graph 이미지.',
        'locale' => '<html lang="">에 사용되는 언어 코드. 예: ko, en, de, fr.',
        'google_verification' => 'Google Search Console 메타 태그의 content 값을 붙여넣으세요.',
        'robots_txt' => '기본값(모두 허용)의 경우 비워 두세요. 사이트맵 URL은 항상 자동으로 추가됩니다.',
    ],
];
