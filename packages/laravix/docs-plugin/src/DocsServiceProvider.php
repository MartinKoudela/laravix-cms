<?php

/**
 * Laravix Docs Plugin — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Docs;

use App\Support\ContentTypeDefinition;
use App\Support\ContentTypeRegistry;
use App\Support\RouteRegistry;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravix\Docs\Http\Controllers\DocsController;
use Laravix\Docs\Http\Controllers\DocsSearchController;

class DocsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'docs');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'docs');

        ContentTypeRegistry::register(
            ContentTypeDefinition::make('doc')
                ->label('docs::docs.types.doc')
                ->pluralLabel('docs::docs.types.docs')
                ->linkableInNavigation()
                ->routePrefix('docs'),
        );

        RouteRegistry::register(function () {
            Route::get('/docs', [DocsController::class, 'index'])->name('docs.index');
            Route::get('/docs/search', DocsSearchController::class)->name('docs.search');
            Route::get('/docs/{slug}', [DocsController::class, 'show'])->name('docs.show');

            Route::get('/{locale}/docs', [DocsController::class, 'index'])
                ->where('locale', '[a-z]{2}')->name('docs.index.localized');
            Route::get('/{locale}/docs/search', DocsSearchController::class)
                ->where('locale', '[a-z]{2}')->name('docs.search.localized');
            Route::get('/{locale}/docs/{slug}', [DocsController::class, 'show'])
                ->where('locale', '[a-z]{2}')->name('docs.show.localized');
        });
    }
}
