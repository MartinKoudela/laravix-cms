<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Contents\Schemas;

use Laravix\Cms\Enums\ContentStatus;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\Taxonomy;
use Laravix\Cms\Support\ContentTypeRegistry;
use Laravix\Cms\Support\FieldComponentFactory;
use Laravix\Cms\Support\FieldRegistry;
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
        return $schema
            ->components([
                Tabs::make()
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make(__('content.sections.content'))
                            ->schema(fn (Get $get): array => [
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
                                            ->unique(table: 'contents', column: 'slug', ignoreRecord: true, modifyRuleUsing: fn ($rule, Get $get) => $rule
                                                ->where('site_id', filament()->getTenant()?->id)
                                                ->where('locale', $get('locale') ?: filament()->getTenant()?->defaultLocale() ?? 'en'))
                                            ->helperText(__('common.must_be_unique')),
                                        Toggle::make('is_homepage')
                                            ->label(__('content.messages.set_as_homepage'))
                                            ->helperText(__('content.messages.only_one_homepage'))
                                            ->live()
                                            ->afterStateUpdated(fn (bool $state, Set $set) => $state ? $set('slug', '/') : null)
                                            ->columnSpanFull()
                                            ->hidden(function (?Content $record, Get $get): bool {
                                                if ($record?->is_homepage) {
                                                    return false;
                                                }

                                                $siteId = filament()->getTenant()?->id;

                                                if (! $siteId) {
                                                    return false;
                                                }

                                                return Content::where('site_id', $siteId)
                                                    ->where('is_homepage', true)
                                                    ->where('locale', $get('locale') ?: filament()->getTenant()?->defaultLocale() ?? 'en')
                                                    ->exists();
                                            }),
                                    ]),
                                Section::make(__('content.sections.publishing'))
                                    ->columns(2)
                                    ->schema([
                                        Select::make('type')
                                            ->label(__('common.type'))
                                            ->required()
                                            ->options(fn () => ContentTypeRegistry::options())
                                            ->default(fn () => ContentTypeRegistry::default()->key)
                                            ->disabled(fn ($record) => $record !== null)
                                            ->dehydrated()
                                            ->live(),
                                        Select::make('locale')
                                            ->label(__('content.fields.locale'))
                                            ->required()
                                            ->options(fn () => collect(filament()->getTenant()?->enabledLocales() ?? ['en'])
                                                ->mapWithKeys(fn (string $locale) => [$locale => strtoupper($locale)]))
                                            ->default(fn () => filament()->getTenant()?->defaultLocale() ?? 'en')
                                            ->disabled(fn ($record) => $record !== null)
                                            ->dehydrated()
                                            ->live()
                                            ->visible(fn () => filament()->getTenant()?->isMultilingual() ?? false),
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
                                            ->options(function (Get $get) {
                                                $taxonomyTypes = ContentTypeRegistry::find($get('type') ?? '')?->taxonomyTypes ?? [];

                                                return Taxonomy::where('site_id', filament()->getTenant()?->id)
                                                    ->when($taxonomyTypes !== [], fn ($q) => $q->whereIn('type', $taxonomyTypes))
                                                    ->pluck('name', 'id');
                                            }),
                                    ]),
                                ...static::fieldSections(static::contentGroups($get)),
                            ]),
                        Tab::make(__('common.seo'))
                            ->schema(fn (Get $get): array => [
                                Section::make()
                                    ->columns(2)
                                    ->schema(array_map(
                                        fn ($definition) => FieldComponentFactory::make($definition),
                                        static::groupedFields($get)['content.sections.seo_group'] ?? [],
                                    )),
                            ]),
                        Tab::make(__('content.sections.builder'))
                            ->hidden(fn (Get $get, ?Content $record): bool => ! (ContentTypeRegistry::find($get('type') ?? '')?->hasBuilder ?? false) || $record === null || filament()->getTenant()?->isHeadless())
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

    private static function groupedFields(Get $get): array
    {
        return FieldRegistry::grouped(type: $get('type'), siteId: filament()->getTenant()?->id);
    }

    private static function contentGroups(Get $get): array
    {
        return array_filter(
            static::groupedFields($get),
            fn (string $group) => $group !== 'content.sections.seo_group',
            ARRAY_FILTER_USE_KEY,
        );
    }

    private static function fieldSections(array $groups): array
    {
        return array_map(
            fn (string $group, array $definitions) => Section::make(__($group))
                ->columns(2)
                ->schema(array_map(
                    fn ($definition) => FieldComponentFactory::make($definition),
                    $definitions,
                )),
            array_keys($groups),
            array_values($groups),
        );
    }
}
