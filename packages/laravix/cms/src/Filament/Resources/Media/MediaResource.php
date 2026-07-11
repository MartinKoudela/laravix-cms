<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Media;

use Laravix\Cms\Filament\Resources\Media\Pages\CreateMedia;
use Laravix\Cms\Filament\Resources\Media\Pages\EditMedia;
use Laravix\Cms\Filament\Resources\Media\Pages\ListMedia;
use Laravix\Cms\Filament\Resources\Media\Schemas\MediaForm;
use Laravix\Cms\Filament\Resources\Media\Tables\MediaTable;
use Laravix\Cms\Models\Media;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MediaResource extends Resource
{
    public static function getModelLabel(): string
    {
        return __('media.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('media.plural');
    }

    protected static ?string $model = Media::class;

    protected static string|null|\UnitEnum $navigationGroup = 'Content';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return MediaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MediaTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMedia::route('/'),
            'create' => CreateMedia::route('/create'),
            'edit' => EditMedia::route('/{record}/edit'),
        ];
    }
}
