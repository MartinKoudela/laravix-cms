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
use App\Http\Resources\Api\V1\PostResource;
use App\Models\Content;
use App\Models\Taxonomy;
use App\Services\MediaResolver;
use App\Support\BlockRegistry;
use App\Support\FieldRegistry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    use Concerns\ResolvesLocale;

    public function __construct(private readonly MediaResolver $mediaResolver) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $site = $request->attributes->get('site');

        $query = Content::query()
            ->where('site_id', $site->id)
            ->where('type', 'post')
            ->where('locale', $this->resolveLocale($request, $site))
            ->where('status', ContentStatus::PUBLISHED)
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->with(['fields', 'taxonomies', 'author'])
            ->orderByDesc('published_at');

        if ($taxonomySlug = $request->query('taxonomy_slug')) {
            $taxonomyId = Taxonomy::where('site_id', $site->id)
                ->where('slug', $taxonomySlug)
                ->value('id');

            $query->whereHas('taxonomies', fn ($q) => $q->where('taxonomies.id', $taxonomyId));
        }

        $perPage = min((int) $request->query('per_page', 15), 100);
        $posts = $query->paginate($perPage);

        $imageKeys = collect(FieldRegistry::forContentType('post', $site->id))
            ->filter(fn ($def) => $def->type === FieldType::IMAGE)
            ->pluck('key');

        $mediaIds = $posts->flatMap(
            fn ($post) => $post->fields->whereIn('key', $imageKeys->all())->pluck('value')
        )->filter()->map(fn ($id) => (int) $id)->unique();

        $mediaMap = $this->mediaResolver->resolve($mediaIds);

        return ContentSummaryResource::collection(
            $posts->through(fn ($post) => new ContentSummaryResource($post, $mediaMap))
        );
    }

    public function show(Request $request, string $slug): PostResource
    {
        $site = $request->attributes->get('site');

        $post = Content::query()
            ->where('site_id', $site->id)
            ->where('slug', $slug)
            ->where('type', 'post')
            ->where('locale', $this->resolveLocale($request, $site))
            ->where('status', ContentStatus::PUBLISHED)
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->with(['fields', 'taxonomies', 'author'])
            ->firstOrFail();

        $imageKeys = collect(FieldRegistry::forContentType('post', $site->id))
            ->filter(fn ($def) => $def->type === FieldType::IMAGE)
            ->pluck('key');

        $mediaIds = $post->fields
            ->whereIn('key', $imageKeys->all())
            ->pluck('value')
            ->filter()
            ->map(fn ($id) => (int) $id);

        $mediaIds = $mediaIds->merge(collect(BlockRegistry::extractMediaIds($post->blocks ?? [])))->unique();

        $mediaMap = $this->mediaResolver->resolve($mediaIds);

        return new PostResource($post, $mediaMap);
    }
}
