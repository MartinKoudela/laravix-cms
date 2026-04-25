<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\SiteRole;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('E-mail'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->label(__('Role'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        SiteRole::ADMIN->value => 'warning',
                        SiteRole::EDITOR->value => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('email_verified_at')
                    ->label(__('Verified'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('Created at'))
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
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
