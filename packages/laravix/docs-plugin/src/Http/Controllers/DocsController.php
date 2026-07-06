<?php

/**
 * Laravix Docs Plugin — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Docs\Http\Controllers;

use App\Models\Content;
use App\Models\Site;
use App\Services\PageDataBuilder;
use App\Services\SiteResolver;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DocsController
{
    public function __construct(
        private readonly SiteResolver $siteResolver,
        private readonly PageDataBuilder $pageDataBuilder,
    ) {}

    public function index(Request $request, ?string $locale = null): View
    {
        [$site, $locale] = $this->resolve($request, $locale);

        $grouped = $this->groupedDocs($site, $locale);

        $view = $this->themeView($site, 'docs.index', 'docs::index');

        return view($view, [
            'site' => $site,
            'locale' => $locale,
            'grouped' => $grouped,
            'docsUrl' => $this->docsUrl($site, $locale),
        ]);
    }

    public function show(Request $request, string $slug, ?string $locale = null): View
    {
        [$site, $locale] = $this->resolve($request, $locale);

        $doc = $this->publishedDocs($site, $locale)
            ->where('slug', $slug)
            ->with(['fields', 'taxonomies'])
            ->firstOrFail();

        $data = $this->pageDataBuilder->build($site, $doc);

        $grouped = $this->groupedDocs($site, $locale);

        $view = $this->themeView($site, 'docs.show', 'docs::show');

        return view($view, array_merge($data, [
            'site' => $site,
            'locale' => $locale,
            'doc' => $doc,
            'grouped' => $grouped,
            'docsUrl' => $this->docsUrl($site, $locale),
        ]));
    }

    private function resolve(Request $request, ?string $locale): array
    {
        $site = $this->siteResolver->resolve($request->getHost());

        $default = $site->defaultLocale();

        if ($locale === null) {
            $locale = $default;
        } else {
            abort_if($locale === $default || ! in_array($locale, $site->enabledLocales(), true), 404);
        }

        app()->setLocale($locale);

        return [$site, $locale];
    }

    private function groupedDocs(Site $site, string $locale)
    {
        return $this->publishedDocs($site, $locale)
            ->with('taxonomies')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get(['id', 'title', 'slug', 'locale', 'sort_order'])
            ->sortBy(fn (Content $doc) => $doc->taxonomies->first()?->sort_order ?? PHP_INT_MAX)
            ->groupBy(fn (Content $doc) => $doc->taxonomies->first()?->name ?? __('docs::docs.uncategorized'));
    }

    private function publishedDocs(Site $site, string $locale)
    {
        return Content::query()
            ->where('site_id', $site->id)
            ->where('type', 'doc')
            ->where('locale', $locale)
            ->where('status', 'published')
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    private function themeView(Site $site, string $themeSuffix, string $fallback): string
    {
        $theme = $site->theme ?? 'default';
        $view = "themes.{$theme}::{$themeSuffix}";

        return view()->exists($view) ? $view : $fallback;
    }

    private function docsUrl(Site $site, string $locale): string
    {
        return $locale === $site->defaultLocale() ? '/docs' : '/'.$locale.'/docs';
    }
}
