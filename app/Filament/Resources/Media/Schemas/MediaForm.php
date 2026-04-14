<?php

namespace App\Filament\Resources\Media\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General')
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('path')
                            ->label('File')
                            ->required()
                            ->disk('public')
                            ->directory('media')
                            ->storeFileNamesIn('name')
                            ->maxSize(10240)
                            ->imageEditor()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
