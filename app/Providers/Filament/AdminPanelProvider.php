<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Providers\Filament;

use App\Filament\Pages\EditProfile;
use App\Filament\Pages\Tenancy\RegisterSite;
use App\Models\Site;
use App\Support\FilamentPluginRegistry;
use Devonab\FilamentEasyFooter\EasyFooterPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Vite;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Promethys\Revive\RevivePlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->renderHook('panels::head.end', fn () => new HtmlString('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">'))
            ->renderHook('panels::body.end', fn () => new HtmlString(app(Vite::class)('resources/js/filament/admin/app.js')))
            ->renderHook('panels::topbar.end', fn () => view('filament.partials.fast-actions'))
            ->login()
            ->brandName('Laravix CMS')
            ->brandLogo(asset('laravix-logo-black.svg'))
            ->favicon(asset('favicon.ico'))
            ->darkModeBrandLogo(asset('laravix-logo-white.svg'))
            ->brandLogoHeight('3rem')
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => Color::hex('#ff0465'),
                'gray' => Color::Zinc,
            ])
            ->profile(EditProfile::class)
            ->tenant(Site::class, slugAttribute: 'id')
            ->tenantRegistration(RegisterSite::class)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->navigationGroups([
                'Content' => NavigationGroup::make(fn () => __('panel.groups.content')),
                'Management' => NavigationGroup::make(fn () => __('panel.groups.management')),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                RevivePlugin::make()
                    ->authorize(fn () => auth()->user()?->isAdmin())
                    ->navigationGroup(__('panel.groups.management'))
                    ->navigationIcon('heroicon-o-archive-box-arrow-down')
                    ->navigationSort(1)
                    ->navigationLabel(fn () => __('panel.bin'))
                    ->title(fn () => __('panel.bin')),

                EasyFooterPlugin::make()
                    ->withSentence(new HtmlString(
                        '<img src="/favicon.ico" style="" alt="Laravel Logo" width="20" height="20">Laravix v'.config('app.version')
                    ))
                    ->withLinks([
                        ['title' => 'panel.footer.website', 'url' => 'https://laravix.com'],
                        ['title' => 'panel.footer.docs', 'url' => 'https://laravix.com/docs'],
                        ['title' => 'panel.footer.contact', 'url' => 'https://laravix.com/contact'],
                    ]),

                ...FilamentPluginRegistry::all(),
            ]);
    }
}
