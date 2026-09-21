<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Roles\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Laravix\Cms\Filament\Actions\HoverActions;
use Laravix\Cms\Models\Role;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('laravix::common.name'))
                    ->weight(FontWeight::Medium)
                    ->description(fn (Role $record): string => $record->slug)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('is_system')
                    ->label(__('laravix::common.type'))
                    ->badge()
                    ->formatStateUsing(fn (bool $state): string => $state
                        ? __('laravix::roles.types.system')
                        : __('laravix::roles.types.custom'))
                    ->color(fn (bool $state): string => $state ? 'warning' : 'info'),
                TextColumn::make('permissions')
                    ->label(__('laravix::roles.fields.permissions'))
                    ->badge()
                    ->color('gray')
                    ->getStateUsing(fn (Role $record): string => $record->allows(Role::WILDCARD)
                        ? __('laravix::roles.full_access')
                        : (string) count($record->permissions ?? [])),
                TextColumn::make('users_count')
                    ->label(__('laravix::users.plural'))
                    ->getStateUsing(fn (Role $record): int => $record->usersCount()),
            ])
            ->defaultSort('is_system', 'desc')
            ->recordActions(HoverActions::wrap([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (DeleteAction $action, Role $record): void {
                        if (! $record->isInUse()) {
                            return;
                        }

                        Notification::make()
                            ->title(__('laravix::roles.messages.in_use'))
                            ->danger()
                            ->send();

                        $action->cancel();
                    }),
            ]));
    }
}
