<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms\Filament\Actions;

use Filament\Actions\Action;

/**
 * Turns record actions into the icon-only bar that slides in when a table row is hovered.
 * The reveal itself is handled by the `.fi-ta-row .fi-ta-actions` rules in the admin theme.
 */
class HoverActions
{
    /**
     * @param  array<Action>  $actions
     * @return array<Action>
     */
    public static function wrap(array $actions): array
    {
        return array_map(
            fn (Action $action): Action => $action
                ->iconButton()
                ->tooltip(fn (Action $action): string => $action->getLabel()),
            $actions,
        );
    }
}
