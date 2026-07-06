<?php

/**
 * Laravix Docs Plugin — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Docs\Http\Controllers;

use App\Models\Content;
use App\Services\SiteResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DocsSearchController
{
    public function __construct(private readonly SiteResolver $siteResolver) {}

    public function __invoke(Request $request, ?string $locale = null): JsonResponse
    {
        $site = $this->siteResolver->resolve($request->getHost());

        $default = $site->defaultLocale();

        if ($locale === null || ! in_array($locale, $site->enabledLocales(), true)) {
            $locale = $default;
        }

        $query = (string) $request->query('q', '');

        if (mb_strlen($query) < 2) {
            return response()->json(['data' => []]);
        }

        $prefix = $locale === $default ? '/docs' : '/'.$locale.'/docs';

        $results = Content::search($query)
            ->where('site_id', $site->id)
            ->where('type', 'doc')
            ->where('locale', $locale)
            ->take(10)
            ->get();

        return response()->json([
            'data' => $results->map(fn (Content $doc) => [
                'title' => $doc->title,
                'url' => $prefix.'/'.$doc->slug,
            ])->values(),
        ]);
    }
}
