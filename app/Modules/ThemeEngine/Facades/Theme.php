<?php

namespace App\Modules\ThemeEngine\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void set(string $slug)
 * @method static \App\Modules\ThemeEngine\Contracts\ThemeInterface|null current()
 * @method static \Illuminate\Contracts\View\View renderShow(string $entity, array $data = [])
 * @method static \Illuminate\Contracts\View\View renderIndex(string $entity, array $data = [])
 * @method static \Illuminate\Contracts\View\View render(string $view, array $data = [])
 */
class Theme extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'theme.manager';
    }
}

