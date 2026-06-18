<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Http\Middleware;

use App\Services\SiteResolver;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResolveSiteForApi
{
    public function __construct(private readonly SiteResolver $siteResolver) {}

    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->header('X-Site-Domain') ?? $request->getHost();

        try {
            $site = $this->siteResolver->resolve($host);
        } catch (NotFoundHttpException) {
            return response()->json(['message' => 'Site not found.'], 404);
        }

        $request->attributes->set('site', $site);

        return $next($request);
    }
}
