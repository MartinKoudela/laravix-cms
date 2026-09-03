<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace Laravix\Cms;

use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Filament\Support\Assets\Js;
use Filament\Support\Assets\Theme;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravix\Cms\Blocks\ButtonBlock;
use Laravix\Cms\Blocks\ButtonGroupBlock;
use Laravix\Cms\Blocks\CardsBlock;
use Laravix\Cms\Blocks\ColumnsBlock;
use Laravix\Cms\Blocks\DividerBlock;
use Laravix\Cms\Blocks\Grapesjs\AccordionBlock;
use Laravix\Cms\Blocks\Grapesjs\AnimatedCounterBlock;
use Laravix\Cms\Blocks\Grapesjs\BadgeBlock;
use Laravix\Cms\Blocks\Grapesjs\BeforeAfterBlock;
use Laravix\Cms\Blocks\Grapesjs\BentoGridBlock;
use Laravix\Cms\Blocks\Grapesjs\BigTypographyBlock;
use Laravix\Cms\Blocks\Grapesjs\ButtonPrimaryBlock;
use Laravix\Cms\Blocks\Grapesjs\CardsSliderBlock;
use Laravix\Cms\Blocks\Grapesjs\ComparisonBlock;
use Laravix\Cms\Blocks\Grapesjs\CookieBannerBlock;
use Laravix\Cms\Blocks\Grapesjs\CountdownBlock;
use Laravix\Cms\Blocks\Grapesjs\CtaBlock;
use Laravix\Cms\Blocks\Grapesjs\FaqBlock;
use Laravix\Cms\Blocks\Grapesjs\FeatureListBlock;
use Laravix\Cms\Blocks\Grapesjs\GalleryBlock;
use Laravix\Cms\Blocks\Grapesjs\GallerySliderBlock;
use Laravix\Cms\Blocks\Grapesjs\GradientHeroBlock;
use Laravix\Cms\Blocks\Grapesjs\HeroImageBlock;
use Laravix\Cms\Blocks\Grapesjs\HtmlEmbedBlock;
use Laravix\Cms\Blocks\Grapesjs\IconBlock;
use Laravix\Cms\Blocks\Grapesjs\ImageCenteredBlock;
use Laravix\Cms\Blocks\Grapesjs\ImageFullBlock;
use Laravix\Cms\Blocks\Grapesjs\LinkTextBlock;
use Laravix\Cms\Blocks\Grapesjs\LogoBarBlock;
use Laravix\Cms\Blocks\Grapesjs\MapBlock;
use Laravix\Cms\Blocks\Grapesjs\MarqueeBlock;
use Laravix\Cms\Blocks\Grapesjs\NewsletterBlock;
use Laravix\Cms\Blocks\Grapesjs\PostListBlock;
use Laravix\Cms\Blocks\Grapesjs\PricingBlock;
use Laravix\Cms\Blocks\Grapesjs\ProgressBarsBlock;
use Laravix\Cms\Blocks\Grapesjs\QuoteBlock;
use Laravix\Cms\Blocks\Grapesjs\SpacerBlock;
use Laravix\Cms\Blocks\Grapesjs\SplitScreenBlock;
use Laravix\Cms\Blocks\Grapesjs\StatsBlock;
use Laravix\Cms\Blocks\Grapesjs\StepsBlock;
use Laravix\Cms\Blocks\Grapesjs\StickyCtaBarBlock;
use Laravix\Cms\Blocks\Grapesjs\TableBlock;
use Laravix\Cms\Blocks\Grapesjs\TabsBlock;
use Laravix\Cms\Blocks\Grapesjs\TeamBlock;
use Laravix\Cms\Blocks\Grapesjs\TestimonialsBlock;
use Laravix\Cms\Blocks\Grapesjs\TestimonialsSliderBlock;
use Laravix\Cms\Blocks\Grapesjs\TimelineBlock;
use Laravix\Cms\Blocks\Grapesjs\TwoColumnTextBlock;
use Laravix\Cms\Blocks\Grapesjs\VideoEmbedBlock;
use Laravix\Cms\Blocks\Grapesjs\VideoHeroBlock;
use Laravix\Cms\Blocks\Grapesjs\YoutubeBlock;
use Laravix\Cms\Blocks\HeroBlock;
use Laravix\Cms\Blocks\TextBlock;
use Laravix\Cms\Console\Commands\CreateUser;
use Laravix\Cms\Console\Commands\Docker;
use Laravix\Cms\Console\Commands\Install;
use Laravix\Cms\Console\Commands\LinkThemes;
use Laravix\Cms\Console\Commands\PublishScheduledContent;
use Laravix\Cms\Console\Commands\Upgrade;
use Laravix\Cms\Enums\FieldType;
use Laravix\Cms\Http\Middleware\AuthenticateApiToken;
use Laravix\Cms\Http\Middleware\HandleRedirects;
use Laravix\Cms\Http\Middleware\ResolveSiteForApi;
use Laravix\Cms\Livewire\BlockEditor;
use Laravix\Cms\Models\Content;
use Laravix\Cms\Models\ContentField;
use Laravix\Cms\Models\ContentRevision;
use Laravix\Cms\Models\ContentTypeField;
use Laravix\Cms\Models\CustomCodeBlock;
use Laravix\Cms\Models\Media;
use Laravix\Cms\Models\Redirect;
use Laravix\Cms\Models\Setting;
use Laravix\Cms\Models\Site;
use Laravix\Cms\Models\SiteApiToken;
use Laravix\Cms\Models\SiteUser;
use Laravix\Cms\Models\Taxonomy;
use Laravix\Cms\Models\User;
use Laravix\Cms\Models\UserInvitation;
use Laravix\Cms\Policies\ContentPolicy;
use Laravix\Cms\Policies\ContentTypeFieldPolicy;
use Laravix\Cms\Policies\CustomCodeBlockPolicy;
use Laravix\Cms\Policies\MediaPolicy;
use Laravix\Cms\Policies\TaxonomyPolicy;
use Laravix\Cms\Support\BlockRegistry;
use Laravix\Cms\Support\ContentTypeDefinition;
use Laravix\Cms\Support\ContentTypeRegistry;
use Laravix\Cms\Support\FieldDefinition;
use Laravix\Cms\Support\FieldRegistry;
use Laravix\Cms\Support\NavigationDefinition;
use Laravix\Cms\Support\NavigationRegistry;
use Laravix\Cms\Support\RouteRegistry;
use Laravix\Cms\Support\SettingDefinition;
use Laravix\Cms\Support\SettingRegistry;
use Laravix\Cms\Support\TaxonomyTypeRegistry;
use Laravix\Cms\Support\ThemeManifest;
use Livewire\Livewire;

class CmsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravix.php', 'laravix');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravix');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravix');

        $this->registerTaxonomyTypes();
        $this->registerContentTypes();
        $this->registerContentFields();
        $this->registerSettings();
        $this->registerBlocks();
        $this->registerNavigations();
    }

    public function boot(): void
    {
        Relation::enforceMorphMap([
            'content' => Content::class,
            'content_field' => ContentField::class,
            'content_revision' => ContentRevision::class,
            'content_type_field' => ContentTypeField::class,
            'custom_code_block' => CustomCodeBlock::class,
            'media' => Media::class,
            'redirect' => Redirect::class,
            'setting' => Setting::class,
            'site' => Site::class,
            'site_api_token' => SiteApiToken::class,
            'site_user' => SiteUser::class,
            'taxonomy' => Taxonomy::class,
            'user' => User::class,
            'user_invitation' => UserInvitation::class,
        ]);

        $this->registerMiddleware();
        $this->registerRoutes();
        $this->registerAssets();

        RateLimiter::for('api', function (Request $request) {
            $token = $request->attributes->get('apiToken');

            return Limit::perMinute(120)->by($token ? 'api-token:'.$token->id : $request->ip());
        });

        Factory::guessFactoryNamesUsing(
            fn (string $modelName): string => 'Laravix\\Cms\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        Factory::guessModelNamesUsing(
            fn (Factory $factory): string => 'Laravix\\Cms\\Models\\'.Str::replaceLast('Factory', '', class_basename($factory))
        );

        Gate::policy(Content::class, ContentPolicy::class);
        Gate::policy(ContentTypeField::class, ContentTypeFieldPolicy::class);
        Gate::policy(CustomCodeBlock::class, CustomCodeBlockPolicy::class);
        Gate::policy(Media::class, MediaPolicy::class);
        Gate::policy(Taxonomy::class, TaxonomyPolicy::class);

        Livewire::component('block-editor', BlockEditor::class);

        $baseThemeViews = base_path('themes/default/views');

        ThemeManifest::flush();

        foreach (ThemeManifest::all() as $theme) {
            $paths = [$theme->path('views')];

            if ($paths[0] !== $baseThemeViews && is_dir($baseThemeViews)) {
                $paths[] = $baseThemeViews;
            }

            View::replaceNamespace("themes.{$theme->key}", $paths);
        }

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['en', 'cs', 'sk'])
                ->flags([
                    'en' => 'https://flagcdn.com/gb.svg',
                    'cs' => 'https://flagcdn.com/cz.svg',
                    'sk' => 'https://flagcdn.com/sk.svg',
                ])
                ->circular()
                ->maxHeight('40rem')
                ->nativeLabel()
                ->visible(insidePanels: true, outsidePanels: true)
                ->userPreferredLocale(fn () => request()->getPreferredLanguage(['en', 'cs', 'sk']));
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateUser::class,
                Docker::class,
                Install::class,
                LinkThemes::class,
                PublishScheduledContent::class,
                Upgrade::class,
            ]);

            $this->callAfterResolving(Schedule::class, function (Schedule $schedule): void {
                $schedule->command('laravix:publish-scheduled')->everyMinute();
            });
        }
    }

    private function registerAssets(): void
    {
        FilamentAsset::register([
            Theme::make('laravix-admin', __DIR__.'/../dist/filament-theme.css'),
            Js::make('laravix-admin', __DIR__.'/../dist/filament-app.js'),
        ], 'laravix/cms');

        $this->publishes([
            __DIR__.'/../dist' => public_path('vendor/laravix'),
        ], 'laravix-assets');

        $this->publishes([
            __DIR__.'/../resources/themes/default' => base_path('themes/default'),
        ], 'laravix-theme');

        $this->publishes([
            __DIR__.'/../resources/views/overrides/tenant-menu.blade.php' => resource_path('views/vendor/filament-panels/components/tenant-menu.blade.php'),
        ], 'laravix-views');

        $this->publishes([
            __DIR__.'/../config/laravix.php' => config_path('laravix.php'),
        ], 'laravix-config');
    }

    private function registerMiddleware(): void
    {
        $router = $this->app['router'];

        $router->aliasMiddleware('api.site', ResolveSiteForApi::class);
        $router->aliasMiddleware('api.token', AuthenticateApiToken::class);
        $router->pushMiddlewareToGroup('web', HandleRedirects::class);
    }

    private function registerRoutes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware('web')->group(__DIR__.'/../routes/web.php');
        Route::middleware('api')->prefix('api')->group(__DIR__.'/../routes/api.php');

        $this->app->booted(function (): void {
            Route::middleware('web')->group(function (): void {
                RouteRegistry::apply();
            });

            Route::middleware('web')->group(__DIR__.'/../routes/cms.php');
        });
    }

    private function registerTaxonomyTypes(): void
    {
        TaxonomyTypeRegistry::register('category', 'laravix::taxonomy.types.category');
        TaxonomyTypeRegistry::register('tag', 'laravix::taxonomy.types.tag');
    }

    private function registerContentTypes(): void
    {
        ContentTypeRegistry::register(
            ContentTypeDefinition::make('page')
                ->label('laravix::content.types.page')
                ->pluralLabel('laravix::content.types_plural.page')
                ->linkableInNavigation(),
            ContentTypeDefinition::make('post')
                ->label('laravix::content.types.post')
                ->pluralLabel('laravix::content.types_plural.post'),
            ContentTypeDefinition::make('archive')
                ->label('laravix::content.types.archive')
                ->pluralLabel('laravix::content.types_plural.archive')
                ->linkableInNavigation(),
        );
    }

    private function registerContentFields(): void
    {
        FieldRegistry::content([
            FieldDefinition::make('meta_title')
                ->label('laravix::content.fields.meta_title')
                ->group('laravix::content.sections.seo_group')
                ->hint('laravix::content.hints.meta_title'),
            FieldDefinition::make('meta_description')
                ->type(FieldType::TEXTAREA)
                ->label('laravix::content.fields.meta_description')
                ->group('laravix::content.sections.seo_group')
                ->hint('laravix::content.hints.meta_description'),
            FieldDefinition::make('og_image')
                ->type(FieldType::IMAGE)
                ->label('laravix::content.fields.og_image')
                ->group('laravix::content.sections.seo_group')
                ->hint('laravix::content.hints.og_image'),
            FieldDefinition::make('noindex')
                ->type(FieldType::BOOLEAN)
                ->label('laravix::content.fields.noindex')
                ->group('laravix::content.sections.seo_group'),
        ]);
    }

    private function registerSettings(): void
    {
        SettingRegistry::register([
            SettingDefinition::make('site_name')
                ->label('laravix::settings.fields.site_name')
                ->group('laravix::settings.tabs.general')
                ->required(),
            SettingDefinition::make('site_description')
                ->type(FieldType::TEXTAREA)
                ->label('laravix::settings.fields.site_description')
                ->group('laravix::settings.tabs.general'),
            SettingDefinition::make('logo')
                ->type(FieldType::IMAGE)
                ->label('laravix::settings.fields.site_logo')
                ->group('laravix::settings.tabs.general')
                ->hint('laravix::settings.hints.logo'),
            SettingDefinition::make('favicon')
                ->type(FieldType::IMAGE)
                ->label('laravix::settings.fields.favicon')
                ->group('laravix::settings.tabs.general')
                ->hint('laravix::settings.hints.favicon'),
            SettingDefinition::make('locale')
                ->label('laravix::settings.fields.locale')
                ->group('laravix::settings.tabs.general')
                ->default('en')
                ->hint('laravix::settings.hints.locale'),
            SettingDefinition::make('contact_email')
                ->label('laravix::settings.fields.contact_email')
                ->group('laravix::settings.tabs.general')
                ->hint('laravix::settings.hints.contact_email')
                ->config(['email' => true]),

            SettingDefinition::make('meta_title')
                ->label('laravix::settings.fields.meta_title')
                ->group('laravix::settings.tabs.seo')
                ->hint('laravix::settings.hints.meta_title'),
            SettingDefinition::make('meta_description')
                ->type(FieldType::TEXTAREA)
                ->label('laravix::settings.fields.meta_description')
                ->group('laravix::settings.tabs.seo')
                ->hint('laravix::settings.hints.meta_description')
                ->config(['maxLength' => 160]),
            SettingDefinition::make('og_image')
                ->type(FieldType::IMAGE)
                ->label('laravix::settings.fields.og_image')
                ->group('laravix::settings.tabs.seo')
                ->hint('laravix::settings.hints.og_image'),
            SettingDefinition::make('google_site_verification')
                ->label('laravix::settings.fields.google_verification')
                ->group('laravix::settings.tabs.seo')
                ->hint('laravix::settings.hints.google_verification'),
            SettingDefinition::make('robots_txt')
                ->type(FieldType::TEXTAREA)
                ->label('laravix::settings.fields.robots_txt')
                ->group('laravix::settings.tabs.seo')
                ->hint('laravix::settings.hints.robots_txt')
                ->config(['placeholder' => "User-agent: *\nAllow: /\n\nUser-agent: GPTBot\nDisallow: /"]),
            SettingDefinition::make('twitter_url')
                ->type(FieldType::URL)
                ->label('laravix::settings.fields.twitter')
                ->group('laravix::settings.tabs.social')
                ->config(['prefixIcon' => 'fa-twitter-x']),
            SettingDefinition::make('linkedin_url')
                ->type(FieldType::URL)
                ->label('laravix::settings.fields.linkedin')
                ->group('laravix::settings.tabs.social')
                ->config(['prefixIcon' => 'fa-linkedin']),
            SettingDefinition::make('facebook_url')
                ->type(FieldType::URL)
                ->label('laravix::settings.fields.facebook')
                ->group('laravix::settings.tabs.social')
                ->config(['prefixIcon' => 'fa-facebook']),
            SettingDefinition::make('instagram_url')
                ->type(FieldType::URL)
                ->label('laravix::settings.fields.instagram')
                ->group('laravix::settings.tabs.social')
                ->config(['prefixIcon' => 'fa-instagram']),
            SettingDefinition::make('tiktok_url')
                ->type(FieldType::URL)
                ->label('laravix::settings.fields.tiktok')
                ->group('laravix::settings.tabs.social')
                ->config(['prefixIcon' => 'fa-tiktok']),
            SettingDefinition::make('github_url')
                ->type(FieldType::URL)
                ->label('laravix::settings.fields.github')
                ->group('laravix::settings.tabs.social')
                ->config(['prefixIcon' => 'fa-github']),
            SettingDefinition::make('youtube_url')
                ->type(FieldType::URL)
                ->label('laravix::settings.fields.youtube')
                ->group('laravix::settings.tabs.social')
                ->config(['prefixIcon' => 'fa-youtube']),
            SettingDefinition::make('discord_url')
                ->type(FieldType::URL)
                ->label('laravix::settings.fields.discord')
                ->group('laravix::settings.tabs.social')
                ->config(['prefixIcon' => 'fa-discord']),
            SettingDefinition::make('telegram_url')
                ->type(FieldType::URL)
                ->label('laravix::settings.fields.telegram')
                ->group('laravix::settings.tabs.social')
                ->config(['prefixIcon' => 'fa-telegram']),
            SettingDefinition::make('whatsapp_url')
                ->type(FieldType::URL)
                ->label('laravix::settings.fields.whatsapp')
                ->group('laravix::settings.tabs.social')
                ->config(['prefixIcon' => 'fa-whatsapp']),
            SettingDefinition::make('pinterest_url')
                ->type(FieldType::URL)
                ->label('laravix::settings.fields.pinterest')
                ->group('laravix::settings.tabs.social')
                ->config(['prefixIcon' => 'fa-pinterest']),
            SettingDefinition::make('reddit_url')
                ->type(FieldType::URL)
                ->label('laravix::settings.fields.reddit')
                ->group('laravix::settings.tabs.social')
                ->config(['prefixIcon' => 'fa-reddit']),
            SettingDefinition::make('twitch_url')
                ->type(FieldType::URL)
                ->label('laravix::settings.fields.twitch')
                ->group('laravix::settings.tabs.social')
                ->config(['prefixIcon' => 'fa-twitch']),
            SettingDefinition::make('snapchat_url')
                ->type(FieldType::URL)
                ->label('laravix::settings.fields.snapchat')
                ->group('laravix::settings.tabs.social')
                ->config(['prefixIcon' => 'fa-snapchat']),
            SettingDefinition::make('spotify_url')
                ->type(FieldType::URL)
                ->label('laravix::settings.fields.spotify')
                ->group('laravix::settings.tabs.social')
                ->config(['prefixIcon' => 'fa-spotify']),
        ]);
    }

    private function registerBlocks(): void
    {
        BlockRegistry::register(
            TextBlock::definition(),
            HeroBlock::definition(),
            CardsBlock::definition(),
            ColumnsBlock::definition(),
            ButtonBlock::definition(),
            ButtonGroupBlock::definition(),
            DividerBlock::definition(),
            HeroImageBlock::definition(),
            VideoHeroBlock::definition(),
            StepsBlock::definition(),
            StatsBlock::definition(),
            GalleryBlock::definition(),
            GallerySliderBlock::definition(),
            CardsSliderBlock::definition(),
            YoutubeBlock::definition(),
            VideoEmbedBlock::definition(),
            MapBlock::definition(),
            TestimonialsBlock::definition(),
            TestimonialsSliderBlock::definition(),
            LogoBarBlock::definition(),
            TeamBlock::definition(),
            CtaBlock::definition(),
            PricingBlock::definition(),
            NewsletterBlock::definition(),
            FaqBlock::definition(),
            AccordionBlock::definition(),
            ButtonPrimaryBlock::definition(),
            ImageFullBlock::definition(),
            ImageCenteredBlock::definition(),
            IconBlock::definition(),
            BadgeBlock::definition(),
            LinkTextBlock::definition(),
            SpacerBlock::definition(),
            TableBlock::definition(),
            HtmlEmbedBlock::definition(),
            MarqueeBlock::definition(),
            BentoGridBlock::definition(),
            SplitScreenBlock::definition(),
            BigTypographyBlock::definition(),
            GradientHeroBlock::definition(),
            QuoteBlock::definition(),
            TimelineBlock::definition(),
            FeatureListBlock::definition(),
            TwoColumnTextBlock::definition(),
            ComparisonBlock::definition(),
            TabsBlock::definition(),
            CountdownBlock::definition(),
            AnimatedCounterBlock::definition(),
            BeforeAfterBlock::definition(),
            ProgressBarsBlock::definition(),
            CookieBannerBlock::definition(),
            StickyCtaBarBlock::definition(),
            PostListBlock::definition(),
        );
    }

    private function registerNavigations(): void
    {
        NavigationRegistry::register(
            NavigationDefinition::make('header')->label('laravix::navigation.labels.header'),
            NavigationDefinition::make('footer')->label('laravix::navigation.labels.footer'),
        );
    }
}
