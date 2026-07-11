<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Http\Middleware;

use Laravix\Cms\Models\SiteApiToken;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $site = $request->attributes->get('site');
        $plaintext = $request->bearerToken();

        if ($plaintext === null) {
            return $this->unauthorized('Missing API token.');
        }

        $token = SiteApiToken::query()
            ->where('site_id', $site->id)
            ->where('token', SiteApiToken::hashToken($plaintext))
            ->first();

        if (! $token) {
            return $this->unauthorized('Invalid API token.');
        }

        if ($token->isExpired()) {
            return $this->unauthorized('API token has expired.');
        }

        $token->markAsUsed();
        $request->attributes->set('apiToken', $token);

        return $next($request);
    }

    private function unauthorized(string $message): JsonResponse
    {
        return response()->json(['message' => $message], 401)
            ->header('WWW-Authenticate', 'Bearer');
    }
}
