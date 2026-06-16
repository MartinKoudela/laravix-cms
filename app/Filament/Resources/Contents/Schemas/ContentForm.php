<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Filament\Resources\Contents\Schemas;

use App\Enums\ContentStatus;
use App\Models\Content;
use App\Models\Taxonomy;
use App\Support\FieldComponentFactory;
use App\Support\FieldRegistry;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class ContentForm
{
    public static function configure(Schema $schema): Schema
    {
        $grouped = FieldRegistry::grouped(siteId: filament()->getTenant()?->id);
        $seoDefinitions = $grouped['content.sections.seo_group'] ?? [];
        $contentGroups = array_filter($grouped, fn (string $key) => $key !== 'content.sections.seo_group', ARRAY_FILTER_USE_KEY);

        return $schema
            ->components([
                Tabs::make()
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make(__('content.sections.content'))
                            ->schema([
                                Section::make(__('common.general'))
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('title')
                                            ->label(__('common.title'))
                                            ->required()
                                            ->maxLength(255)
                                            ->live(debounce: 500)
                                            ->afterStateUpdated(fn (TextInput $component, ?string $state) => $component->getContainer()->getComponent('slug')
                                                ?->state(str($state ?? '')->slug()->toString())
                                            )
                                            ->columnSpanFull(),
                                        TextInput::make('slug')
                                            ->label(__('common.slug'))
                                            ->required()
                                            ->maxLength(255)
                                            ->key('slug')
                                            ->prefix('/')
                                            ->unique(table: 'contents', column: 'slug', ignoreRecord: true, modifyRuleUsing: fn ($rule) => $rule->where('site_id', filament()->getTenant()?->id))
                                            ->helperText(__('common.must_be_unique')),
                                        Toggle::make('is_homepage')
                                            ->label(__('content.messages.set_as_homepage'))
                                            ->helperText(__('content.messages.only_one_homepage'))
                                            ->live()
                                            ->afterStateUpdated(fn (bool $state, Set $set) => $state ? $set('slug', '/') : null)
                                            ->columnSpanFull()
                                            ->hidden(function (?Content $record): bool {
                                                if ($record?->is_homepage) {
                                                    return false;
                                                }

                                                $siteId = filament()->getTenant()?->id;

                                                if (! $siteId) {
                                                    return false;
                                                }

                                                return Content::where('site_id', $siteId)
                                                    ->where('is_homepage', true)
                                                    ->exists();
                                            }),
                                    ]),
                                Section::make(__('content.sections.publishing'))
                                    ->columns(2)
                                    ->schema([
                                        Select::make('type')
                                            ->label(__('common.type'))
                                            ->required()
                                            ->options([
                                                'page' => __('content.types.page'),
                                                'post' => __('content.types.post'),
                                                'archive' => __('content.types.archive'),
                                            ])
                                            ->default('page')
                                            ->disabled(fn ($record) => $record !== null)
                                            ->dehydrated(),
                                        Select::make('status')
                                            ->label(__('common.status'))
                                            ->required()
                                            ->options(collect(ContentStatus::cases())->mapWithKeys(
                                                fn (ContentStatus $case) => [$case->value => $case->name]
                                            ))
                                            ->default(ContentStatus::DRAFT->value)
                                            ->live(),
                                        DateTimePicker::make('published_at')
                                            ->visible(fn (Get $get): bool => $get('status') === ContentStatus::SCHEDULED->value),
                                    ]),
                                Section::make(__('content.sections.taxonomies'))
                                    ->schema([
                                        Select::make('taxonomies')
                                            ->label(__('content.sections.taxonomies'))
                                            ->relationship('taxonomies', 'name')
                                            ->multiple()
                                            ->searchable()
                                            ->preload()
                                            ->options(fn () => Taxonomy::where('site_id', filament()->getTenant()?->id)
                                                ->pluck('name', 'id')
                                            ),
                                    ]),
                                ...array_map(
                                    fn (string $group, array $definitions) => Section::make(__($group))
                                        ->schema(array_map(
                                            fn ($definition) => FieldComponentFactory::make($definition),
                                            $definitions,
                                        ))
                                        ->columns(2),
                                    array_keys($contentGroups),
                                    array_values($contentGroups),
                                ),
                            ]),
                        Tab::make(__('common.seo'))
                            ->schema([
                                Section::make()
                                    ->columns(2)
                                    ->schema(array_map(
                                        fn ($definition) => FieldComponentFactory::make($definition),
                                        $seoDefinitions,
                                    )),
                            ]),
                        Tab::make(__('content.sections.builder'))
                            ->hidden(fn (Get $get, ?Content $record): bool => $get('type') !== 'page' || $record === null)
                            ->schema([
                                View::make('filament.partials.block-builder')
                                    ->viewData(fn ($livewire) => [
                                        'record' => $livewire->record,
                                    ])
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
