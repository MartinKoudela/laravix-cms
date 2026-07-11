<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Http\Controllers\Api\V1;

use Laravix\Cms\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NavigationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $site = $request->attributes->get('site');

        return response()->json([
            'data' => [
                'navigations' => $site->navigations ?? [],
                'design' => $site->nav_design ?? [],
            ],
        ]);
    }
}
