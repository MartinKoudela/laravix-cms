<?php

return [
    'plural' => '콘텐츠',
    'singular' => '콘텐츠',
    'sections' => [
        'seo_group' => 'SEO',
        'publishing' => '게시',
        'taxonomies' => '분류',
        'builder' => '빌더',
    ],
    'fields' => [
        'meta_title' => '메타 제목',
        'meta_description' => '메타 설명',
        'og_image' => 'OG 이미지',
        'body' => '본문',
        'hero_image' => '히어로 이미지',
        'excerpt' => '발췌',
        'noindex' => '검색 엔진에서 숨기기',
    ],
    'stats' => [
        'recent' => '최근 콘텐츠',
        'published' => '게시됨',
        'published_description' => '게시된 페이지 및 게시물',
        'drafts' => '초안',
        'awaiting' => '게시 대기 중',
    ],
    'messages' => [
        'set_as_homepage' => '홈페이지로 설정',
        'only_one_homepage' => '사이트당 하나의 콘텐츠만 홈페이지가 될 수 있습니다.',
    ],
    'types' => [
        'page' => '페이지',
        'post' => '게시물',
        'archive' => '아카이브',
    ],
    'hints' => [
        'meta_title' => '설정 시 사이트 전체 기본값을 재정의합니다.',
        'meta_description' => '최대 160자. 사이트 전체 기본값을 재정의합니다.',
        'og_image' => '이 페이지의 사이트 전체 OG 이미지를 재정의합니다.',
        'field_key' => 'Unique identifier for this field, e.g. "price", "stock", "subtitle".',
        'field_value' => 'The value for this field. Plugins may use this data to extend content functionality.',
    ],
    'actions' => [
        'revert' => '되돌리기',
    ],
];
