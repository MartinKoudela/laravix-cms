<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

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
            ->gjsIcon('fa-image')
            ->category('blocks.categories.hero')
            ->canvasHtml(<<<'HTML'
<section style="padding:80px 24px;text-align:center;background:#f9fafb;">
    <div style="max-width:720px;margin:0 auto;">
        <h1 style="font-size:3rem;font-weight:800;color:#111827;margin:0 0 16px;line-height:1.15;">Main page heading</h1>
        <p style="font-size:1.25rem;color:#6b7280;margin:0 0 36px;line-height:1.7;">Short description or call to action for visitors.</p>
        <a href="#" data-gjs-type="button-link" style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;font-size:.9375rem;font-weight:600;border-radius:8px;text-decoration:none;background:#111827;color:#fff;">Get started</a>
    </div>
</section>
HTML)
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
