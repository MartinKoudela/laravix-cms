<?php

namespace App\Services;

use App\Enums\FieldType;
use App\Models\Content;
use App\Models\Setting;
use App\Models\Site;
use App\Support\AppearanceRegistry;
use App\Support\BlockRegistry;
use App\Support\FieldRegistry;

class PageDataBuilder
{
    public function __construct(
        private readonly MediaResolver $mediaResolver,
    ) {}

    public function build(Site $site, Content $content): array
    {
        $navPages = Content::query()
            ->where('site_id', $site->id)
            ->whereIn('type', ['page', 'archive'])
            ->where('status', 'published')
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->orderBy('title')
            ->get(['id', 'title', 'slug', 'is_homepage']);

        $recentPosts = Content::query()
            ->where('site_id', $site->id)
            ->where('type', 'post')
            ->where('status', 'published')
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->orderByDesc('published_at')
            ->limit(5)
            ->get(['id', 'title', 'slug', 'published_at']);

        $archivePosts = null;
        if ($content->type === 'archive') {
            $archivePosts = Content::query()
                ->where('site_id', $site->id)
                ->where('type', 'post')
                ->where('status', 'published')
                ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()))
                ->with(['fields', 'taxonomies', 'author'])
                ->orderByDesc('published_at')
                ->get();
        }

        $settings = Setting::where('site_id', $site->id)->pluck('value', 'key');

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

        $blockMediaIds = collect(BlockRegistry::extractMediaIds($content->blocks ?? []));
        $mediaIds = $mediaIds->merge($blockMediaIds)->unique();

        foreach (['og_image', 'logo', 'favicon'] as $key) {
            $id = (int) $settings->get($key);
            if ($id && ! $mediaIds->contains($id)) {
                $mediaIds->push($id);
            }
        }

        $mediaMap = $this->mediaResolver->resolve($mediaIds);

        $appearanceKeys = collect(AppearanceRegistry::forContentType($content->type))->pluck('key');
        $appearance = $content->fields->whereIn('key', $appearanceKeys->all())->pluck('value', 'key');
        $bgMedia = ($bgId = (int) $appearance->get('background_image')) ? $mediaMap->get($bgId) : null;

        $systemFieldKeys = collect(FieldRegistry::forContentType($content->type))
            ->pluck('key')
            ->merge($appearanceKeys)
            ->all();

        $logoMedia = ($logoId = (int) $settings->get('logo')) ? $mediaMap->get($logoId) : null;
        $faviconMedia = ($faviconId = (int) $settings->get('favicon')) ? $mediaMap->get($faviconId) : null;

        $navigations = $site->navigations ?? [];
        $navDesign = $site->nav_design ?? [];

        return compact(
            'navPages', 'recentPosts', 'archivePosts',
            'settings', 'mediaMap', 'appearance',
            'bgMedia', 'systemFieldKeys', 'logoMedia', 'faviconMedia',
            'navigations', 'navDesign'
        );
    }
}
