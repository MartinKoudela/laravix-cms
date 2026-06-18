<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Http\Controllers\Api\V1;

use App\Enums\ContentStatus;
use App\Enums\FieldType;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ContentSummaryResource;
use App\Http\Resources\Api\V1\PageResource;
use App\Models\Content;
use App\Services\MediaResolver;
use App\Support\BlockRegistry;
use App\Support\FieldRegistry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PageController extends Controller
{
    public function __construct(private readonly MediaResolver $mediaResolver) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $site = $request->attributes->get('site');

        $pages = Content::query()
            ->where('site_id', $site->id)
            ->whereIn('type', ['page', 'archive'])
            ->where('status', ContentStatus::PUBLISHED)
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->with(['fields', 'taxonomies', 'author'])
            ->orderBy('title')
            ->get();

        $imageKeys = collect(FieldRegistry::forContentType('page', $site->id))
            ->filter(fn ($def) => $def->type === FieldType::IMAGE)
            ->pluck('key');

        $mediaIds = $pages->flatMap(
            fn ($page) => $page->fields->whereIn('key', $imageKeys->all())->pluck('value')
        )->filter()->map(fn ($id) => (int) $id)->unique();

        $mediaMap = $this->mediaResolver->resolve($mediaIds);

        return ContentSummaryResource::collection(
            $pages->map(fn ($page) => new ContentSummaryResource($page, $mediaMap))
        );
    }

    public function homepage(Request $request): PageResource
    {
        $site = $request->attributes->get('site');

        $page = Content::query()
            ->where('site_id', $site->id)
            ->where('is_homepage', true)
            ->where('status', ContentStatus::PUBLISHED)
            ->with(['fields', 'taxonomies', 'author'])
            ->firstOrFail();

        return $this->buildPageResource($site, $page);
    }

    public function show(Request $request, string $slug): PageResource
    {
        $site = $request->attributes->get('site');

        $page = Content::query()
            ->where('site_id', $site->id)
            ->where('slug', $slug)
            ->whereIn('type', ['page', 'archive'])
            ->where('status', ContentStatus::PUBLISHED)
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->with(['fields', 'taxonomies', 'author'])
            ->firstOrFail();

        return $this->buildPageResource($site, $page);
    }

    private function buildPageResource(mixed $site, Content $page): PageResource
    {
        $imageKeys = collect(FieldRegistry::forContentType($page->type, $site->id))
            ->filter(fn ($def) => $def->type === FieldType::IMAGE)
            ->pluck('key');

        $mediaIds = $page->fields
            ->whereIn('key', $imageKeys->all())
            ->pluck('value')
            ->filter()
            ->map(fn ($id) => (int) $id);

        $mediaIds = $mediaIds->merge(collect(BlockRegistry::extractMediaIds($page->blocks ?? [])))->unique();

        $mediaMap = $this->mediaResolver->resolve($mediaIds);

        return new PageResource($page, $mediaMap);
    }
}
