<?php

/**
 * Laravix Docs Plugin — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Docs\Http\Controllers;

use App\Models\Content;
use App\Models\Site;
use App\Models\Taxonomy;
use App\Services\PageDataBuilder;
use App\Services\SiteResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Laravix\Docs\MarkdownRenderer;
use Laravix\Docs\Support\DocsTree;

class DocsController
{
    public function __construct(
        private readonly SiteResolver $siteResolver,
        private readonly PageDataBuilder $pageDataBuilder,
        private readonly MarkdownRenderer $markdown,
    ) {}

    public function index(Request $request, ?string $locale = null): View
    {
        [$site, $locale] = $this->resolve($request, $locale);

        $tree = new DocsTree($site, $locale);
        $docs = $this->sidebarDocs($site, $locale);
        $defaultLocale = $site->defaultLocale();

        $sections = $tree->sections()
            ->map(fn (Taxonomy $section) => [
                'label' => $section->localizedName($locale),
                'url' => $tree->sectionPath($section, $defaultLocale),
                'groups' => $tree->groupedForSection(
                    $section,
                    $docs->filter(fn (Content $doc) => $tree->sectionFor($doc)?->id === $section->id),
                ),
            ])
            ->filter(fn (array $section) => $section['groups']->isNotEmpty())
            ->values();

        $ungrouped = $docs
            ->filter(fn (Content $doc) => $tree->sectionFor($doc) === null)
            ->values();

        $view = $this->themeView($site, 'docs.index', 'docs::index');

        return view($view, [
            'site' => $site,
            'locale' => $locale,
            'sections' => $sections,
            'ungrouped' => $ungrouped,
            'docsUrl' => $this->docsUrl($site, $locale),
            'docUrl' => fn (Content $doc) => $tree->pathFor($doc, $defaultLocale),
        ]);
    }

    public function show(Request $request, string $slug): View|RedirectResponse
    {
        return $this->handleShow($request, null, $slug);
    }

    public function showLocalized(Request $request, string $locale, string $slug): View|RedirectResponse
    {
        return $this->handleShow($request, $locale, $slug);
    }

    public function showInSection(Request $request, string $section, string $slug): View|RedirectResponse
    {
        return $this->handleShowInSection($request, null, $section, $slug);
    }

    public function showInSectionLocalized(Request $request, string $locale, string $section, string $slug): View|RedirectResponse
    {
        return $this->handleShowInSection($request, $locale, $section, $slug);
    }

    private function handleShow(Request $request, ?string $locale, string $slug): View|RedirectResponse
    {
        [$site, $locale] = $this->resolve($request, $locale);

        $tree = new DocsTree($site, $locale);
        $defaultLocale = $site->defaultLocale();

        if ($section = $tree->resolveSection($slug)) {
            $first = $this->sectionDocs($site, $locale, $tree, $section)->first();

            abort_unless($first, 404);

            return redirect($tree->pathFor($first, $defaultLocale));
        }

        $doc = $this->findDoc($site, $locale, $slug);

        if ($tree->sectionFor($doc)) {
            return redirect($tree->pathFor($doc, $defaultLocale), 301);
        }

        return $this->renderDoc($site, $locale, $tree, null, $doc);
    }

    private function handleShowInSection(Request $request, ?string $locale, string $sectionSlug, string $slug): View|RedirectResponse
    {
        [$site, $locale] = $this->resolve($request, $locale);

        $tree = new DocsTree($site, $locale);
        $defaultLocale = $site->defaultLocale();

        $section = $tree->resolveSection($sectionSlug);
        abort_unless($section, 404);

        $doc = $this->findDoc($site, $locale, $slug);

        if ($tree->sectionFor($doc)?->id !== $section->id) {
            return redirect($tree->pathFor($doc, $defaultLocale), 301);
        }

        return $this->renderDoc($site, $locale, $tree, $section, $doc);
    }

    private function renderDoc(Site $site, string $locale, DocsTree $tree, ?Taxonomy $section, Content $doc): View
    {
        $defaultLocale = $site->defaultLocale();

        $data = $this->pageDataBuilder->build($site, $doc);

        $body = $doc->fields->firstWhere('key', 'body')?->value;
        $contentHtml = filled($body) ? $this->markdown->toHtml($body) : ($data['grapesjsHtml'] ?? '');
        $toc = $this->markdown->toc($contentHtml);

        $sidebarDocs = $section
            ? $this->sectionDocs($site, $locale, $tree, $section)
            : $this->sidebarDocs($site, $locale)->filter(fn (Content $d) => $tree->sectionFor($d) === null)->values();

        $grouped = $section
            ? $tree->groupedForSection($section, $sidebarDocs)
            : collect([[
                'label' => __('docs::docs.uncategorized'),
                'docs' => $sidebarDocs,
            ]])->filter(fn (array $group) => $group['docs']->isNotEmpty())->values();

        $flat = $grouped->flatMap(fn (array $group) => $group['docs'])->values();
        $currentIndex = $flat->search(fn (Content $d) => $d->id === $doc->id);
        $prev = $currentIndex !== false && $currentIndex > 0 ? $flat[$currentIndex - 1] : null;
        $next = $currentIndex !== false && $currentIndex < $flat->count() - 1 ? $flat[$currentIndex + 1] : null;

        $sections = $tree->sections()->map(fn (Taxonomy $s) => [
            'label' => $s->localizedName($locale),
            'url' => $tree->sectionPath($s, $defaultLocale),
            'active' => $section?->id === $s->id,
        ]);

        $view = $this->themeView($site, 'docs.show', 'docs::show');

        return view($view, array_merge($data, [
            'site' => $site,
            'locale' => $locale,
            'doc' => $doc,
            'section' => $section,
            'sections' => $sections,
            'grouped' => $grouped,
            'contentHtml' => $contentHtml,
            'toc' => $toc,
            'prev' => $prev,
            'next' => $next,
            'docsUrl' => $this->docsUrl($site, $locale),
            'docUrl' => fn (Content $d) => $tree->pathFor($d, $defaultLocale),
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

    private function findDoc(Site $site, string $locale, string $slug): Content
    {
        return $this->publishedDocs($site, $locale)
            ->where('slug', $slug)
            ->with(['fields', 'taxonomies'])
            ->firstOrFail();
    }

    private function sectionDocs(Site $site, string $locale, DocsTree $tree, Taxonomy $section): Collection
    {
        $subtreeIds = $tree->subtreeIds($section);

        return $this->publishedDocs($site, $locale)
            ->whereHas('taxonomies', fn ($q) => $q->whereIn('taxonomies.id', $subtreeIds))
            ->with('taxonomies')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get(['id', 'title', 'slug', 'locale', 'type', 'sort_order']);
    }

    private function sidebarDocs(Site $site, string $locale): Collection
    {
        return $this->publishedDocs($site, $locale)
            ->with('taxonomies')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get(['id', 'title', 'slug', 'locale', 'type', 'sort_order']);
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
