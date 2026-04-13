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
        $site = Site::where('domain', $request->getHost())->firstOrFail();

        $content = Content::where('site_id', $site->id)
            ->where('slug', ltrim($slug, '/'))
            ->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->with(['fields', 'taxonomies'])
            ->first();

        if (! $content) {
            throw new NotFoundHttpException();
        }

        $theme = $site->theme ?? 'default';


        $view = "themes.{$theme}.{$content->type}.show";


        if (! view()->exists($view)) {
            $view = "themes.{$theme}.default";
        }

        return view($view, compact('content', 'site'));
    }
}
