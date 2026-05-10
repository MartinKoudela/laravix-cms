<?php

namespace App\Services;

use App\Enums\ImageVariant;
use App\Models\Content;
use App\Models\Media;
use Illuminate\Support\Collection;

class SeoBuilder
{
    public function build(Collection $contentFields, Collection $settings, Content $content, ?Media $ogMedia): array
    {
        return [
            'title' => $contentFields->get('meta_title')
                ?: $settings->get('meta_title')
                    ?: $content->title,
            'description' => $contentFields->get('meta_description')
                ?: $settings->get('meta_description'),
            'og_image_url' => $ogMedia?->variantUrl(ImageVariant::OG),
            'noindex' => (bool) $contentFields->get('noindex'),
            'canonical' => url($content->is_homepage ? '/' : '/'.$content->slug),
        ];
    }
}
