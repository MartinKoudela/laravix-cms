<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Services;

use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Site;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContentResolver
{
    public function resolve(Site $site, string $slug, ?string $locale = null): Content
    {
        $content = Content::query()
            ->where('site_id', $site->id)
            ->where('status', 'published')
            ->when($locale, fn ($q) => $q->where('locale', $locale))
            ->where(function ($q) use ($slug) {
                if ($slug === '/') {
                    $q->where('is_homepage', true);
                } else {
                    $q->where('slug', $slug);
                }
            })
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            })
            ->with(['fields', 'taxonomies'])
            ->first();

        if (! $content) {
            throw new NotFoundHttpException;
        }

        return $content;
    }
}
