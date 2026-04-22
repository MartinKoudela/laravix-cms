<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Tenancy\RegisterSite;
use App\Models\Site;
use Devonab\FilamentEasyFooter\EasyFooterPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login()
            ->brandName('Laravix CMS')
            ->brandLogo(asset('laravix-logo-black.svg'))
            ->favicon(asset('favicon.ico'))
            ->darkModeBrandLogo(asset('laravix-logo-white.svg'))
            ->brandLogoHeight('3rem')
            ->colors([
                'primary' => Color::hex('#ff0465'),
                'gray' => Color::Zinc,
            ])
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
                'Content',
                'Management',
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
                EasyFooterPlugin::make()
                    ->withSentence(new HtmlString('<img src="/favicon.ico" style="" alt="Laravel Logo" width="20" height="20">Laravix'))
                    ->withGithub(showLogo: true, showUrl: true),
                //                    ->withLinks([
                //                        ['title' => 'Website', 'url' => 'https://laravix.com'],
                //                        ['title' => 'Privacy Policy', 'url' => 'https://laravix.com/privacy-policy']
                //                    ]),
            ]);
    }
}
