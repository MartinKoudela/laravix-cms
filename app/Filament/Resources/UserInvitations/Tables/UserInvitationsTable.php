<?php

namespace App\Filament\Resources\UserInvitations\Tables;

use App\Enums\SiteRole;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UserInvitationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        SiteRole::ADMIN->value => 'warning',
                        SiteRole::EDITOR->value => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('accepted_at')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state): string => $state ? 'success' : 'gray')
                    ->formatStateUsing(fn ($state): string => $state ? 'Accepted' : 'Pending')
                    ->sortable(),
                TextColumn::make('invitedBy.name')
                    ->label('Invited by')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('expires_at')
                    ->label('Expires')
                    ->dateTime()
                    ->sortable()
                    ->color(fn ($state): string => $state?->isPast() ? 'danger' : 'gray'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('role')
                    ->options(collect(SiteRole::cases())->mapWithKeys(
                        fn (SiteRole $case) => [$case->value => $case->name]
                    )),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
