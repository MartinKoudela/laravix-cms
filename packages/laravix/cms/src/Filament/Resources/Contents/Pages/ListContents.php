<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Contents\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Laravix\Cms\Filament\Concerns\HasTableLayoutSwitcher;
use Laravix\Cms\Filament\Resources\Contents\ContentResource;
use Laravix\Cms\Filament\Resources\Contents\Tables\ContentsTable;
use Laravix\Cms\Support\ContentTypeRegistry;
use Livewire\Attributes\Url;

class ListContents extends ListRecords
{
    use HasTableLayoutSwitcher;

    protected static string $resource = ContentResource::class;

    #[Url]
    public string $type = '';

    public function mount(): void
    {
        parent::mount();

        if (! ContentTypeRegistry::has($this->type)) {
            $this->type = ContentTypeRegistry::default()->key;
        }
    }

    public function mountInteractsWithTable(): void
    {
        if ($taxonomy = request()->query('taxonomy')) {
            $this->tableFilters = ['taxonomies' => ['value' => (string) $taxonomy]];
        }

        parent::mountInteractsWithTable();
    }

    public function getTitle(): string
    {
        return __(ContentTypeRegistry::find($this->type)->pluralLabel);
    }

    public function table(Table $table): Table
    {
        return $this->applyTableLayout(parent::table($table))
            ->modifyQueryUsing(fn (Builder $query) => $query->where('type', $this->type));
    }

    protected static function gridColumns(): array
    {
        return ContentsTable::gridColumns();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->url(fn (): string => ContentResource::getUrl('create', ['type' => $this->type])),
        ];
    }
}
