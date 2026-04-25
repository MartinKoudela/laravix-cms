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
                Section::make(__('General'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Title'))
                            ->required()
                            ->maxLength(255)
                            ->live(debounce: 500)
                            ->afterStateUpdated(fn (TextInput $component, ?string $state) => $component->getContainer()->getComponent('slug')
                                ?->state(str($state ?? '')->slug()->toString())
                            ),
                        TextInput::make('slug')
                            ->label(__('Slug'))
                            ->required()
                            ->maxLength(255)
                            ->key('slug')
                            ->prefix('/')
                            ->unique(table: 'taxonomies', column: 'slug', ignoreRecord: true, modifyRuleUsing: fn ($rule, callable $get) => $rule->where('site_id', filament()->getTenant()?->id))
                            ->helperText(__('Must be unique per site.')),
                        Select::make('type')
                            ->label(__('Type'))
                            ->required()
                            ->options([
                                'category' => __('Category'),
                                'tag' => __('Tag'),
                            ])
                            ->default('category'),
                    ]),
                Section::make(__('Hierarchy'))
                    ->schema([
                        Select::make('parent_id')
                            ->label(__('Parent'))
                            ->options(fn (?Taxonomy $record) => Taxonomy::query()
                                ->where('site_id', filament()->getTenant()?->id)
                                ->when($record, fn ($q) => $q->whereNot('id', $record->id))
                                ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->helperText(__('Optional. Set a parent to create nested categories.')),
                    ]),
            ]);
    }
}
