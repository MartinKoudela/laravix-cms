<?php

namespace App\Blocks;

use App\Support\BlockDefinition;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class ButtonGroupBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('button_group')
            ->label('Button Group')
            ->icon('heroicon-o-cursor-arrow-rays')
            ->schema(fn () => [
                Repeater::make('buttons')
                    ->label(fn () => __('Buttons'))
                    ->schema([
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
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
