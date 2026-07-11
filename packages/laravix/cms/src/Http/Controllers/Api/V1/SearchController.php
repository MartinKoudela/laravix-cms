<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Http\Controllers\Api\V1;

use Laravix\Cms\Enums\FieldType;
use Laravix\Cms\Http\Controllers\Api\V1\Concerns\ResolvesLocale;
use Laravix\Cms\Http\Controllers\Controller;
use Laravix\Cms\Http\Resources\Api\V1\ContentSummaryResource;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Services\MediaResolver;
use Laravix\Cms\Support\ContentTypeRegistry;
use Laravix\Cms\Support\FieldRegistry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SearchController extends Controller
{
    use ResolvesLocale;

    public function __construct(private readonly MediaResolver $mediaResolver) {}

    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $site = $request->attributes->get('site');

        $validated = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:200'],
        ]);

        $search = Content::search($validated['q'])
            ->where('site_id', $site->id)
            ->where('locale', $this->resolveLocale($request, $site))
            ->query(fn ($query) => $query->with(['fields', 'taxonomies', 'author']));

        if (ContentTypeRegistry::has((string) $request->query('type'))) {
            $search->where('type', $request->query('type'));
        }

        $perPage = min((int) $request->query('per_page', 15), 100);
        $results = $search->paginate($perPage);

        $imageKeys = collect(ContentTypeRegistry::keys())
            ->flatMap(fn (string $type) => collect(FieldRegistry::forContentType($type, $site->id))
                ->filter(fn ($def) => $def->type === FieldType::IMAGE)
                ->pluck('key'))
            ->unique();

        $mediaIds = $results->getCollection()
            ->flatMap(fn ($content) => $content->fields->whereIn('key', $imageKeys->all())->pluck('value'))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique();

        $mediaMap = $this->mediaResolver->resolve($mediaIds);

        return ContentSummaryResource::collection(
            $results->through(fn ($content) => new ContentSummaryResource($content, $mediaMap))
        );
    }
}
