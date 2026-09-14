<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Pages;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

abstract class BaseEditRecord extends EditRecord
{
    protected function getHeaderActions(): array
    {
        return [
            Action::make('saveFromHeader')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->action(fn () => $this->save()),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getCancelFormAction(),
            DeleteAction::make(),
        ];
    }
}
