<?php

return [
    'singular' => 'pozvánka',
    'plural' => 'pozvánky',
    'fields' => [
        'invited_by' => 'Pozvánka od',
        'expires' => 'Platnost',
    ],
    'accept' => [
        'title' => 'Byl jsi pozván',
        'intro' => 'Připojuješ se k :site jako :role.',
        'name' => 'Celé jméno',
        'password' => 'Heslo',
        'password_confirmation' => 'Heslo znovu',
        'submit' => 'Přijmout pozvánku',
        'expires' => 'Pozvánka vyprší :time.',
    ],
    'statuses' => [
        'accepted' => 'Přijato',
        'pending' => 'Čeká',
    ],
];
