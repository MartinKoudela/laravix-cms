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
    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make(__('laravix::sites.stats.title'), Site::count())
                ->description(__('laravix::sites.stats.total'))
                ->color('primary'),
            Stat::make(__('laravix::content.stats.published'), Content::where('status', ContentStatus::PUBLISHED->value)->count())
                ->description(__('laravix::content.stats.published_description'))
                ->color('success'),
            Stat::make(__('laravix::content.stats.drafts'), Content::where('status', ContentStatus::DRAFT->value)->count())
                ->description(__('laravix::content.stats.awaiting'))
                ->color('warning'),
            Stat::make(__('laravix::media.stats.files'), Media::count())
                ->description(__('laravix::media.stats.uploaded'))
                ->color('gray'),
        ];
    }
}
