<?php

/**
 * Laravix Docs Plugin — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Docs\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Services\SiteResolver;
use Laravix\Docs\Support\DocsTree;

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

        $results = Content::search($query)
            ->where('site_id', $site->id)
            ->where('type', 'doc')
            ->where('locale', $locale)
            ->take(10)
            ->get()
            ->load('taxonomies');

        $tree = new DocsTree($site, $locale);

        return response()->json([
            'data' => $results->map(fn (Content $doc) => [
                'title' => $doc->title,
                'url' => $tree->pathFor($doc, $default),
            ])->values(),
        ]);
    }
}
