<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Services;

use Laravix\Cms\Enums\FieldType;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Setting;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Support\BlockRegistry;
use Laravix\Cms\Support\ContentTypeRegistry;
use Laravix\Cms\Support\FieldRegistry;
use Laravix\Cms\Support\HydratorRegistry;
use Illuminate\Support\Collection;

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
                ->get(['id', 'type', 'slug', 'is_homepage', 'locale'])
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

        $navigations = $this->localizeNavigations($site->navigations ?? [], $currentLocale, $defaultLocale);
        $navDesign = $site->nav_design ?? [];

        $grapesjsHtml = $content->grapesjs_html
            ? $this->postListHydrator->hydrate($content->grapesjs_html, $archivePosts ?? collect(), $mediaMap)
            : null;

        if ($grapesjsHtml !== null) {
            foreach (HydratorRegistry::all() as $hydratorClass) {
                $grapesjsHtml = app($hydratorClass)->hydrate($grapesjsHtml, $site, $content);
            }
        }

        return compact(
            'navPages', 'archivePosts',
            'settings', 'mediaMap', 'appearance',
            'bgMedia', 'systemFieldKeys', 'logoMedia', 'faviconMedia',
            'navigations', 'navDesign', 'grapesjsHtml',
            'defaultLocale', 'currentLocale', 'alternates'
        );
    }

    private function localizeNavigations(array $navigations, string $locale, string $defaultLocale): array
    {
        if ($locale === $defaultLocale) {
            return $navigations;
        }

        $pathMap = $this->localizedPathMap($navigations, $locale, $defaultLocale);

        return array_map(
            fn (array $items) => array_map(
                fn (array $item) => $this->localizeItem($item, $locale, $pathMap),
                $items,
            ),
            $navigations,
        );
    }

    private function localizeItem(array $item, string $locale, Collection $pathMap): array
    {
        if (filled($item['translations'][$locale]['label'] ?? null)) {
            $item['label'] = $item['translations'][$locale]['label'];
        }

        $contentId = (int) ($item['content_id'] ?? 0);
        if ($contentId && $pathMap->has($contentId)) {
            $item['url'] = $pathMap->get($contentId);
        }

        if (! empty($item['children'])) {
            $item['children'] = array_map(
                fn (array $child) => $this->localizeItem($child, $locale, $pathMap),
                $item['children'],
            );
        }

        return $item;
    }

    private function localizedPathMap(array $navigations, string $locale, string $defaultLocale): Collection
    {
        $contentIds = collect($navigations)
            ->flatten(1)
            ->flatMap(fn (array $item) => [
                $item['content_id'] ?? null,
                ...array_map(fn (array $child) => $child['content_id'] ?? null, $item['children'] ?? []),
            ])
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($contentIds->isEmpty()) {
            return collect();
        }

        $groups = Content::query()
            ->whereIn('id', $contentIds)
            ->pluck('translation_group_id', 'id')
            ->filter();

        if ($groups->isEmpty()) {
            return collect();
        }

        $translations = Content::query()
            ->whereIn('translation_group_id', $groups->unique()->values())
            ->where('locale', $locale)
            ->where('status', 'published')
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->get(['id', 'type', 'slug', 'is_homepage', 'locale', 'translation_group_id'])
            ->keyBy('translation_group_id');

        return $groups
            ->map(fn ($groupId) => $translations->get($groupId)?->path($defaultLocale))
            ->filter();
    }
}
