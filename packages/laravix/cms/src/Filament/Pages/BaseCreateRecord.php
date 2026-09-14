<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Pages;

use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

abstract class BaseCreateRecord extends CreateRecord
{
    protected function getHeaderActions(): array
    {
        return [
            Action::make('createFromHeader')
                ->label(__('filament-panels::resources/pages/create-record.form.actions.create.label'))
                ->action(fn () => $this->create()),
        ];
    }
}
