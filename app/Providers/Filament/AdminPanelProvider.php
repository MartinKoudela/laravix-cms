<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Providers\Filament;

use Filament\Panel;
use Laravix\Cms\Filament\BaseAdminPanelProvider;

class AdminPanelProvider extends BaseAdminPanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return parent::panel($panel);
    }
}
