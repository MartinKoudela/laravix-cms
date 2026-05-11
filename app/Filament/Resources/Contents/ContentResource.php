<?php

namespace App\Filament\Resources\Contents;

use App\Filament\Resources\Contents\Pages\CreateContent;
use App\Filament\Resources\Contents\Pages\EditContent;
use App\Filament\Resources\Contents\Pages\ListContents;
use App\Filament\Resources\Contents\RelationManagers\FieldsRelationManager;
use App\Filament\Resources\Contents\RelationManagers\RedirectsRelationManager;
use App\Filament\Resources\Contents\RelationManagers\RevisionsRelationManager;
use App\Filament\Resources\Contents\Schemas\ContentForm;
use App\Filament\Resources\Contents\Tables\ContentsTable;
use App\Models\Content;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ContentResource extends Resource
{
    public static function getModelLabel(): string
    {
        return __('content.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('content.plural');
    }

    protected static ?string $model = Content::class;

    protected static string|null|\UnitEnum $navigationGroup = 'Content';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return ContentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ContentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            FieldsRelationManager::class,
            RevisionsRelationManager::class,
            RedirectsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListContents::route('/'),
            'create' => CreateContent::route('/create'),
            'edit' => EditContent::route('/{record}/edit'),
        ];
    }
}
