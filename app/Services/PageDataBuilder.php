<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Services;

use App\Enums\FieldType;
use App\Models\Content;
use App\Models\Setting;
use App\Models\Site;
use App\Support\BlockRegistry;
use App\Support\ContentTypeRegistry;
use App\Support\FieldRegistry;

class PageDataBuilder
{
    public function __construct(
        private readonly MediaResolver $mediaResolver,
        private readonly PostListHydrator $postListHydrator,
    ) {}

    public function build(Site $site, Content $content): array
    {
        $defaultLocale = $site->defaultLocale();
        $currentLocale = $content->locale ?? $defaultLocale;

        $navPages = Content::query()
            ->where('site_id', $site->id)
            ->whereIn('type', ContentTypeRegistry::navigationLinkableKeys())
            ->where('locale', $currentLocale)
            ->where('status', 'published')
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->orderBy('title')
            ->get(['id', 'title', 'slug', 'is_homepage', 'locale']);

        $archivePosts = null;
        if ($content->type === 'archive') {
            $archivePosts = Content::query()
                ->where('site_id', $site->id)
                ->where('type', 'post')
                ->where('locale', $currentLocale)
                ->where('status', 'published')
                ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()))
                ->with(['fields', 'taxonomies', 'author'])
                ->orderByDesc('published_at')
                ->get();
        }

        $alternates = collect();
        if ($site->isMultilingual() && $content->translation_group_id) {
            $alternates = Content::query()
                ->where('translation_group_id', $content->translation_group_id)
                ->where('status', 'published')
                ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()))
                ->get(['id', 'slug', 'is_homepage', 'locale'])
                ->mapWithKeys(fn (Content $c) => [$c->locale => url($c->path($defaultLocale))]);
        }

        $settings = Setting::where('site_id', $site->id)->pluck('value', 'key');

        $imageKeys = collect(FieldRegistry::forContentType($content->type, $site->id))
            ->filter(fn ($def) => $def->type === FieldType::IMAGE)
            ->pluck('key');

        $mediaIds = $content->fields
            ->whereIn('key', $imageKeys->all())
            ->pluck('value')
            ->filter()
            ->map(fn ($id) => (int) $id);

        $blockMediaIds = collect(BlockRegistry::extractMediaIds($content->blocks ?? []));

        $archiveMediaIds = $archivePosts
            ? $archivePosts->flatMap(fn ($post) => $post->fields->where('key', 'og_image')->pluck('value'))
                ->filter()
                ->map(fn ($id) => (int) $id)
            : collect();

        $mediaIds = $mediaIds->merge($blockMediaIds)->merge($archiveMediaIds)->unique();

        foreach (['og_image', 'logo', 'favicon'] as $key) {
            $id = (int) $settings->get($key);
            if ($id && ! $mediaIds->contains($id)) {
                $mediaIds->push($id);
            }
        }

        $mediaMap = $this->mediaResolver->resolve($mediaIds);

        $appearance = collect();
        $bgMedia = null;

        $systemFieldKeys = collect(FieldRegistry::forContentType($content->type))
            ->pluck('key')
            ->all();

        $logoMedia = ($logoId = (int) $settings->get('logo')) ? $mediaMap->get($logoId) : null;
        $faviconMedia = ($faviconId = (int) $settings->get('favicon')) ? $mediaMap->get($faviconId) : null;

        $navigations = $site->navigations ?? [];
        $navDesign = $site->nav_design ?? [];

        $grapesjsHtml = $content->grapesjs_html
            ? $this->postListHydrator->hydrate($content->grapesjs_html, $archivePosts ?? collect(), $mediaMap)
            : null;

        return compact(
            'navPages', 'archivePosts',
            'settings', 'mediaMap', 'appearance',
            'bgMedia', 'systemFieldKeys', 'logoMedia', 'faviconMedia',
            'navigations', 'navDesign', 'grapesjsHtml',
            'defaultLocale', 'currentLocale', 'alternates'
        );
    }
}
