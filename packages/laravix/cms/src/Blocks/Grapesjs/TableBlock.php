<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Blocks\Grapesjs;

use Laravix\Cms\Support\BlockDefinition;

class TableBlock
{
    public static function definition(): BlockDefinition
    {
        return BlockDefinition::make('table')
            ->label('laravix::blocks.gjs.table')
            ->icon('fa-table')
            ->category('laravix::blocks.categories.elements')
            ->canvasHtml(<<<'HTML'
<div class="lx-table">
    <table class="lx-table__el">
        <thead class="lx-table__thead">
            <tr>
                <th class="lx-table__th">Column 1</th>
                <th class="lx-table__th">Column 2</th>
                <th class="lx-table__th">Column 3</th>
            </tr>
        </thead>
        <tbody class="lx-table__tbody">
            <tr><td class="lx-table__td">Row 1</td><td class="lx-table__td">Data</td><td class="lx-table__td">Data</td></tr>
            <tr><td class="lx-table__td">Row 2</td><td class="lx-table__td">Data</td><td class="lx-table__td">Data</td></tr>
            <tr><td class="lx-table__td">Row 3</td><td class="lx-table__td">Data</td><td class="lx-table__td">Data</td></tr>
        </tbody>
    </table>
</div>
HTML);
    }
}
