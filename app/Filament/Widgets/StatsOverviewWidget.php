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
            Stat::make('Sites', Site::count())
                ->description('Total managed sites')
                ->color('primary'),
            Stat::make('Published', Content::where('status', ContentStatus::PUBLISHED->value)->count())
                ->description('Published pages & posts')
                ->color('success'),
            Stat::make('Drafts', Content::where('status', ContentStatus::DRAFT->value)->count())
                ->description('Awaiting publication')
                ->color('warning'),
            Stat::make('Media files', Media::count())
                ->description('Uploaded files')
                ->color('gray'),
        ];
    }
}
