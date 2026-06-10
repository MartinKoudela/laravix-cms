<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */


namespace App\Filament\Resources\CustomCodeBlocks;

use App\Filament\Resources\CustomCodeBlocks\Pages\CreateCustomCodeBlock;
use App\Filament\Resources\CustomCodeBlocks\Pages\EditCustomCodeBlock;
use App\Filament\Resources\CustomCodeBlocks\Pages\ListCustomCodeBlocks;
use App\Filament\Resources\CustomCodeBlocks\Schemas\CustomCodeBlockForm;
use App\Filament\Resources\CustomCodeBlocks\Tables\CustomCodeBlocksTable;
use App\Models\CustomCodeBlock;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomCodeBlockResource extends Resource
{

    public static function getModelLabel(): string
    {
        return __('custom_code_blocks.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('custom_code_blocks.plural');
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
