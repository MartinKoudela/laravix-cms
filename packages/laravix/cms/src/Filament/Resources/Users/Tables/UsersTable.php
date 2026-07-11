<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Users\Tables;

use Laravix\Cms\Enums\SiteRole;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label('')
                    ->circular()
                    ->getStateUsing(fn ($record): string => $record->getFilamentAvatarUrl())
                    ->size(36),
                TextColumn::make('name')
                    ->label(__('common.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('common.email'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->label(__('common.role'))
                    ->badge()
                    ->formatStateUsing(fn (?string $state, $record): string => $record->is_super_admin
                        ? __('users.super_admin')
                        : $state)
                    ->color(fn (?string $state, $record): string => match (true) {
                        $record->is_super_admin => 'danger',
                        $state === SiteRole::ADMIN->value => 'warning',
                        $state === SiteRole::EDITOR->value => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('email_verified_at')
                    ->label(__('common.verified'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('common.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->options(collect(SiteRole::cases())->mapWithKeys(
                        fn (SiteRole $case) => [$case->value => $case->name]
                    )),
            ])
            ->recordClasses(fn ($record) => $record->is_super_admin ? 'opacity-50 grayscale' : null)
            ->recordActions([
                EditAction::make()
                    ->visible(fn ($record): bool => ! $record->is_super_admin),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
