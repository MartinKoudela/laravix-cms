<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Actions;

use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Contracts\HasTable;

/**
 * Row-level shortcut that flips the table into Filament's drag-and-drop reorder mode.
 */
class ReorderRecordsAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'reorder';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('laravix::common.reorder'));
        $this->icon(Heroicon::OutlinedArrowsUpDown);
        $this->color('gray');

        $this->visible(fn (HasTable $livewire): bool => $livewire->getTable()->isReorderable());

        $this->action(fn (HasTable $livewire) => $livewire->toggleTableReordering());
    }
}
