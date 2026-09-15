<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Actions;

use Filament\Actions\ReplicateAction;
use Filament\Support\Icons\Heroicon;
use Laravix\Cms\Models\CustomCodeBlock;

class DuplicateCustomCodeBlockAction extends ReplicateAction
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

        $this->beforeReplicaSaved(function (CustomCodeBlock $record, CustomCodeBlock $replica): void {
            $replica->name = $record->name.' '.__('laravix::common.copy_suffix');
        });
    }
}
