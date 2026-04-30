<?php

namespace App\Blocks;

use App\Support\BlockDefinition;
use App\Support\FieldComponentFactory;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class HeroBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('hero')
            ->label('Hero')
            ->icon('heroicon-o-photo')
            ->schema(fn () => [
                TextInput::make('heading')->label(fn () => __('Heading'))->columnSpanFull(),
                Textarea::make('subheading')->label(fn () => __('Subheading'))->columnSpanFull(),
                FieldComponentFactory::mediaSelect('image_id', __('Image')),
                Repeater::make('buttons')
                    ->label(fn () => __('Buttons'))
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('label')->label(fn () => __('Label')),
                        TextInput::make('href')->label(fn () => __('URL')),
                        Select::make('variant')
                            ->label(fn () => __('Variant'))
                            ->options(fn () => [
                                'primary' => __('Primary'),
                                'secondary' => __('Secondary'),
                                'outline' => __('Outline'),
                            ]),
                    ]),
            ]);
    }
}
