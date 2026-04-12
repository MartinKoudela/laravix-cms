<?php

namespace App\Filament\Resources\Media\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('site_id')
                    ->relationship('site', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('path')
                    ->required(),
                TextInput::make('disk')
                    ->required(),
                TextInput::make('mime_type')
                    ->required(),
                TextInput::make('size')
                    ->required()
                    ->numeric(),
                TextInput::make('created_by')
                    ->required()
                    ->numeric(),
            ]);
    }
}
