<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MediaResource;
use App\Models\Media;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private const array PUBLIC_KEYS = [
        'site_name', 'site_description', 'locale', 'contact_email',
        'meta_title', 'meta_description', 'google_site_verification',
        'twitter_url', 'linkedin_url', 'facebook_url', 'instagram_url', 'github_url',
    ];

    private const array MEDIA_KEYS = ['logo', 'favicon', 'og_image'];

    public function index(Request $request): JsonResponse
    {
        $site = $request->attributes->get('site');

        $settings = Setting::where('site_id', $site->id)
            ->whereIn('key', [...self::PUBLIC_KEYS, ...self::MEDIA_KEYS])
            ->pluck('value', 'key');

        $mediaIds = collect(self::MEDIA_KEYS)
            ->map(fn ($key) => (int) $settings->get($key))
            ->filter()
            ->unique();

        $mediaMap = Media::whereIn('id', $mediaIds)->get()->keyBy('id');

        $data = $settings->only(self::PUBLIC_KEYS)->toArray();

        foreach (self::MEDIA_KEYS as $key) {
            $id = (int) $settings->get($key);
            $data[$key] = $id && $mediaMap->has($id) ? new MediaResource($mediaMap->get($id)) : null;
        }

        return response()->json(['data' => $data]);
    }
}
