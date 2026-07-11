<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravix\Cms\Console\Commands\CreateUser;
use Laravix\Cms\Console\Commands\PublishScheduledContent;
use Laravix\Cms\Livewire\BlockEditor;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Media;
use Laravix\Cms\Models\Taxonomy;
use Laravix\Cms\Policies\ContentPolicy;
use Laravix\Cms\Policies\MediaPolicy;
use Laravix\Cms\Policies\TaxonomyPolicy;
use Livewire\Livewire;

class CmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Factory::guessFactoryNamesUsing(
            fn (string $modelName): string => 'Database\\Factories\\'.class_basename($modelName).'Factory'
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
}
