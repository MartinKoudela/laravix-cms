<?php

return [
    'singular' => 'site',
    'plural' => 'sites',
    'fields' => [
        'users_count' => 'Users',
        'contents_count' => 'Contents',
        'mode' => 'Mode',
        'locales' => 'Additional languages',
    ],
    'modes' => [
        'theme' => 'Theme (SSR)',
        'headless' => 'Headless (API)',
    ],
    'messages' => [
        'public_name' => 'The public name of the website.',
    ],
    'actions' => [
        'create' => 'Create Site',
    ],
    'stats' => [
        'title' => 'Sites',
        'total' => 'Total managed sites',
    ],
    'hints' => [
        'locales' => 'Languages available for content besides the default site language.',
    ],
];
