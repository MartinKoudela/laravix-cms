<?php

return [
    'singular' => 'rola',
    'plural' => 'roly',
    'full_access' => 'Plný prístup',
    'types' => [
        'system' => 'Systémová',
        'custom' => 'Vlastná',
    ],
    'fields' => [
        'permissions' => 'Oprávnenia',
        'preset' => 'Vychádzať z',
    ],
    'sections' => [
        'permissions' => 'Oprávnenia',
    ],
    'messages' => [
        'preset_hint' => 'Skopíruje oprávnenia systémovej roly ako východiskový bod.',
        'permissions_hint' => 'Zaškrtnite, čo môžu členovia tejto roly na tomto webe robiť. Rola správcu má vždy plný prístup.',
        'in_use' => 'Táto rola je stále priradená používateľom alebo čakajúcim pozvánkam a nedá sa zmazať.',
        'cannot_grant' => 'Oprávnenie :permission nemôžete udeliť, pretože ho sami nemáte.',
        'cannot_assign' => 'Nemôžete priradiť rolu s viac oprávneniami, než máte sami.',
        'last_admin' => 'Tento používateľ je jediným správcom webu. Najprv povýšte niekoho iného.',
    ],
];
