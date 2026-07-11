<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Taxonomies\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Laravix\Cms\Models\Taxonomy;
use Laravix\Cms\Support\TaxonomyTypeRegistry;

class TaxonomyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('laravix::common.general'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('laravix::common.title'))
                            ->required()
                            ->maxLength(255)
                            ->live(debounce: 500)
                            ->afterStateUpdated(fn (TextInput $component, ?string $state) => $component->getContainer()->getComponent('slug')
                                ?->state(str($state ?? '')->slug()->toString())
                            ),
                        TextInput::make('slug')
                            ->label(__('laravix::common.slug'))
                            ->required()
                            ->maxLength(255)
                            ->key('slug')
                            ->prefix('/')
                            ->unique(table: 'taxonomies', column: 'slug', ignoreRecord: true, modifyRuleUsing: fn ($rule, callable $get) => $rule->where('site_id', filament()->getTenant()?->id))
                            ->helperText(__('laravix::common.must_be_unique')),
                        Select::make('type')
                            ->label(__('laravix::common.type'))
                            ->required()
                            ->options(fn () => TaxonomyTypeRegistry::options())
                            ->default('category'),
                    ]),
                Section::make(__('laravix::taxonomy.sections.translations'))
                    ->columns(2)
                    ->visible(fn () => filament()->getTenant()?->isMultilingual() ?? false)
                    ->schema(function (): array {
                        $site = filament()->getTenant();
                        $default = $site?->defaultLocale() ?? 'en';
                        $components = [];

                        foreach ($site?->enabledLocales() ?? [] as $locale) {
                            if ($locale === $default) {
                                continue;
                            }

                            $components[] = TextInput::make("translations.{$locale}.name")
                                ->label(__('laravix::common.title').' ('.strtoupper($locale).')')
                                ->maxLength(255);
                            $components[] = TextInput::make("translations.{$locale}.slug")
                                ->label(__('laravix::common.slug').' ('.strtoupper($locale).')')
                                ->prefix('/')
                                ->maxLength(255);
                        }

                        return $components;
                    }),
                Section::make(__('laravix::taxonomy.sections.hierarchy'))
                    ->schema([
                        Select::make('parent_id')
                            ->label(__('laravix::common.parent'))
                            ->options(fn (?Taxonomy $record) => Taxonomy::query()
                                ->where('site_id', filament()->getTenant()?->id)
                                ->when($record, fn ($q) => $q->whereNot('id', $record->id))
                                ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->helperText(__('laravix::taxonomy.messages.optional_parent')),
                    ]),
            ]);
    }
}
