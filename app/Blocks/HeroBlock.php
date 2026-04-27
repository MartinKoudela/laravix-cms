<?php

namespace App\Blocks;

use App\Support\BlockDefinition;
use App\Support\FieldComponentFactory;
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
                TextInput::make('button_label')->label(fn () => __('Button Label')),
                TextInput::make('button_url')->label(fn () => __('Button URL'))->url(),
            ]);
    }
}
