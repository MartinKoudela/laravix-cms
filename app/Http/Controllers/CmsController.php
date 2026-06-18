<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Http\Controllers;

use App\Services\ContentResolver;
use App\Services\PageDataBuilder;
use App\Services\SeoBuilder;
use App\Services\SiteResolver;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CmsController extends Controller
{
    public function __construct(
        private readonly SiteResolver $siteResolver,
        private readonly ContentResolver $contentResolver,
        private readonly PageDataBuilder $pageDataBuilder,
        private readonly SeoBuilder $seoBuilder,
    ) {}

    public function show(Request $request, string $slug = '/'): View
    {
        $site = $this->siteResolver->resolve($request->getHost());

        abort_if($site->isHeadless(), 404);

        $content = $this->contentResolver->resolve($site, $slug);

        $theme = $site->theme ?? 'default';
        $view = "themes.{$theme}::{$content->type}.show";
        if (! view()->exists($view)) {
            $view = "themes.{$theme}::default";
        }

        $data = $this->pageDataBuilder->build($site, $content);

        $contentFields = $content->fields->pluck('value', 'key');
        $ogImageId = (int) ($contentFields->get('og_image') ?: $data['settings']->get('og_image'));
        $ogMedia = $ogImageId ? $data['mediaMap']->get($ogImageId) : null;

        $seo = $this->seoBuilder->build($contentFields, $data['settings'], $content, $ogMedia);

        return view($view, array_merge($data, compact('content', 'site', 'seo')));
    }
}
