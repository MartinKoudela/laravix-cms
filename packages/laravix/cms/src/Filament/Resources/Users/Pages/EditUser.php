<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Users\Pages;

use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Filament\Pages\BaseEditRecord;
use Laravix\Cms\Filament\Resources\Users\Schemas\UserForm;
use Laravix\Cms\Filament\Resources\Users\UserResource;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\User;

class EditUser extends BaseEditRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $role = $data['role'] ?? null;
        unset($data['role']);

        $site = Filament::getTenant();

        if ($role !== null) {
            $this->assertRoleAssignable($role);
            $this->assertNotLastAdmin($record, $site, $role);
        }

        $record->update($data);

        if ($role !== null) {
            $record->sites()->updateExistingPivot($site->id, ['role' => $role]);
            $record->forgetResolvedRoles();
        }

        return $record;
    }

    protected function assertRoleAssignable(string $role): void
    {
        if (array_key_exists($role, UserForm::roleOptions())) {
            return;
        }

        Notification::make()
            ->title(__('laravix::roles.messages.cannot_assign'))
            ->danger()
            ->send();

        $this->halt();
    }

    protected function assertNotLastAdmin(User $record, Site $site, string $role): void
    {
        if ($role === SiteRole::ADMIN->value) {
            return;
        }

        if ($record->roleSlugForSite($site) !== SiteRole::ADMIN->value) {
            return;
        }

        $otherAdmins = $site->users()
            ->wherePivot('role', SiteRole::ADMIN->value)
            ->whereKeyNot($record->getKey())
            ->exists();

        if ($otherAdmins) {
            return;
        }

        Notification::make()
            ->title(__('laravix::roles.messages.last_admin'))
            ->danger()
            ->send();

        $this->halt();
    }
}
