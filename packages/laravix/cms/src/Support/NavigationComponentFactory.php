<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Support;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Laravix\Cms\Models\Content;

class NavigationComponentFactory
{
    public static function make(NavigationDefinition $definition): Repeater
    {
        return Repeater::make('navigations.'.$definition->key)
            ->label(fn () => __($definition->label))
            ->schema([
                ...static::itemFields(),
                Section::make(fn () => __('laravix::navigation.labels.submenu'))
                    ->description(fn () => __('laravix::navigation.hints.submenu'))
                    ->schema([
                        Repeater::make('children')
                            ->hiddenLabel()
                            ->schema(static::itemFields(child: true))
                            ->itemLabel(fn (array $state) => static::itemLabel($state))
                            ->addActionLabel(fn () => __('laravix::navigation.actions.add_child'))
                            ->columns(2)
                            ->collapsible()
                            ->collapsed()
                            ->reorderableWithButtons()
                            ->defaultItems(0)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull(),
            ])
            ->itemLabel(fn (array $state) => static::itemLabel($state))
            ->addActionLabel(fn () => __('laravix::navigation.actions.add_item'))
            ->columns(2)
            ->collapsible()
            ->collapsed()
            ->reorderableWithButtons()
            ->columnSpanFull();
    }

    private static function itemFields(bool $child = false): array
    {
        return [
            TextInput::make('label')
                ->label(fn () => __('laravix::common.label'))
                ->required(! $child)
                ->nullable($child)
                ->live(debounce: 500)
                ->columnSpanFull(),
            Select::make('content_id')
                ->label(fn () => __('laravix::content.types.page'))
                ->options(fn () => Content::query()
                    ->where('site_id', filament()->getTenant()?->id)
                    ->whereIn('type', ContentTypeRegistry::navigationLinkableKeys())
                    ->where('status', 'published')
                    ->orderBy('title')
                    ->pluck('title', 'id')
                )
                ->searchable()
                ->live()
                ->afterStateUpdated(function (?int $state, Set $set) {
                    if (! $state) {
                        return;
                    }
                    $content = Content::find($state);
                    if ($content) {
                        $set('url', $content->path(filament()->getTenant()?->defaultLocale() ?? 'en'));
                    }
                })
                ->placeholder(fn () => __('laravix::navigation.labels.url_manual'))
                ->nullable(),
            TextInput::make('url')
                ->label(fn () => __('laravix::common.url'))
                ->live(debounce: 500)
                ->nullable(),
            Select::make('target')
                ->label(fn () => __('laravix::navigation.labels.target'))
                ->options([
                    '_self' => __('laravix::navigation.options.same_tab'),
                    '_blank' => __('laravix::navigation.options.new_tab'),
                ])
                ->default('_self'),
            static::iconSelect(),
            Textarea::make('description')
                ->label(fn () => __('laravix::common.description'))
                ->rows(2)
                ->columnSpanFull(),
            Section::make(fn () => __('laravix::navigation.sections.translations'))
                ->visible(fn () => filament()->getTenant()?->isMultilingual() ?? false)
                ->schema(function (): array {
                    $site = filament()->getTenant();
                    $default = $site?->defaultLocale() ?? 'en';
                    $components = [];

                    foreach ($site?->enabledLocales() ?? [] as $locale) {
                        if ($locale === $default) {
                            continue;
                        }
                        $components[] = TextInput::make("translations.{$locale}.label")
                            ->label(__('laravix::common.label').' ('.strtoupper($locale).')')
                            ->nullable();
                    }

                    return $components;
                })
                ->collapsible()
                ->collapsed()
                ->columnSpanFull(),
        ];
    }

    private static function itemLabel(array $state): string
    {
        $label = filled($state['label'] ?? null)
            ? $state['label']
            : (filled($state['url'] ?? null) ? $state['url'] : __('laravix::navigation.labels.untitled'));

        $childCount = count($state['children'] ?? []);

        return $childCount > 0
            ? $label.' — '.trans_choice('laravix::navigation.labels.submenu_count', $childCount, ['count' => $childCount])
            : $label;
    }

    private static function iconSelect(): Select
    {
        return Select::make('icon')
            ->label(fn () => __('laravix::navigation.labels.icon'))
            ->allowHtml()
            ->nullable()
            ->live(debounce: 300)
            ->optionsLimit(200)
            ->options(fn () => NavigationIconRegistry::selectOptions());
    }
}
