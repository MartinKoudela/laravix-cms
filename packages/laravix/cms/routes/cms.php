<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

use Illuminate\Support\Facades\Route;
use Laravix\Cms\Http\Controllers\CmsController;

Route::get('/{slug?}', [CmsController::class, 'show'])
    ->where('slug', '.*')
    ->name('cms.show');
