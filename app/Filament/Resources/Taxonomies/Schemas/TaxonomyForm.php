<?php

namespace App\Filament\Resources\Taxonomies\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TaxonomyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(debounce: 500)
                            ->afterStateUpdated(fn (TextInput $component, ?string $state) =>
                                $component->getContainer()->getComponent('slug')
                                    ?->state(str($state ?? '')->slug()->toString())
                            ),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->key('slug')
                            ->prefix('/'),
                        Select::make('type')
                            ->required()
                            ->options([
                                'category' => 'Category',
                                'tag' => 'Tag',
                            ])
                            ->default('category'),
                        Select::make('site_id')
                            ->relationship('site', 'name')
                            ->required()
                            ->searchable(),
                    ]),
                Section::make('Hierarchy')
                    ->schema([
                        Select::make('parent_id')
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->helperText('Optional. Set a parent to create nested categories.'),
                    ]),
            ]);
    }
}
