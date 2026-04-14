<?php

namespace App\Filament\Resources\Sites\RelationManagers;

use App\Enums\SiteRole;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('role')
                    ->options(collect(SiteRole::cases())->mapWithKeys(
                        fn (SiteRole $case) => [$case->value => $case->name]
                    ))
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable()
                    ->color('gray'),
                TextColumn::make('pivot.role')
                    ->label('Role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        SiteRole::ADMIN->value => 'warning',
                        SiteRole::EDITOR->value => 'info',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Select::make('role')
                            ->options(collect(SiteRole::cases())->mapWithKeys(
                                fn (SiteRole $case) => [$case->value => $case->name]
                            ))
                            ->required(),
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
