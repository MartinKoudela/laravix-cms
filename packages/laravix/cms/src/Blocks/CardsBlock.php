<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks;

use Laravix\Cms\Support\BlockDefinition;
use Laravix\Cms\Support\BlockRegistry;
use Laravix\Cms\Support\FieldComponentFactory;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

class CardsBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('cards')
            ->label('blocks.types.cards')
            ->icon('heroicon-o-squares-2x2')
            ->gjsIcon('fa-grip')
            ->category('blocks.categories.content')
            ->canvasHtml(<<<'HTML'
<section style="padding:64px 24px;background:#f9fafb;">
    <div style="max-width:1100px;margin:0 auto;">
        <h2 style="font-size:2rem;font-weight:700;color:#111827;text-align:center;margin:0 0 40px;">What we offer</h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px;">
            <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);"><div style="width:48px;height:48px;background:#eff6ff;border-radius:10px;margin:0 0 16px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-bolt" style="color:#2563eb;font-size:1.25rem;"></i></div><h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0 0 8px;">Feature 1</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">Feature description.</p></div>
            <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);"><div style="width:48px;height:48px;background:#f0fdf4;border-radius:10px;margin:0 0 16px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-shield-halved" style="color:#16a34a;font-size:1.25rem;"></i></div><h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0 0 8px;">Feature 2</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">Feature description.</p></div>
            <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 1px 4px rgba(0,0,0,.06);"><div style="width:48px;height:48px;background:#fdf4ff;border-radius:10px;margin:0 0 16px;display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-star" style="color:#9333ea;font-size:1.25rem;"></i></div><h3 style="font-size:1.125rem;font-weight:600;color:#111827;margin:0 0 8px;">Feature 3</h3><p style="font-size:.9375rem;color:#6b7280;margin:0;line-height:1.6;">Feature description.</p></div>
        </div>
    </div>
</section>
HTML)
            ->nestable(false)
            ->schema(fn () => [
                TextInput::make('heading')->label(fn () => __('common.heading'))->columnSpanFull(),
                Repeater::make('items')
                    ->label(fn () => __('blocks.fields.cards'))
                    ->schema([
                        TextInput::make('title')->label(fn () => __('common.title')),
                        FieldComponentFactory::mediaSelect('image_id', __('common.image')),
                        TextInput::make('link')->label(fn () => __('common.link'))->url(),
                        Builder::make('blocks')
                            ->blocks(BlockRegistry::toNestableBlocks())
                            ->collapsible()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
