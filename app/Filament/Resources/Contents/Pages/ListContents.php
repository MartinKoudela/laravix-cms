<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Filament\Resources\Contents\Pages;

use App\Filament\Resources\Contents\ContentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;

class ListContents extends ListRecords
{
    protected static string $resource = ContentResource::class;

    #[Url]
    public string $type = 'page';

    public function mount(): void
    {
        parent::mount();

        if (! in_array($this->type, ['page', 'post', 'archive'], true)) {
            $this->type = 'page';
        }
    }

    public function getTitle(): string
    {
        return __('content.types_plural.'.$this->type);
    }

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->modifyQueryUsing(fn (Builder $query) => $query->where('type', $this->type));
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->url(fn (): string => ContentResource::getUrl('create', ['type' => $this->type])),
        ];
    }
}
