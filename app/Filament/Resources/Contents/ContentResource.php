<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Filament\Resources\Contents;

use App\Filament\Resources\Contents\Pages\CreateContent;
use App\Filament\Resources\Contents\Pages\EditContent;
use App\Filament\Resources\Contents\Pages\ListContents;
use App\Filament\Resources\Contents\RelationManagers\RedirectsRelationManager;
use App\Filament\Resources\Contents\RelationManagers\RevisionsRelationManager;
use App\Filament\Resources\Contents\Schemas\ContentForm;
use App\Filament\Resources\Contents\Tables\ContentsTable;
use App\Models\Content;
use App\Support\ContentTypeDefinition;
use App\Support\ContentTypeRegistry;
use BackedEnum;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use function Filament\Support\original_request;

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

    public static function getNavigationItems(): array
    {
        $defaultKey = ContentTypeRegistry::default()->key;

        $typeItem = fn (ContentTypeDefinition $type) => NavigationItem::make(fn () => __($type->pluralLabel))
            ->url(fn () => static::getUrl('index', ['type' => $type->key]))
            ->isActiveWhen(fn (): bool => original_request()->routeIs(static::getRouteBaseName().'.index')
                && original_request()->query('type', $defaultKey) === $type->key);

        [$item] = parent::getNavigationItems();

        return [
            $item->childItems(array_map($typeItem, array_values(ContentTypeRegistry::all()))),
        ];
    }

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
