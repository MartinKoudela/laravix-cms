<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\ContentTypeFields\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Laravix\Cms\Enums\FieldType;
use Laravix\Cms\Models\ContentTypeField;
use Laravix\Cms\Support\ContentTypeRegistry;

class ContentTypeFieldForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->schema([
                Select::make('content_type')
                    ->label(__('laravix::content_type_field.fields.content_type'))
                    ->options(fn () => ContentTypeRegistry::options())
                    ->required(),
                Select::make('type')
                    ->label(__('laravix::content_type_field.fields.type'))
                    ->options(collect(FieldType::cases())->mapWithKeys(
                        fn (FieldType $case) => [$case->value => $case->name]
                    ))
                    ->required(),
                TextInput::make('key')
                    ->label(__('laravix::content_type_field.fields.key'))
                    ->required()
                    ->maxLength(255)
                    ->alphaDash()
                    ->disabled(fn (?ContentTypeField $record) => $record !== null)
                    ->dehydrated(fn (?ContentTypeField $record) => $record === null),
                TextInput::make('label')
                    ->label(__('laravix::content_type_field.fields.label'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('group')
                    ->label(__('laravix::content_type_field.fields.group'))
                    ->maxLength(255),
                TextInput::make('hint')
                    ->label(__('laravix::content_type_field.fields.hint'))
                    ->maxLength(255),
                TextInput::make('sort_order')
                    ->label(__('laravix::content_type_field.fields.sort_order'))
                    ->numeric()
                    ->default(0),
                Toggle::make('required')
                    ->label(__('laravix::content_type_field.fields.required'))
                    ->default(false),
            ]),
        ]);
    }
}
