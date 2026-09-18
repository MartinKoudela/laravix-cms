<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Concerns;

use Filament\Tables\Table;
use Laravix\Cms\Enums\TableLayout;
use Laravix\Cms\Filament\Actions\SwitchTableLayoutAction;

trait HasTableLayoutSwitcher
{
    public string $tableLayout = TableLayout::List->value;

    public function bootHasTableLayoutSwitcher(): void
    {
        $this->tableLayout = session()->get(static::tableLayoutSessionKey(), static::defaultTableLayout()->value);
    }

    public function setTableLayout(string $layout): void
    {
        $layout = TableLayout::from($layout);

        session()->put(static::tableLayoutSessionKey(), $layout->value);
        $this->tableLayout = $layout->value;

        $this->table = $this->table($this->makeTable());
        $this->flushCachedTableRecords();
    }

    public function getTableLayout(): TableLayout
    {
        return TableLayout::from($this->tableLayout);
    }

    protected function applyTableLayout(Table $table): Table
    {
        $table->pushToolbarActions([SwitchTableLayoutAction::make()]);

        if ($this->getTableLayout() === TableLayout::Grid) {
            $table
                ->columns(static::gridColumns())
                ->contentGrid(['md' => 2, 'xl' => 3, '2xl' => 4]);
        }

        return $table;
    }

    protected static function defaultTableLayout(): TableLayout
    {
        return TableLayout::List;
    }

    protected static function tableLayoutSessionKey(): string
    {
        return 'laravix.table_layout.'.static::class;
    }

    abstract protected static function gridColumns(): array;
}
