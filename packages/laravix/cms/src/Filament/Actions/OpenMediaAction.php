<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Actions;

use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Laravix\Cms\Models\Media;

class OpenMediaAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'open';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('laravix::media.actions.open'));
        $this->icon(Heroicon::OutlinedArrowTopRightOnSquare);
        $this->color('gray');

        $this->url(fn (Media $record): string => $record->url, shouldOpenInNewTab: true);
    }
}
