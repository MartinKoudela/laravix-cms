<?php

return [
    'plural' => '内容',
    'singular' => '内容',
    'sections' => [
        'seo_group' => 'SEO',
        'publishing' => '发布设置',
        'taxonomies' => '分类法',
        'builder' => '页面构建器',
    ],
    'fields' => [
        'meta_title' => 'Meta标题',
        'meta_description' => 'Meta描述',
        'og_image' => 'OG图片',
        'body' => '正文',
        'hero_image' => '主图',
        'excerpt' => '摘要',
        'noindex' => '对搜索引擎隐藏',
    ],
    'stats' => [
        'recent' => '最新内容',
        'published' => '已发布',
        'published_description' => '已发布页面和文章',
        'drafts' => '草稿',
        'awaiting' => '待发布',
    ],
    'messages' => [
        'set_as_homepage' => '设为首页',
        'only_one_homepage' => '每个站点只能有一个内容作为首页。',
        'save_first_for_builder' => '请先保存内容以使用块构建器。',
        'builder_has_content' => '该页面有来自构建器的已保存内容。',
        'builder_no_content' => '该页面尚未有来自构建器的内容。',
    ],
    'types' => [
        'page' => '页面',
        'post' => '文章',
        'archive' => '归档',
    ],
    'hints' => [
        'meta_title' => '设置后将覆盖站点默认值。',
        'meta_description' => '最多160个字符。覆盖站点默认值。',
        'og_image' => '覆盖此页面的站点OG图片。',
        'field_key' => 'Unique identifier for this field, e.g. "price", "stock", "subtitle".',
        'field_value' => 'The value for this field. Plugins may use this data to extend content functionality.',
    ],
    'actions' => [
        'revert' => '恢复',
        'open_builder' => '打开构建器',
    ],
];
