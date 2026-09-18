<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Actions;

use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Laravix\Cms\Enums\TableLayout;
use Livewire\Component;

class SwitchTableLayoutAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'switchLayout';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->iconButton();
        $this->color('gray');

        $this->label(fn (Component $livewire): string => $livewire->getTableLayout() === TableLayout::Grid
            ? __('laravix::common.layout.list')
            : __('laravix::common.layout.grid'));

        $this->tooltip(fn (Action $action): string => $action->getLabel());

        $this->icon(fn (Component $livewire) => $livewire->getTableLayout() === TableLayout::Grid
            ? Heroicon::OutlinedListBullet
            : Heroicon::OutlinedSquares2x2);

        $this->action(fn (Component $livewire) => $livewire->setTableLayout($livewire->getTableLayout()->toggle()->value));
    }
}
