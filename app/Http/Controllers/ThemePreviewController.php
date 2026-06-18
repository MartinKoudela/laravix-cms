<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Http\Controllers;

use App\Models\Site;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ThemePreviewController extends Controller
{
    public function show(string $theme): BinaryFileResponse
    {
        if (! array_key_exists($theme, Site::availableThemes())) {
            throw new NotFoundHttpException;
        }

        $path = Site::themePreviewPath($theme);

        if (! $path) {
            throw new NotFoundHttpException;
        }

        return response()->file($path, [
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
