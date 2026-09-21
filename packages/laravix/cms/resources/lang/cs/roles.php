<?php

return [
    'singular' => 'role',
    'plural' => 'role',
    'full_access' => 'Plný přístup',
    'types' => [
        'system' => 'Systémová',
        'custom' => 'Vlastní',
    ],
    'fields' => [
        'permissions' => 'Oprávnění',
        'preset' => 'Vycházet z',
    ],
    'sections' => [
        'permissions' => 'Oprávnění',
    ],
    'messages' => [
        'preset_hint' => 'Zkopíruje oprávnění systémové role jako výchozí bod.',
        'permissions_hint' => 'Zaškrtněte, co mohou členové této role na tomto webu dělat. Role správce má vždy plný přístup.',
        'in_use' => 'Tato role je stále přiřazena uživatelům nebo čekajícím pozvánkám a nelze ji smazat.',
        'cannot_grant' => 'Oprávnění :permission nemůžete udělit, protože ho sami nemáte.',
        'cannot_assign' => 'Nemůžete přiřadit roli s více oprávněními, než máte sami.',
        'last_admin' => 'Tento uživatel je jediným správcem webu. Nejprve povyšte někoho jiného.',
    ],
];
