<?php

namespace App\Blocks;

use App\Support\BlockDefinition;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;

class TextBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('text')
            ->label('Text')
            ->icon('heroicon-o-document-text')
            ->schema(fn () => [
                TextInput::make('heading')->label(fn () => __('Heading'))->columnSpanFull(),
                RichEditor::make('content')->label(fn () => __('Content'))->columnSpanFull(),
            ]);
    }
}
