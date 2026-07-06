<?php

/**
 * Laravix Changelog Plugin — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Changelog\Filament\Resources\ChangelogReleases;

use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Laravix\Changelog\Models\ChangelogItem;
use Laravix\Changelog\Models\ChangelogRelease;

class ChangelogReleaseResource extends Resource
{
    protected static ?string $model = ChangelogRelease::class;

    protected static ?string $tenantOwnershipRelationshipName = 'site';

    protected static string|null|\UnitEnum $navigationGroup = 'Content';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static ?string $recordTitleAttribute = 'version';

    public static function getModelLabel(): string
    {
        return __('changelog::changelog.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('changelog::changelog.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->schema([
                TextInput::make('version')
                    ->label(__('changelog::changelog.fields.version'))
                    ->required()
                    ->maxLength(50)
                    ->placeholder('1.4.0'),
                DatePicker::make('released_at')
                    ->label(__('changelog::changelog.fields.released_at'))
                    ->required()
                    ->default(now()),
                TextInput::make('title')
                    ->label(__('changelog::changelog.fields.title'))
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]),
            Section::make(__('changelog::changelog.fields.items'))->schema([
                Repeater::make('items')
                    ->hiddenLabel()
                    ->relationship('items')
                    ->orderColumn('sort_order')
                    ->schema([
                        Select::make('type')
                            ->label(__('changelog::changelog.fields.type'))
                            ->options(collect(ChangelogItem::TYPES)->mapWithKeys(
                                fn (string $type) => [$type => __('changelog::changelog.types.'.$type)]
                            ))
                            ->default('added')
                            ->required(),
                        Textarea::make('text')
                            ->label(__('changelog::changelog.fields.text'))
                            ->rows(2)
                            ->required(),
                    ])
                    ->columns(2)
                    ->reorderableWithButtons()
                    ->addActionLabel(__('changelog::changelog.actions.add_item'))
                    ->defaultItems(1),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('version')
                    ->label(__('changelog::changelog.fields.version'))
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label(__('changelog::changelog.fields.title'))
                    ->searchable(),
                TextColumn::make('items_count')
                    ->label(__('changelog::changelog.fields.items'))
                    ->counts('items'),
                TextColumn::make('released_at')
                    ->label(__('changelog::changelog.fields.released_at'))
                    ->date()
                    ->sortable(),
            ])
            ->defaultSort('released_at', 'desc')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChangelogReleases::route('/'),
            'create' => Pages\CreateChangelogRelease::route('/create'),
            'edit' => Pages\EditChangelogRelease::route('/{record}/edit'),
        ];
    }
}
