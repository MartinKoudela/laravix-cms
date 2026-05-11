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
            ->label('blocks.types.hero')
            ->icon('heroicon-o-photo')
            ->schema(fn () => [
                TextInput::make('heading')->label(fn () => __('common.heading'))->columnSpanFull(),
                Textarea::make('subheading')->label(fn () => __('blocks.fields.subheading'))->columnSpanFull(),
                FieldComponentFactory::mediaSelect('image_id', __('common.image')),
                Repeater::make('buttons')
                    ->label(fn () => __('blocks.fields.buttons'))
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('label')->label(fn () => __('common.label')),
                        TextInput::make('href')->label(fn () => __('common.url')),
                        Select::make('variant')
                            ->label(fn () => __('blocks.fields.variant'))
                            ->options(fn () => [
                                'primary' => __('blocks.styles.primary'),
                                'secondary' => __('blocks.styles.secondary'),
                                'outline' => __('blocks.styles.outline'),
                            ]),
                    ]),
            ]);
    }
}
