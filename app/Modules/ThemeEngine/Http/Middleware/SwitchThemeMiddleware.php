<?php

namespace App\Modules\ThemeEngine\Http\Middleware;

use App\Modules\ThemeEngine\Facades\Theme;
use Closure;
use Illuminate\Http\Request;

class SwitchThemeMiddleware
{
    public function handle(Request $request, Closure $next, string $themeSlug)
    {
        Theme::set($themeSlug);

        return $next($request);
    }
}
