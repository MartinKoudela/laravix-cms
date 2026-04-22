<?php

namespace App\Services;

use App\Models\Content;
use App\Models\Site;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContentResolver
{
    public function resolve(Site $site, string $slug): Content
    {
        $content = Content::query()
            ->where('site_id', $site->id)
            ->where('status', 'published')
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
