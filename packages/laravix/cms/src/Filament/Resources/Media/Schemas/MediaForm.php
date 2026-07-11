<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Resources\Media\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('common.general'))
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('path')
                            ->label(__('common.file'))
                            ->required()
                            ->disk('public')
                            ->directory('media')
                            ->storeFileNamesIn('name')
                            ->acceptedFileTypes(['image/*', 'video/*', 'audio/*'])
                            ->maxSize(524288)
                            ->imageEditor()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
