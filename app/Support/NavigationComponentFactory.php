<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Support;

use App\Models\Content;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;

class NavigationComponentFactory
{
    public static function make(NavigationDefinition $definition): Repeater
    {
        return Repeater::make('navigations.'.$definition->key)
            ->label(fn () => __($definition->label))
            ->schema([
                TextInput::make('label')
                    ->label(fn () => __('common.label'))
                    ->required()
                    ->live(debounce: 500)
                    ->columnSpanFull(),
                Select::make('content_id')
                    ->label(fn () => __('content.types.page'))
                    ->options(fn () => Content::query()
                        ->where('site_id', filament()->getTenant()?->id)
                        ->whereIn('type', ['page', 'archive'])
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
                            $set('url', $content->is_homepage ? '/' : '/'.$content->slug);
                        }
                    })
                    ->placeholder(fn () => __('navigation.labels.url_manual'))
                    ->nullable(),
                TextInput::make('url')
                    ->label(fn () => __('common.url'))
                    ->live(debounce: 500)
                    ->nullable(),
                Select::make('target')
                    ->label(fn () => __('navigation.labels.target'))
                    ->options([
                        '_self' => __('navigation.options.same_tab'),
                        '_blank' => __('navigation.options.new_tab'),
                    ])
                    ->default('_self'),
                static::iconSelect(),
                Textarea::make('description')
                    ->label(fn () => __('common.description'))
                    ->rows(2)
                    ->columnSpanFull(),
                Repeater::make('children')
                    ->label(fn () => __('navigation.labels.submenu'))
                    ->schema([
                        TextInput::make('label')
                            ->label(fn () => __('common.label'))
                            ->nullable()
                            ->columnSpanFull(),
                        Select::make('content_id')
                            ->label(fn () => __('content.types.page'))
                            ->options(fn () => Content::query()
                                ->where('site_id', filament()->getTenant()?->id)
                                ->whereIn('type', ['page', 'archive'])
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
                                    $set('url', $content->is_homepage ? '/' : '/'.$content->slug);
                                }
                            })
                            ->placeholder(fn () => __('navigation.labels.url_manual'))
                            ->nullable(),
                        TextInput::make('url')
                            ->label(fn () => __('common.url'))
                            ->nullable(),
                        Select::make('target')
                            ->label(fn () => __('navigation.labels.target'))
                            ->options([
                                '_self' => __('navigation.options.same_tab'),
                                '_blank' => __('navigation.options.new_tab'),
                            ])
                            ->nullable()
                            ->default('_self'),
                        static::iconSelect(),
                        Textarea::make('description')
                            ->label(fn () => __('common.description'))
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull(),
            ])
            ->columns(2)
            ->collapsible()
            ->reorderableWithButtons()
            ->columnSpanFull();
    }

    private static function iconSelect(): Select
    {
        return Select::make('icon')
            ->label(fn () => __('navigation.labels.icon'))
            ->allowHtml()
            ->nullable()
            ->live(debounce: 300)
            ->optionsLimit(200)
            ->options(fn () => NavigationIconRegistry::selectOptions());
    }
}
