<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Laravix\Cms\Enums\ContentStatus;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Media;
use Laravix\Cms\Models\Site;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $siteId = filament()->getTenant()?->id;

        return [
            ...(auth()->user()?->is_super_admin ? [
                Stat::make(__('laravix::sites.stats.title'), Site::count())
                    ->description(__('laravix::sites.stats.total'))
                    ->color('primary'),
            ] : []),
            Stat::make(__('laravix::content.stats.published'), Content::where('site_id', $siteId)->where('status', ContentStatus::PUBLISHED->value)->count())
                ->description(__('laravix::content.stats.published_description'))
                ->color('success'),
            Stat::make(__('laravix::content.stats.drafts'), Content::where('site_id', $siteId)->where('status', ContentStatus::DRAFT->value)->count())
                ->description(__('laravix::content.stats.awaiting'))
                ->color('warning'),
            Stat::make(__('laravix::media.stats.files'), Media::where('site_id', $siteId)->count())
                ->description(__('laravix::media.stats.uploaded'))
                ->color('gray'),
        ];
    }
}
