<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CmsController extends Controller
{
    public function show(Request $request, string $slug = '/'): View
    {
        $host = $request->getHost();
        $site = Site::all()->first(
            fn ($s) => rtrim(parse_url($s->domain, PHP_URL_HOST) ?? $s->domain, '/') === $host
        );

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

        return view($view, compact('content', 'site'));
    }
}
