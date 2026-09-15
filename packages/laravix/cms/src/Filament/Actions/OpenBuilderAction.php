<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Actions;

use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Support\ContentTypeRegistry;

class OpenBuilderAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'builder';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('laravix::content.actions.open_builder'));
        $this->icon(Heroicon::OutlinedSquares2x2);
        $this->color('gray');

        $this->visible(fn (Content $record): bool => static::hasBuilder($record));

        $this->url(fn (Content $record): string => route('builder.edit', [$record->site_id, $record->id]));
    }

    public static function hasBuilder(Content $record): bool
    {
        if (filament()->getTenant()?->isHeadless()) {
            return false;
        }

        return ContentTypeRegistry::find($record->type)?->hasBuilder ?? false;
    }
}
