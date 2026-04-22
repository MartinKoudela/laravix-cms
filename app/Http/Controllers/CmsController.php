<?php

namespace App\Http\Controllers;

use App\Enums\FieldType;
use App\Models\Content;
use App\Models\Media;
use App\Models\Setting;
use App\Models\Site;
use App\Services\ContentResolver;
use App\Services\MediaResolver;
use App\Services\SeoBuilder;
use App\Support\AppearanceRegistry;
use App\Support\FieldRegistry;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CmsController extends Controller
{

    public function __construct(
        private readonly ContentResolver $contentResolver,
        private readonly SeoBuilder $seoBuilder,
        private readonly MediaResolver $mediaResolver,
    ) {}

    public function show(Request $request, string $slug = '/'): View
    {
        $host = $request->getHost();
        $site = Site::where('domain', $host)->first();

        if (! $site) {
            throw new NotFoundHttpException;
        }

        $content = $this->contentResolver->resolve($site, $slug);

        $theme = $site->theme ?? 'default';

        $view = "themes.{$theme}::{$content->type}.show";

        if (! view()->exists($view)) {
            $view = "themes.{$theme}::default";
        }

        $navPages = Content::query()
            ->where('site_id', $site->id)
            ->whereIn('type', ['page', 'archive'])
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            })
            ->orderBy('title')
            ->get(['id', 'title', 'slug', 'is_homepage']);

        $recentPosts = Content::query()
            ->where('site_id', $site->id)
            ->where('type', 'post')
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            })
            ->orderByDesc('published_at')
            ->limit(5)
            ->get(['id', 'title', 'slug', 'published_at']);

        $archivePosts = null;
        if ($content->type === 'archive') {
            $archivePosts = Content::query()
                ->where('site_id', $site->id)
                ->where('type', 'post')
                ->where('status', 'published')
                ->where(function ($q) {
                    $q->whereNull('published_at')->orWhere('published_at', '<=', now());
                })
                ->with(['fields', 'taxonomies', 'author'])
                ->orderByDesc('published_at')
                ->get();
        }

        $imageKeys = collect(FieldRegistry::forContentType($content->type))
            ->filter(fn ($def) => $def->type === FieldType::IMAGE)
            ->pluck('key');

        $appearanceImageKeys = collect(AppearanceRegistry::forContentType($content->type))
            ->filter(fn ($def) => $def->type === FieldType::IMAGE)
            ->pluck('key');

        $mediaIds = $content->fields
            ->whereIn('key', $imageKeys->merge($appearanceImageKeys)->all())
            ->pluck('value')
            ->filter()
            ->map(fn ($id) => (int) $id);

        $settings = Setting::where('site_id', $site->id)->pluck('value', 'key');

        foreach (['og_image', 'logo', 'favicon'] as $imageKey) {
            $id = (int) $settings->get($imageKey);
            if ($id && ! $mediaIds->contains($id)) {
                $mediaIds->push($id);
            }
        }

        $mediaMap = $this->mediaResolver->resolve($mediaIds);

        $contentFields = $content->fields->pluck('value', 'key');

        $appearanceKeys = collect(AppearanceRegistry::forContentType($content->type))->pluck('key');
        $appearance = $content->fields->whereIn('key', $appearanceKeys->all())->pluck('value', 'key');
        $bgMedia = ($bgId = (int) $appearance->get('background_image')) ? $mediaMap->get($bgId) : null;

        $systemFieldKeys = collect(FieldRegistry::forContentType($content->type))
            ->pluck('key')
            ->merge($appearanceKeys)
            ->all();

        $ogImageId = (int) ($contentFields->get('og_image') ?: $settings->get('og_image'));
        $logoMedia = ($logoId = (int) $settings->get('logo')) ? $mediaMap->get($logoId) : null;
        $faviconMedia = ($faviconId = (int) $settings->get('favicon')) ? $mediaMap->get($faviconId) : null;

        $ogMedia = $ogImageId ? $mediaMap->get($ogImageId) : null;
        $seo = $this->seoBuilder->build($contentFields, $settings, $content, $ogMedia);

        return view($view, compact('content', 'site', 'navPages', 'recentPosts', 'archivePosts', 'mediaMap', 'settings', 'seo', 'logoMedia', 'faviconMedia', 'appearance', 'bgMedia', 'systemFieldKeys'));
    }
}
