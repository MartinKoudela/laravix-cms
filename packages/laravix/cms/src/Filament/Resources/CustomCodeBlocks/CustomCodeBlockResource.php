<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\CustomCodeBlocks;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Laravix\Cms\Filament\Resources\CustomCodeBlocks\Pages\CreateCustomCodeBlock;
use Laravix\Cms\Filament\Resources\CustomCodeBlocks\Pages\EditCustomCodeBlock;
use Laravix\Cms\Filament\Resources\CustomCodeBlocks\Pages\ListCustomCodeBlocks;
use Laravix\Cms\Filament\Resources\CustomCodeBlocks\Schemas\CustomCodeBlockForm;
use Laravix\Cms\Filament\Resources\CustomCodeBlocks\Tables\CustomCodeBlocksTable;
use Laravix\Cms\Models\CustomCodeBlock;

class CustomCodeBlockResource extends Resource
{
    public static function getModelLabel(): string
    {
        return __('laravix::custom_code_blocks.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('laravix::custom_code_blocks.plural');
    }

    protected static ?string $model = CustomCodeBlock::class;

    protected static string|null|\UnitEnum $navigationGroup = 'Content';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCodeBracketSquare;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CustomCodeBlockForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomCodeBlocksTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomCodeBlocks::route('/'),
            'create' => CreateCustomCodeBlock::route('/create'),
            'edit' => EditCustomCodeBlock::route('/{record}/edit'),
        ];
    }
}
