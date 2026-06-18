<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class ContentSummaryResource extends JsonResource
{
    public function __construct(
        mixed $resource,
        private readonly Collection $mediaMap = new Collection,
    ) {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        $fields = $this->fields->pluck('value', 'key');

        return [
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
            'slug' => $this->slug,
            'is_homepage' => $this->is_homepage,
            'status' => $this->status->value,
            'published_at' => $this->published_at?->toIso8601String(),
            'og_image' => $this->resolveMedia((int) $fields->get('og_image')),
            'taxonomies' => TaxonomyResource::collection($this->whenLoaded('taxonomies')),
            'author' => $this->whenLoaded('author', fn () => [
                'id' => $this->author->id,
                'name' => $this->author->name,
            ]),
        ];
    }

    private function resolveMedia(int $id): mixed
    {
        $media = $id ? $this->mediaMap->get($id) : null;

        return $media ? new MediaResource($media) : null;
    }
}
