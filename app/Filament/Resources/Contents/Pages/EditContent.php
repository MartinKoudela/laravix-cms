<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Filament\Resources\Contents\Pages;

use App\Filament\Resources\Contents\ContentResource;
use App\Models\Content;
use App\Support\FieldRegistry;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditContent extends EditRecord
{
    protected static string $resource = ContentResource::class;

    protected array $fieldData = [];

    public string $blockPreviewToken = '';

    public function mount(int|string $record): void
    {
        $this->blockPreviewToken = md5($record.'-'.auth()->id().'-block-preview');

        parent::mount($record);

        $this->refreshBlockPreview();
    }

    public function updated(string $property): void
    {
        if (str_starts_with($property, 'data.blocks')) {
            $this->refreshBlockPreview();
        }
    }

    public function refreshBlockPreview(): void
    {
        cache()->put("preview_blocks_{$this->blockPreviewToken}", [
            'content_id' => $this->record->id,
            'blocks' => $this->data['blocks'] ?? [],
        ], now()->addMinutes(30));

        $this->dispatch('block-preview-updated');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview')
                ->label(__('common.preview'))
                ->icon(Heroicon::OutlinedEye)
                ->color('gray')
                ->url(function (): string {
                    /** @var Content $record */
                    $record = $this->getRecord();
                    $record->loadMissing('site');

                    $slug = $record->is_homepage ? '' : ltrim($record->slug, '/');

                    return 'https://'.$record->site->domain.'/'.$slug;
                }, shouldOpenInNewTab: true),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $fields = $this->record->fields()->pluck('value', 'key');

        foreach ($fields as $key => $value) {
            $data['field_'.$key] = $value;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        foreach (FieldRegistry::forContentType($this->record->type) as $definition) {
            $this->fieldData[$definition->key] = $data['field_'.$definition->key] ?? null;
            unset($data['field_'.$definition->key]);
        }

        return $data;
    }

    protected function afterSave(): void
    {
        foreach ($this->fieldData as $key => $value) {
            $this->record->fields()->updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }

        $cached = cache()->get("preview_blocks_{$this->blockPreviewToken}");
        if ($cached && array_key_exists('blocks', $cached)) {
            $this->record->updateQuietly(['blocks' => $cached['blocks']]);
        }

        $this->record->revisions()->create([
            'created_by' => auth()->id(),
            'data' => [
                'title' => $this->record->title,
                'slug' => $this->record->slug,
                'status' => $this->record->status->value,
                'is_homepage' => $this->record->is_homepage,
                'published_at' => $this->record->published_at,
                'blocks' => $this->record->fresh()->blocks,
                'fields' => $this->record->fields()->pluck('value', 'key')->toArray(),
            ],
        ]);
    }
}
