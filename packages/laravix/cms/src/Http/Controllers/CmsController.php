<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Laravix\Cms\Enums\PlaceholderReason;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Services\ContentResolver;
use Laravix\Cms\Services\PageDataBuilder;
use Laravix\Cms\Services\PlaceholderResolver;
use Laravix\Cms\Services\SeoBuilder;
use Laravix\Cms\Services\SiteResolver;
use Laravix\Cms\Support\ContentTypeRegistry;

class CmsController extends Controller
{
    public function __construct(
        private readonly SiteResolver $siteResolver,
        private readonly ContentResolver $contentResolver,
        private readonly PageDataBuilder $pageDataBuilder,
        private readonly SeoBuilder $seoBuilder,
        private readonly PlaceholderResolver $placeholderResolver,
    ) {}

    public function show(Request $request, string $slug = '/'): View|RedirectResponse|Response
    {
        $site = $this->siteResolver->resolve($request->getHost());

        $defaultLocale = $site->defaultLocale();
        $locale = $defaultLocale;

        $segments = explode('/', trim($slug, '/'), 2);
        if ($segments[0] !== ''
            && $segments[0] !== $defaultLocale
            && in_array($segments[0], $site->enabledLocales(), true)) {
            $locale = $segments[0];
            $slug = $segments[1] ?? '/';
        }

        app()->setLocale($locale);

        if ($slug === '/') {
            $content = $this->contentResolver->find($site, $slug, $locale);

            if ($reason = $this->placeholderResolver->reasonFor($site, $content)) {
                return $this->placeholder($site, $reason);
            }
        } else {
            abort_if($site->isHeadless(), 404);

            $content = $this->contentResolver->resolve($site, $slug, $locale);
        }

        if (ContentTypeRegistry::find($content->type)?->routePrefix) {
            return redirect($content->path($defaultLocale), 301);
        }

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

    private function placeholder(Site $site, PlaceholderReason $reason): Response
    {
        $theme = $site->theme ?? 'default';
        $view = "themes.{$theme}::placeholder";

        if (! view()->exists($view)) {
            $view = 'laravix::placeholder';
        }

        return response()->view($view, [
            'site' => $site,
            'reason' => $reason,
            'showHint' => app()->isLocal(),
        ], $reason->status());
    }
}
