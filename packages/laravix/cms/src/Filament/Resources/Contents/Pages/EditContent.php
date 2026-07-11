<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Contents\Pages;

use Laravix\Cms\Enums\ContentStatus;
use Laravix\Cms\Filament\Resources\Contents\ContentResource;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Support\FieldRegistry;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
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

                    return 'https://'.$record->site->domain.$record->path($record->site->defaultLocale());
                }, shouldOpenInNewTab: true),
            Action::make('translate')
                ->label(__('content.actions.translate'))
                ->icon(Heroicon::OutlinedLanguage)
                ->color('gray')
                ->visible(fn (): bool => $this->missingLocales() !== [])
                ->schema([
                    Select::make('locale')
                        ->label(__('content.fields.locale'))
                        ->options(fn () => collect($this->missingLocales())
                            ->mapWithKeys(fn (string $locale) => [$locale => strtoupper($locale)]))
                        ->required(),
                ])
                ->action(function (array $data): void {
                    /** @var Content $record */
                    $record = $this->getRecord();

                    $copy = $record->replicate();
                    $copy->locale = $data['locale'];
                    $copy->translation_group_id = $record->translation_group_id;
                    $copy->status = ContentStatus::DRAFT;
                    $copy->published_at = null;
                    $copy->created_by = auth()->id();
                    $copy->save();

                    foreach ($record->fields as $field) {
                        $copy->fields()->create(['key' => $field->key, 'value' => $field->value]);
                    }

                    Notification::make()
                        ->title(__('content.messages.translation_created'))
                        ->success()
                        ->send();

                    $this->redirect(ContentResource::getUrl('edit', ['record' => $copy]));
                }),
            DeleteAction::make(),
        ];
    }

    private function missingLocales(): array
    {
        $site = filament()->getTenant();

        if (! $site?->isMultilingual()) {
            return [];
        }

        /** @var Content $record */
        $record = $this->getRecord();

        $existing = Content::where('translation_group_id', $record->translation_group_id)
            ->pluck('locale')
            ->all();

        return array_values(array_diff($site->enabledLocales(), $existing));
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
        foreach (FieldRegistry::forContentType($this->record->type, $this->record->site_id) as $definition) {
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

        $this->record->refreshSearchText();

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
