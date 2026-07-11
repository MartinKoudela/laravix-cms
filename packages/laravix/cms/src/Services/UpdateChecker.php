<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Laravix\Cms\Laravix;

class UpdateChecker
{
    public function latestVersion(): ?string
    {
        if (! config('laravix.updates.check')) {
            return null;
        }

        return rescue(function () {
            return Cache::remember(
                'laravix.latest-version',
                config('laravix.updates.cache_ttl', 43200),
                fn (): string => $this->fetchLatestVersion() ?? ''
            ) ?: null;
        }, null, false);
    }

    public function updateAvailable(): bool
    {
        $latest = $this->latestVersion();
        $current = Laravix::version();

        if ($latest === null || str_starts_with($current, 'dev')) {
            return false;
        }

        return version_compare(ltrim($latest, 'v'), ltrim($current, 'v'), '>');
    }

    private function fetchLatestVersion(): ?string
    {
        $response = Http::timeout(5)->connectTimeout(3)
            ->get(config('laravix.updates.endpoint'));

        if (! $response->successful()) {
            return null;
        }

        return collect($response->json('packages.laravix/cms', []))
            ->pluck('version')
            ->filter(fn (?string $version): bool => is_string($version)
                && preg_match('/^v?\d+\.\d+\.\d+$/', $version) === 1)
            ->map(fn (string $version): string => ltrim($version, 'v'))
            ->reduce(fn (?string $highest, string $version): string => $highest === null || version_compare($version, $highest, '>')
                ? $version
                : $highest);
    }
}
