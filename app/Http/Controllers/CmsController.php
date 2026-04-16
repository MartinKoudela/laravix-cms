<?php

namespace App\Http\Controllers;

use App\Enums\FieldType;
use App\Models\Content;
use App\Models\Media;
use App\Models\Site;
use App\Support\FieldRegistry;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CmsController extends Controller
{
    public function show(Request $request, string $slug = '/'): View
    {
        $host = $request->getHost();
        $site = Site::where('domain', $host)->first();

        if (! $site) {
            throw new NotFoundHttpException;
        }

        $content = Content::query()
            ->where('site_id', $site->id)
            ->where('status', 'published')
            ->where(function ($q) use ($slug) {
                if (is_null($slug) || $slug === '/') {
                    $q->where('is_homepage', true);
                } else {
                    $q->where('slug', $slug);
                }
            })
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->with(['fields', 'taxonomies'])
            ->orderBy('published_at', 'desc')
            ->first();

        if (! $content) {
            throw new NotFoundHttpException;
        }

        $theme = $site->theme ?? 'default';

        $view = "themes.{$theme}::{$content->type}.show";

        if (! view()->exists($view)) {
            $view = "themes.{$theme}::default";
        }

        $navPages = Content::query()
            ->where('site_id', $site->id)
            ->where('type', 'page')
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            })
            ->orderBy('title')
            ->get(['id', 'title', 'slug', 'is_homepage']);

        $recentPosts = Content::query()
            ->where('site_id', $site->id)
            ->where('type', 'post')
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            })
            ->orderByDesc('published_at')
            ->limit(5)
            ->get(['id', 'title', 'slug', 'published_at']);

        $imageKeys = collect(FieldRegistry::forContentType($content->type))
            ->filter(fn ($def) => $def->type === FieldType::IMAGE)
            ->pluck('key');

        $mediaIds = $content->fields
            ->whereIn('key', $imageKeys->all())
            ->pluck('value')
            ->filter()
            ->map(fn ($id) => (int) $id);

        $mediaMap = Media::whereIn('id', $mediaIds)
            ->get()
            ->keyBy('id');

        return view($view, compact('content', 'site', 'navPages', 'recentPosts', 'mediaMap'));
    }
}
