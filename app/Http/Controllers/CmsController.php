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

        $content = Content::where('site_id', $site->id)
            ->when(! $slug, fn ($q) => $q->where('is_homepage', true), fn ($q) => $q->where('slug', $slug))
            ->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->with(['fields', 'taxonomies'])
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
