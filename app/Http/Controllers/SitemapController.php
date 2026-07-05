<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Setting;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SitemapController extends Controller
{
    public function index(Request $request): Response
    {
        $host = $request->getHost();
        $site = Site::where('domain', $host)->first();

        if (! $site) {
            throw new NotFoundHttpException;
        }

        $contents = Content::query()
            ->where('site_id', $site->id)
            ->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            })
            ->whereNotExists(function ($q) {
                $q->from('content_fields')
                    ->whereColumn('content_fields.content_id', 'contents.id')
                    ->where('content_fields.key', 'noindex')
                    ->where('content_fields.value', '1');
            })
            ->orderByDesc('updated_at')
            ->get(['id', 'slug', 'is_homepage', 'locale', 'updated_at']);

        $defaultLocale = $site->defaultLocale();

        $xml = view('sitemap', compact('contents', 'defaultLocale'))->render();

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    public function robots(Request $request): Response
    {
        $host = $request->getHost();
        $site = Site::where('domain', $host)->first();

        $customContent = $site
            ? Setting::where('site_id', $site->id)->where('key', 'robots_txt')->value('value')
            : null;

        if ($customContent) {
            $content = rtrim($customContent)."\n\nSitemap: ".url('/sitemap.xml')."\n";
        } else {
            $content = "User-agent: *\nAllow: /\n\nSitemap: ".url('/sitemap.xml')."\n";
        }

        return response($content, 200, ['Content-Type' => 'text/plain']);
    }
}
