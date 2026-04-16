<?php

namespace App\Filament\Resources\Taxonomies\Schemas;

use App\Models\Taxonomy;
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
                            ->afterStateUpdated(fn (TextInput $component, ?string $state) => $component->getContainer()->getComponent('slug')
                                ?->state(str($state ?? '')->slug()->toString())
                            ),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->key('slug')
                            ->prefix('/')
                            ->unique(table: 'taxonomies', column: 'slug', ignoreRecord: true, modifyRuleUsing: fn ($rule, callable $get) => $rule->where('site_id', filament()->getTenant()?->id))
                            ->helperText('Must be unique per site.'),
                        Select::make('type')
                            ->required()
                            ->options([
                                'category' => 'Category',
                                'tag' => 'Tag',
                            ])
                            ->default('category'),
                    ]),
                Section::make('Hierarchy')
                    ->schema([
                        Select::make('parent_id')
                            ->options(fn (?Taxonomy $record) => Taxonomy::query()
                                ->where('site_id', filament()->getTenant()?->id)
                                ->when($record, fn ($q) => $q->whereNot('id', $record->id))
                                ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->helperText('Optional. Set a parent to create nested categories.'),
                    ]),
            ]);
    }
}
