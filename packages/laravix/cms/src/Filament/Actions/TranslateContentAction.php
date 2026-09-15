<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Actions;

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Laravix\Cms\Enums\ContentStatus;
use Laravix\Cms\Filament\Resources\Contents\ContentResource;
use Laravix\Cms\Models\Content;
use Livewire\Component;

class TranslateContentAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'translate';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('laravix::content.actions.translate'));
        $this->icon(Heroicon::OutlinedLanguage);
        $this->color('gray');

        $this->visible(fn (Content $record): bool => static::missingLocales($record) !== []);

        $this->schema(fn (Content $record): array => [
            Select::make('locale')
                ->label(__('laravix::content.fields.locale'))
                ->options(collect(static::missingLocales($record))
                    ->mapWithKeys(fn (string $locale) => [$locale => strtoupper($locale)]))
                ->required(),
        ]);

        $this->action(function (Content $record, array $data, Component $livewire): void {
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
                ->title(__('laravix::content.messages.translation_created'))
                ->success()
                ->send();

            $livewire->redirect(ContentResource::getUrl('edit', ['record' => $copy]));
        });
    }

    public static function missingLocales(Content $record): array
    {
        $site = filament()->getTenant();

        if (! $site?->isMultilingual()) {
            return [];
        }

        $existing = Content::where('translation_group_id', $record->translation_group_id)
            ->pluck('locale')
            ->all();

        return array_values(array_diff($site->enabledLocales(), $existing));
    }
}
