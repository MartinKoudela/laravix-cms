<?php

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
            Stat::make(__('Sites'), Site::count())
                ->description(__('Total managed sites'))
                ->color('primary'),
            Stat::make(__('Published'), Content::where('status', ContentStatus::PUBLISHED->value)->count())
                ->description(__('Published pages & posts'))
                ->color('success'),
            Stat::make(__('Drafts'), Content::where('status', ContentStatus::DRAFT->value)->count())
                ->description(__('Awaiting publication'))
                ->color('warning'),
            Stat::make(__('Media files'), Media::count())
                ->description(__('Uploaded files'))
                ->color('gray'),
        ];
    }
}
