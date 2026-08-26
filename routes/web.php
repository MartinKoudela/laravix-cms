<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

use App\Modules\ThemeEngine\Facades\Theme;
use App\Modules\ThemeEngine\Http\Controllers\ThemeViewController;
use Illuminate\Support\Facades\Route;

// Statické systemové trasy (např. admin, auth atd.) musí být výše!


// Catch-All trasa – musí být zapsána až pod všemi systémovými trasami
Route::get('/{fallbackPlaceholder?}', [ThemeViewController::class, 'handle'])
    ->where('fallbackPlaceholder', '.*');

/*
Route::get('/blog', [ThemeViewController::class, 'index']);
Route::get('/{entity}/{slug}', [ThemeViewController::class, 'show']);
// ->middleware(SwitchThemeMiddleware::class . ':DarkTheme');
*/
