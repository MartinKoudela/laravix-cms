<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravix\Cms\Console\Commands\CreateUser;
use Laravix\Cms\Console\Commands\PublishScheduledContent;
use Laravix\Cms\Http\Middleware\AuthenticateApiToken;
use Laravix\Cms\Http\Middleware\HandleRedirects;
use Laravix\Cms\Http\Middleware\ResolveSiteForApi;
use Laravix\Cms\Livewire\BlockEditor;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Media;
use Laravix\Cms\Models\Taxonomy;
use Laravix\Cms\Policies\ContentPolicy;
use Laravix\Cms\Policies\MediaPolicy;
use Laravix\Cms\Policies\TaxonomyPolicy;
use Laravix\Cms\Support\RouteRegistry;
use Livewire\Livewire;

class CmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravix');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravix');
    }

    public function boot(): void
    {
        $this->registerMiddleware();
        $this->registerRoutes();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName): string => 'Laravix\\Cms\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        Factory::guessModelNamesUsing(
            fn (Factory $factory): string => 'Laravix\\Cms\\Models\\'.Str::replaceLast('Factory', '', class_basename($factory))
        );

        Gate::policy(Content::class, ContentPolicy::class);
        Gate::policy(Media::class, MediaPolicy::class);
        Gate::policy(Taxonomy::class, TaxonomyPolicy::class);

        Livewire::component('block-editor', BlockEditor::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateUser::class,
                PublishScheduledContent::class,
            ]);
        }
    }

    private function registerMiddleware(): void
    {
        $router = $this->app['router'];

        $router->aliasMiddleware('api.site', ResolveSiteForApi::class);
        $router->aliasMiddleware('api.token', AuthenticateApiToken::class);
        $router->pushMiddlewareToGroup('web', HandleRedirects::class);
    }

    private function registerRoutes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware('web')->group(__DIR__.'/../routes/web.php');
        Route::middleware('api')->prefix('api')->group(__DIR__.'/../routes/api.php');

        $this->app->booted(function (): void {
            Route::middleware('web')->group(function (): void {
                RouteRegistry::apply();
            });

            Route::middleware('web')->group(__DIR__.'/../routes/cms.php');
        });
    }
}
