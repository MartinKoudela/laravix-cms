<?php

/**
 * Laravix Changelog Plugin — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Changelog\Http\Controllers;

use App\Models\Setting;
use App\Services\SiteResolver;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laravix\Changelog\Models\ChangelogRelease;

class ChangelogController
{
    public function __construct(private readonly SiteResolver $siteResolver) {}

    public function __invoke(Request $request, ?string $locale = null): View
    {
        $site = $this->siteResolver->resolve($request->getHost());

        $default = $site->defaultLocale();

        if ($locale === null) {
            $locale = $default;
        } else {
            abort_if($locale === $default || ! in_array($locale, $site->enabledLocales(), true), 404);
        }

        app()->setLocale($locale);

        $enabled = (bool) Setting::where('site_id', $site->id)
            ->where('key', 'changelog_page_enabled')
            ->value('value');

        abort_unless($enabled, 404);

        $releases = ChangelogRelease::query()
            ->where('site_id', $site->id)
            ->with('items')
            ->orderByDesc('released_at')
            ->get();

        $theme = $site->theme ?? 'default';
        $view = "themes.{$theme}::changelog.index";

        if (! view()->exists($view)) {
            $view = 'changelog::index';
        }

        return view($view, compact('site', 'releases', 'locale'));
    }
}
