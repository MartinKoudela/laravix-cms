<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Site;
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

    public function navPreview(string $token): View
    {
        $cached = cache()->get("preview_nav_{$token}");

        abort_if(! $cached, 404);

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

        $theme = $site->theme ?? 'default';
        $view = "themes.{$theme}::{$content->type}.show";
        if (! view()->exists($view)) {
            $view = "themes.{$theme}::default";
        }

        $data = $this->pageDataBuilder->build($site, $content);
        $data['navigations'] = $cached['navigations'];

        $contentFields = $content->fields->pluck('value', 'key');
        $ogImageId = (int) ($contentFields->get('og_image') ?: $data['settings']->get('og_image'));
        $ogMedia = $ogImageId ? $data['mediaMap']->get($ogImageId) : null;
        $seo = $this->seoBuilder->build($contentFields, $data['settings'], $content, $ogMedia);

        return view($view, array_merge($data, compact('content', 'site', 'seo')));
    }

    public function show(Request $request, string $slug = '/'): View
    {
        $site = $this->siteResolver->resolve($request->getHost());
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
