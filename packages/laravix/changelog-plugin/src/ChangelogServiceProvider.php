<?php

/**
 * Laravix Changelog Plugin — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Changelog;

use App\Support\BlockRegistry;
use App\Support\FilamentPluginRegistry;
use App\Support\HydratorRegistry;
use App\Support\RouteRegistry;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravix\Changelog\Blocks\ChangelogBlock;
use Laravix\Changelog\Http\Controllers\ChangelogController;

class ChangelogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        FilamentPluginRegistry::register(ChangelogPlugin::make());
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'changelog');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'changelog');

        BlockRegistry::register(ChangelogBlock::definition());
        HydratorRegistry::register(ChangelogHydrator::class);

        RouteRegistry::register(function () {
            Route::get('/changelog', ChangelogController::class)->name('changelog');
            Route::get('/{locale}/changelog', ChangelogController::class)
                ->where('locale', '[a-z]{2}')->name('changelog.localized');
        });
    }
}
