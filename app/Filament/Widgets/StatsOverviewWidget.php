<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Filament\Widgets;

use App\Enums\ContentStatus;
use App\Models\Content;
use App\Models\Media;
use App\Models\Site;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make(__('sites.stats.title'), Site::count())
                ->description(__('sites.stats.total'))
                ->color('primary'),
            Stat::make(__('content.stats.published'), Content::where('status', ContentStatus::PUBLISHED->value)->count())
                ->description(__('content.stats.published_description'))
                ->color('success'),
            Stat::make(__('content.stats.drafts'), Content::where('status', ContentStatus::DRAFT->value)->count())
                ->description(__('content.stats.awaiting'))
                ->color('warning'),
            Stat::make(__('media.stats.files'), Media::count())
                ->description(__('media.stats.uploaded'))
                ->color('gray'),
        ];
    }
}
