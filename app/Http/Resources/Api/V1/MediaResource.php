<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Http\Resources\Api\V1;

use App\Enums\ImageVariant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'mime_type' => $this->mime_type,
            'size' => $this->size,
            'url' => $this->url,
            'variants' => collect(ImageVariant::cases())->mapWithKeys(
                fn (ImageVariant $variant) => [$variant->value => $this->variantUrl($variant)]
            ),
        ];
    }
}
