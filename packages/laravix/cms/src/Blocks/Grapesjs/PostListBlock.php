<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class PostListBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('post-list')
            ->label('blocks.gjs.post_list')
            ->icon('fa-newspaper')
            ->category('blocks.categories.content')
            ->contentTypes(['archive'])
            ->canvasHtml(<<<'HTML'
<section class="lx-section lx-section--light">
    <div class="lx-container">
        <div class="lx-post-list__grid" data-lx-post-list>
            <a href="#" class="lx-post-list__card">
                <div class="lx-post-list__image" style="background-image:url(https://placehold.co/600x400?text=Post+1)"></div>
                <div class="lx-post-list__body">
                    <h3 class="lx-post-list__title">Sample post title</h3>
                    <p class="lx-post-list__meta">1. 1. 2026</p>
                </div>
            </a>
            <a href="#" class="lx-post-list__card">
                <div class="lx-post-list__image" style="background-image:url(https://placehold.co/600x400?text=Post+2)"></div>
                <div class="lx-post-list__body">
                    <h3 class="lx-post-list__title">Another post title</h3>
                    <p class="lx-post-list__meta">2. 1. 2026</p>
                </div>
            </a>
            <a href="#" class="lx-post-list__card">
                <div class="lx-post-list__image" style="background-image:url(https://placehold.co/600x400?text=Post+3)"></div>
                <div class="lx-post-list__body">
                    <h3 class="lx-post-list__title">Third post title</h3>
                    <p class="lx-post-list__meta">3. 1. 2026</p>
                </div>
            </a>
        </div>
    </div>
</section>
HTML);
    }
}
