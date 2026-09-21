<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\UserInvitations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Laravix\Cms\Enums\SiteRole;
use Laravix\Cms\Models\Role;
use Laravix\Cms\Models\Site;

class UserInvitationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columnToggleFormMaxHeight('200px')
            ->columns([
                TextColumn::make('email')
                    ->label(__('laravix::common.email'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->label(__('laravix::common.role'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => static::roleNames()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        SiteRole::ADMIN->value => 'warning',
                        SiteRole::EDITOR->value => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('accepted_at')
                    ->label(__('laravix::common.status'))
                    ->badge()
                    ->getStateUsing(fn ($record): string => $record->accepted_at ? __('laravix::invitations.statuses.accepted') : __('laravix::invitations.statuses.pending'))
                    ->color(fn (string $state): string => $state === __('laravix::invitations.statuses.accepted') ? 'success' : 'gray'),
                TextColumn::make('invitedBy.name')
                    ->label(__('laravix::invitations.fields.invited_by'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('expires_at')
                    ->label(__('laravix::invitations.fields.expires'))
                    ->dateTime()
                    ->sortable()
                    ->color(fn ($state): string => $state?->isPast() ? 'danger' : 'gray'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('role')
                    ->options(fn (): array => static::roleNames()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function roleNames(): array
    {
        $site = filament()->getTenant();

        return $site instanceof Site ? Role::optionsForSite($site) : [];
    }
}
