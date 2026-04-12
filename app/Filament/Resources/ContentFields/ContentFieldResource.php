<?php

namespace App\Filament\Resources\ContentFields;

use App\Filament\Resources\ContentFields\Pages\CreateContentField;
use App\Filament\Resources\ContentFields\Pages\EditContentField;
use App\Filament\Resources\ContentFields\Pages\ListContentFields;
use App\Filament\Resources\ContentFields\Schemas\ContentFieldForm;
use App\Filament\Resources\ContentFields\Tables\ContentFieldsTable;
use App\Models\ContentField;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContentFieldResource extends Resource
{
    protected static ?string $model = ContentField::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'key';

    public static function form(Schema $schema): Schema
    {
        return ContentFieldForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContentFieldsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContentFields::route('/'),
            'create' => CreateContentField::route('/create'),
            'edit' => EditContentField::route('/{record}/edit'),
        ];
    }
}
