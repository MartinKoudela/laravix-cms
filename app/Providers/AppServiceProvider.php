<?php

namespace App\Providers;

use App\Enums\FieldType;
use App\Support\FieldDefinition;
use App\Support\FieldRegistry;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {

        FieldRegistry::content([
            FieldDefinition::make('body')->type(FieldType::RICH_TEXT)->label('Body'),
            FieldDefinition::make('hero_image')->type(FieldType::IMAGE)->label('Hero Image'),
            FieldDefinition::make('excerpt')->type(FieldType::TEXTAREA)->label('Excerpt'),
        ]);

        foreach (glob(base_path('themes/*'), GLOB_ONLYDIR) as $themePath) {
            $themeName = basename($themePath);
            View::addNamespace("themes.{$themeName}", "{$themePath}/views");
        }
    }
}
