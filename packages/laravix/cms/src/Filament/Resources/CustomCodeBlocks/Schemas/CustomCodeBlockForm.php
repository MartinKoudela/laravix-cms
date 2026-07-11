<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\CustomCodeBlocks\Schemas;

use Filament\Forms\Components\CodeEditor;
use Filament\Forms\Components\CodeEditor\Enums\Language;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Laravix\Cms\Support\BlockIconRegistry;

class CustomCodeBlockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make(__('laravix::common.general'))
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('laravix::common.name'))
                                    ->required()
                                    ->maxLength(255),
                                Select::make('icon')
                                    ->label('Icon')
                                    ->allowHtml()
                                    ->nullable()
                                    ->optionsLimit(200)
                                    ->options(fn () => BlockIconRegistry::selectOptions()),
                            ]),
                        Tab::make('HTML')
                            ->schema([
                                CodeEditor::make('html_content')
                                    ->language(Language::Html)
                                    ->label('HTML'),
                            ]),
                        Tab::make('CSS')
                            ->schema([
                                CodeEditor::make('css_content')
                                    ->language(Language::Css)
                                    ->label('CSS'),
                            ]),
                        Tab::make('JavaScript')
                            ->schema([
                                CodeEditor::make('js_content')
                                    ->language(Language::JavaScript)
                                    ->label('JavaScript'),
                            ]),
                    ]),
            ]);
    }
}
