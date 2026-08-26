<?php

namespace App\Modules\ThemeEngine;

namespace App\Modules\ThemeEngine;

use App\Modules\ThemeEngine\Repositories\ThemeRepository;
use App\Modules\ThemeEngine\Services\ThemeManager;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ThemeRepository::class);

        $this->app->singleton('theme.manager', function ($app) {
            return new ThemeManager($app->make(ThemeRepository::class));
        });
    }

    public function boot(): void
    {
        $repository = $this->app->make(ThemeRepository::class);
        $themesPath = app_path('Themes');

        if (!is_dir($themesPath)) {
            return;
        }

        foreach (scandir($themesPath) as $folder) {
            if ($folder === '.' || $folder === '..') {
                continue;
            }

            $themeClass = "App\\Themes\\{$folder}\\{$folder}";

            if (class_exists($themeClass) && is_subclass_of($themeClass, AbstractTheme::class)) {
                /** @var AbstractTheme $theme */
                $theme = new $themeClass();

                $repository->register($theme);

                $viewsPath = $theme->getViewsPath();
                $name = $theme->getName(); // SimpleBlog

                if (is_dir($viewsPath)) {
                    // Registrace View namespace -> SimpleBlog::...
                    View::addNamespace($name, $viewsPath);

                    // Registrace komponent -> <x-SimpleBlog::header />
                    // app/Modules/ThemeEngine/ThemeServiceProvider.php

                    if (is_dir($viewsPath)) {
                        // 1. Klasická views (nyní funguje view('SimpleBlog::entities.post.index'))
                        View::addNamespace($name, $viewsPath);

                        // 2. Anonymní komponenty (nyní najde <x-SimpleBlog::layouts.app> i <x-SimpleBlog::components.header>)
                        Blade::anonymousComponentPath($viewsPath, $name);
                    }

                }
            }
        }
    }
}
