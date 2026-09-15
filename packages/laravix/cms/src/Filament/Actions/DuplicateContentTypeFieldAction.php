<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Actions;

use Filament\Actions\ReplicateAction;
use Filament\Support\Icons\Heroicon;
use Laravix\Cms\Models\ContentTypeField;

class DuplicateContentTypeFieldAction extends ReplicateAction
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
        $this->successNotificationTitle(__('laravix::common.duplicated'));

        $this->beforeReplicaSaved(function (ContentTypeField $record, ContentTypeField $replica): void {
            $replica->label = $record->label.' '.__('laravix::common.copy_suffix');
            $replica->key = static::uniqueKey($record);
            $replica->sort_order = ($record->sort_order ?? 0) + 1;
        });
    }

    public static function uniqueKey(ContentTypeField $record): string
    {
        $taken = ContentTypeField::query()
            ->where('site_id', $record->site_id)
            ->where('content_type', $record->content_type)
            ->where('key', 'like', $record->key.'_copy%')
            ->pluck('key')
            ->all();

        $candidate = $record->key.'_copy';

        for ($i = 2; in_array($candidate, $taken, true); $i++) {
            $candidate = $record->key.'_copy_'.$i;
        }

        return $candidate;
    }
}
