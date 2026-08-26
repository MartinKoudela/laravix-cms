<?php

namespace App\Themes\SimpleBlog;

use App\Modules\ThemeEngine\AbstractTheme;

class SimpleBlog extends AbstractTheme
{

    public function getAssetsPath(): string
    {
        return public_path('themes/simple-blog');
    }
}
