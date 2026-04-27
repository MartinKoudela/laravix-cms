<?php

namespace App\Blocks;

use App\Support\BlockDefinition;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class ButtonBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('button')
            ->label('Button')
            ->icon('heroicon-o-cursor-arrow-rays')
            ->schema(fn () => [
                TextInput::make('label')->label(fn () => __('Label')),
                TextInput::make('url')->label(fn () => __('URL'))->url(),
                Select::make('style')
                    ->label(fn () => __('Style'))
                    ->options(fn () => [
                        'primary' => __('Primary'),
                        'secondary' => __('Secondary'),
                        'outline' => __('Outline'),
                    ])
                    ->default('primary'),
            ]);
    }
}
