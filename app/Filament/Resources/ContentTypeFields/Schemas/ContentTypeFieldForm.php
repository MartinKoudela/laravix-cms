<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Filament\Resources\ContentTypeFields\Schemas;

use App\Enums\FieldType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContentTypeFieldForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->schema([
                Select::make('content_type')
                    ->label(__('content_type_field.fields.content_type'))
                    ->options([
                        'page' => __('content.types.page'),
                        'post' => __('content.types.post'),
                        'archive' => __('content.types.archive'),
                    ])
                    ->required(),
                Select::make('type')
                    ->label(__('content_type_field.fields.type'))
                    ->options(collect(FieldType::cases())->mapWithKeys(
                        fn (FieldType $case) => [$case->value => $case->name]
                    ))
                    ->required(),
                TextInput::make('key')
                    ->label(__('content_type_field.fields.key'))
                    ->required()
                    ->maxLength(255)
                    ->alphaDash(),
                TextInput::make('label')
                    ->label(__('content_type_field.fields.label'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('group')
                    ->label(__('content_type_field.fields.group'))
                    ->maxLength(255),
                TextInput::make('hint')
                    ->label(__('content_type_field.fields.hint'))
                    ->maxLength(255),
                TextInput::make('sort_order')
                    ->label(__('content_type_field.fields.sort_order'))
                    ->numeric()
                    ->default(0),
                Toggle::make('required')
                    ->label(__('content_type_field.fields.required'))
                    ->default(false),
            ]),
        ]);
    }
}
