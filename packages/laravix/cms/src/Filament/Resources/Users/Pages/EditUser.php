<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Users\Pages;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Laravix\Cms\Filament\Pages\BaseEditRecord;
use Laravix\Cms\Filament\Resources\Users\UserResource;

class EditUser extends BaseEditRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $role = $data['role'] ?? null;
        unset($data['role']);

        $record->update($data);

        if ($role !== null) {
            $record->sites()->updateExistingPivot(Filament::getTenant()->id, ['role' => $role]);
        }

        return $record;
    }
}
