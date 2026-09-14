<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Actions;

use Filament\Actions\Action;
use Filament\Actions\View\ActionsIconAlias;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;
use Laravix\Cms\Models\Content;

class PreviewAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'preview';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('laravix::common.preview'));

        $this->tableIcon(FilamentIcon::resolve(ActionsIconAlias::VIEW_ACTION) ?? Heroicon::Eye);

        $this->visible(fn (Content $record): bool => $record->isPublished());

        $this->url(function (Content $record): string {
            $record->loadMissing('site');

            return request()->getScheme().'://'.$record->site->domain.$record->path($record->site->defaultLocale());
        }, shouldOpenInNewTab: true);
    }
}
