<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Site;
use App\Services\PageDataBuilder;
use App\Services\SeoBuilder;
use Illuminate\View\View;

class PreviewController extends Controller
{
    public function __construct(
        private readonly PageDataBuilder $pageDataBuilder,
        private readonly SeoBuilder $seoBuilder,
    ) {}

    public function nav(string $token): View
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

        $data = $this->pageDataBuilder->build($site, $content);
        $data['navigations'] = $cached['navigations'];

        $seo = $this->buildSeo($content, $data);

        return view($this->resolveView($site, $content), array_merge($data, compact('content', 'site', 'seo')));
    }

    public function appearance(string $token): View
    {
        $cached = cache()->get("preview_appearance_{$token}");

        abort_if(! $cached, 404);

        $content = Content::with(['fields', 'taxonomies', 'site'])->findOrFail($cached['content_id']);
        $site = $content->site;

        $data = $this->pageDataBuilder->build($site, $content);
        $data['appearance'] = collect($cached['appearance']);

        $seo = $this->buildSeo($content, $data);

        return view($this->resolveView($site, $content), array_merge($data, compact('content', 'site', 'seo')));
    }

    public function blocks(string $token): View
    {
        $cached = cache()->get("preview_blocks_{$token}");

        abort_if(! $cached, 404);

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
