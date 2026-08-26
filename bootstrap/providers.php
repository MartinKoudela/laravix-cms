<?php

use App\Modules\ThemeEngine\ThemeServiceProvider;
use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    ThemeServiceProvider::class,
];
