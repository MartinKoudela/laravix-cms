<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

use Illuminate\Support\Facades\Route;
use Laravix\Cms\Http\Controllers\Api\V1\NavigationController;
use Laravix\Cms\Http\Controllers\Api\V1\PageController;
use Laravix\Cms\Http\Controllers\Api\V1\PostController;
use Laravix\Cms\Http\Controllers\Api\V1\SearchController;
use Laravix\Cms\Http\Controllers\Api\V1\SettingsController;
use Laravix\Cms\Http\Controllers\Api\V1\TaxonomyController;

Route::middleware(['api.site', 'api.token', 'throttle:api'])->prefix('v1')->group(function (): void {
    Route::get('/pages', [PageController::class, 'index']);
    Route::get('/pages/homepage', [PageController::class, 'homepage']);
    Route::get('/pages/{slug}', [PageController::class, 'show']);

    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{slug}', [PostController::class, 'show']);

    Route::get('/taxonomies', [TaxonomyController::class, 'index']);

    Route::get('/settings', [SettingsController::class, 'index']);

    Route::get('/navigation', [NavigationController::class, 'index']);

    Route::get('/search', SearchController::class);
});
