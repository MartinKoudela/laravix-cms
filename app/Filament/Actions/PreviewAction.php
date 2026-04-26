<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use Filament\Actions\View\ActionsIconAlias;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Model;

class PreviewAction extends Action
{

    public static function getDefaultName(): ?string
    {
        return 'preview';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('Preview'));

        $this->tableIcon(FilamentIcon::resolve(ActionsIconAlias::VIEW_ACTION) ?? Heroicon::Eye);

        $this->url(function (Model $record): string {
            $record->loadMissing('site');

            $slug = $record->is_homepage ? '' : ltrim($record->slug, '/');

            return 'https://' . $record->site->domain . '/' . $slug;
        }, shouldOpenInNewTab: true);
    }
}
