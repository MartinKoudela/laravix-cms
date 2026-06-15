<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Filament\Resources\ContentTypeFields;

use App\Filament\Resources\ContentTypeFields\Pages\CreateContentTypeField;
use App\Filament\Resources\ContentTypeFields\Pages\EditContentTypeField;
use App\Filament\Resources\ContentTypeFields\Pages\ListContentTypeFields;
use App\Filament\Resources\ContentTypeFields\Schemas\ContentTypeFieldForm;
use App\Filament\Resources\ContentTypeFields\Tables\ContentTypeFieldsTable;
use App\Models\ContentTypeField;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContentTypeFieldResource extends Resource
{
    protected static ?string $model = ContentTypeField::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|null|\UnitEnum $navigationGroup = 'Content';

    protected static ?string $recordTitleAttribute = 'label';

    public static function getModelLabel(): string
    {
        return __('content_type_field.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('content_type_field.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return ContentTypeFieldForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContentTypeFieldsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContentTypeFields::route('/'),
            'create' => CreateContentTypeField::route('/create'),
            'edit' => EditContentTypeField::route('/{record}/edit'),
        ];
    }
}
