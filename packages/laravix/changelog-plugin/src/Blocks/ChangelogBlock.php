<?php

/**
 * Laravix Changelog Plugin — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Changelog\Blocks;

use App\Support\BlockDefinition;

class ChangelogBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('changelog')
            ->label('changelog::changelog.block_label')
            ->icon('fa-clock-rotate-left')
            ->category('blocks.categories.content')
            ->canvasHtml(<<<'HTML'
<section class="lx-section lx-section--light">
    <div class="lx-container">
        <div class="lx-changelog" data-lx-changelog>
            <div class="lx-changelog__release">
                <div class="lx-changelog__header">
                    <span class="lx-changelog__version">v1.2.0</span>
                    <span class="lx-changelog__date">1. 6. 2026</span>
                </div>
                <ul class="lx-changelog__items">
                    <li><span class="lx-changelog__type lx-changelog__type--added">Added</span> Sample new feature</li>
                    <li><span class="lx-changelog__type lx-changelog__type--fixed">Fixed</span> Sample bug fix</li>
                </ul>
            </div>
            <div class="lx-changelog__release">
                <div class="lx-changelog__header">
                    <span class="lx-changelog__version">v1.1.0</span>
                    <span class="lx-changelog__date">1. 5. 2026</span>
                </div>
                <ul class="lx-changelog__items">
                    <li><span class="lx-changelog__type lx-changelog__type--changed">Changed</span> Sample improvement</li>
                </ul>
            </div>
        </div>
    </div>
</section>
HTML);
    }
}
