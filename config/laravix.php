<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

return [

    'cross_domain_switcher' => env('CMS_CROSS_DOMAIN_SWITCHER', false),

    'updates' => [
        'check' => env('CMS_UPDATE_CHECK', true),
        'endpoint' => 'https://repo.packagist.org/p2/laravix/cms.json',
        'cache_ttl' => 43200,
    ],

];
