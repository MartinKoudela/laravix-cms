<?php
return [
    // Výchozí stránka na kořenové URL "/"
    'home_page_id' => 1, // ID nebo slug úvodní stránky

    // Prefixy pro různé typy archivů / taxonomií
    'prefixes' => [
        'category' => 'kategorie',  // z /category/sport udělal /kategorie/sport
        'tag' => 'stitek',
        'product' => 'produkty',
    ],

    // Vlastní entitní pravidla
    'entities' => [
        'post' => 'clanky',
        'product' => 'obchod',
    ],
];
