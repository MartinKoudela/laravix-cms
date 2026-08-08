<?php

return [
    'singular' => 'pozvánka',
    'plural' => 'pozvánky',
    'fields' => [
        'invited_by' => 'Pozvánka od',
        'expires' => 'Platnosť',
    ],
    'accept' => [
        'title' => 'Bol si pozvaný',
        'intro' => 'Pripájaš sa k :site ako :role.',
        'name' => 'Celé meno',
        'password' => 'Heslo',
        'password_confirmation' => 'Heslo znova',
        'submit' => 'Prijať pozvánku',
        'expires' => 'Pozvánka vyprší :time.',
    ],
    'statuses' => [
        'accepted' => 'Prijaté',
        'pending' => 'Čaká',
    ],
];
