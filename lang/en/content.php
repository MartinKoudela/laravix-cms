<?php

return [
    'singular' => 'content',
    'plural' => 'contents',
    'sections' => [
        'content' => 'Content',
        'publishing' => 'Publishing',
        'taxonomies' => 'Taxonomies',
        'builder' => 'Builder',
        'seo_group' => 'SEO',
    ],
    'types' => [
        'page' => 'Page',
        'post' => 'Post',
        'archive' => 'Archive',
    ],
    'types_plural' => [
        'page' => 'Pages',
        'post' => 'Posts',
        'archive' => 'Archives',
    ],
    'fields' => [
        'locale' => 'Language',
        'body' => 'Body',
        'hero_image' => 'Hero Image',
        'excerpt' => 'Excerpt',
        'meta_title' => 'Meta Title',
        'meta_description' => 'Meta Description',
        'og_image' => 'OG Image',
        'noindex' => 'Hide from search engines',
    ],
    'hints' => [
        'meta_title' => 'Overrides the site-wide default when set.',
        'meta_description' => 'Up to 160 characters. Overrides the site-wide default.',
        'og_image' => 'Overrides the site-wide OG image for this page.',
        'field_key' => 'Unique identifier for this field, e.g. "price", "stock", "subtitle".',
        'field_value' => 'The value for this field. Plugins may use this data to extend content functionality.',
    ],
    'actions' => [
        'translate' => 'Translate',
        'revert' => 'Revert',
        'open_builder' => 'Open Builder',
    ],
    'messages' => [
        'translation_created' => 'Translation created',
        'set_as_homepage' => 'Set as homepage',
        'only_one_homepage' => 'Only one content per site can be the homepage.',
        'save_first_for_builder' => 'Save the content first to use the block builder.',
        'builder_has_content' => 'The page has saved content from the builder.',
        'builder_no_content' => 'The page has no builder content yet.',
    ],
    'stats' => [
        'recent' => 'Recent Content',
        'published' => 'Published',
        'published_description' => 'Published pages & posts',
        'drafts' => 'Drafts',
        'awaiting' => 'Awaiting publication',
    ],
];
