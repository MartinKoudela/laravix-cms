<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Services;

use Laravix\Cms\Enums\ImageVariant;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Media;
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
            'canonical' => url($content->path($settings->get('locale') ?: 'en')),
        ];
    }
}
