<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Actions;

use Filament\Actions\ReplicateAction;
use Filament\Support\Icons\Heroicon;
use Laravix\Cms\Enums\ContentStatus;
use Laravix\Cms\Filament\Resources\Contents\ContentResource;
use Laravix\Cms\Models\Content;
use Livewire\Component;

class DuplicateContentAction extends ReplicateAction
{
    public static function getDefaultName(): ?string
    {
        return 'duplicate';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('laravix::common.duplicate'));
        $this->icon(Heroicon::OutlinedDocumentDuplicate);
        $this->color('gray');
        $this->modal(false);
        $this->successNotificationTitle(__('laravix::content.messages.duplicated'));

        $this->excludeAttributes(['translation_group_id', 'search_text']);

        $this->beforeReplicaSaved(function (Content $record, Content $replica): void {
            $replica->title = $record->title.' '.__('laravix::common.copy_suffix');
            $replica->slug = static::uniqueSlug($record);
            $replica->status = ContentStatus::DRAFT;
            $replica->published_at = null;
            $replica->is_homepage = false;
            $replica->created_by = auth()->id();
        });

        $this->after(function (Content $record, Content $replica, Component $livewire): void {
            foreach ($record->fields as $field) {
                $replica->fields()->create(['key' => $field->key, 'value' => $field->value]);
            }

            $replica->taxonomies()->sync($record->taxonomies()->pluck('taxonomies.id'));

            $livewire->redirect(ContentResource::getUrl('edit', ['record' => $replica]));
        });
    }

    public static function uniqueSlug(Content $record): string
    {
        $base = ltrim($record->slug, '/');
        $base = $base === '' ? 'home' : $base;

        $taken = Content::withTrashed()
            ->where('site_id', $record->site_id)
            ->where('locale', $record->locale)
            ->where('slug', 'like', $base.'-copy%')
            ->pluck('slug')
            ->all();

        $candidate = $base.'-copy';

        for ($i = 2; in_array($candidate, $taken, true); $i++) {
            $candidate = $base.'-copy-'.$i;
        }

        return $candidate;
    }
}
