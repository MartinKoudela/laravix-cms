<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\View\View;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Services\PageDataBuilder;
use Laravix\Cms\Services\SeoBuilder;

class PreviewController extends Controller
{
    public function __construct(
        private readonly PageDataBuilder $pageDataBuilder,
        private readonly SeoBuilder $seoBuilder,
    ) {}

    private function loading(): Response
    {
        return response(view('laravix::preview-loading'));
    }

    public function nav(string $token): View|Response
    {
        $cached = cache()->get("preview_nav_{$token}");

        if (! $cached) {
            return $this->loading();
        }

        $site = Site::findOrFail($cached['site_id']);

        $content = Content::where('site_id', $site->id)
            ->where('status', 'published')
            ->where('is_homepage', true)
            ->with(['fields', 'taxonomies'])
            ->first()
            ?? Content::where('site_id', $site->id)
                ->where('status', 'published')
                ->with(['fields', 'taxonomies'])
                ->first();

        abort_if(! $content, 404);

        $data = $this->pageDataBuilder->build($site, $content);
        $data['navigations'] = $cached['navigations'];
        $data['navDesign'] = $cached['nav_design'] ?? [];

        $seo = $this->buildSeo($content, $data);

        $theme = $site->theme ?? 'default';
        $navPartView = "themes.{$theme}::preview.nav";
        if (request()->has('part') && view()->exists($navPartView)) {
            return view($navPartView, array_merge($data, compact('content', 'site', 'seo')));
        }

        return view($this->resolveView($site, $content), array_merge($data, compact('content', 'site', 'seo')));
    }

    public function blocks(string $token): View|Response
    {
        $cached = cache()->get("preview_blocks_{$token}");

        if (! $cached) {
            return $this->loading();
        }

        $content = Content::with(['fields', 'taxonomies', 'site'])->findOrFail($cached['content_id']);
        $content->blocks = $cached['blocks'];
        $site = $content->site;

        $data = $this->pageDataBuilder->build($site, $content);

        $seo = $this->buildSeo($content, $data);

        return view($this->resolveView($site, $content), array_merge($data, compact('content', 'site', 'seo')));
    }

    private function resolveView(Site $site, Content $content): string
    {
        $theme = $site->theme ?? 'default';
        $view = "themes.{$theme}::{$content->type}.show";

        return view()->exists($view) ? $view : "themes.{$theme}::default";
    }

    private function buildSeo(Content $content, array $data): mixed
    {
        $contentFields = $content->fields->pluck('value', 'key');
        $ogImageId = (int) ($contentFields->get('og_image') ?: $data['settings']->get('og_image'));
        $ogMedia = $ogImageId ? $data['mediaMap']->get($ogImageId) : null;

        return $this->seoBuilder->build($contentFields, $data['settings'], $content, $ogMedia);
    }
}
