<?php

/**
 * Laravix CMS — Copyright (C) 2026 Martin Koudela (laravix.com)
 * Licensed under GPL-3.0-or-later. See LICENSE for details.
 */

namespace App\Providers;

use App\Blocks\ButtonBlock;
use App\Blocks\ButtonGroupBlock;
use App\Blocks\CardsBlock;
use App\Blocks\ColumnsBlock;
use App\Blocks\DividerBlock;
use App\Blocks\Grapesjs\AccordionBlock;
use App\Blocks\Grapesjs\AnimatedCounterBlock;
use App\Blocks\Grapesjs\BadgeBlock;
use App\Blocks\Grapesjs\BeforeAfterBlock;
use App\Blocks\Grapesjs\BentoGridBlock;
use App\Blocks\Grapesjs\BigTypographyBlock;
use App\Blocks\Grapesjs\ButtonPrimaryBlock;
use App\Blocks\Grapesjs\CardsSliderBlock;
use App\Blocks\Grapesjs\ComparisonBlock;
use App\Blocks\Grapesjs\ContactFormBlock;
use App\Blocks\Grapesjs\CookieBannerBlock;
use App\Blocks\Grapesjs\CountdownBlock;
use App\Blocks\Grapesjs\CtaBlock;
use App\Blocks\Grapesjs\FaqBlock;
use App\Blocks\Grapesjs\FeatureListBlock;
use App\Blocks\Grapesjs\GalleryBlock;
use App\Blocks\Grapesjs\GallerySliderBlock;
use App\Blocks\Grapesjs\GradientHeroBlock;
use App\Blocks\Grapesjs\HeroImageBlock;
use App\Blocks\Grapesjs\HtmlEmbedBlock;
use App\Blocks\Grapesjs\IconBlock;
use App\Blocks\Grapesjs\ImageCenteredBlock;
use App\Blocks\Grapesjs\ImageFullBlock;
use App\Blocks\Grapesjs\LinkTextBlock;
use App\Blocks\Grapesjs\LogoBarBlock;
use App\Blocks\Grapesjs\MapBlock;
use App\Blocks\Grapesjs\MarqueeBlock;
use App\Blocks\Grapesjs\NewsletterBlock;
use App\Blocks\Grapesjs\PostListBlock;
use App\Blocks\Grapesjs\PricingBlock;
use App\Blocks\Grapesjs\ProgressBarsBlock;
use App\Blocks\Grapesjs\QuoteBlock;
use App\Blocks\Grapesjs\SpacerBlock;
use App\Blocks\Grapesjs\SplitScreenBlock;
use App\Blocks\Grapesjs\StatsBlock;
use App\Blocks\Grapesjs\StepsBlock;
use App\Blocks\Grapesjs\StickyCtaBarBlock;
use App\Blocks\Grapesjs\TableBlock;
use App\Blocks\Grapesjs\TabsBlock;
use App\Blocks\Grapesjs\TeamBlock;
use App\Blocks\Grapesjs\TestimonialsBlock;
use App\Blocks\Grapesjs\TestimonialsSliderBlock;
use App\Blocks\Grapesjs\TimelineBlock;
use App\Blocks\Grapesjs\TwoColumnTextBlock;
use App\Blocks\Grapesjs\VideoEmbedBlock;
use App\Blocks\Grapesjs\VideoHeroBlock;
use App\Blocks\Grapesjs\YoutubeBlock;
use App\Blocks\HeroBlock;
use App\Blocks\TextBlock;
use App\Enums\FieldType;
use App\Support\BlockRegistry;
use App\Support\ContentTypeDefinition;
use App\Support\ContentTypeRegistry;
use App\Support\FieldDefinition;
use App\Support\FieldRegistry;
use App\Support\NavigationDefinition;
use App\Support\NavigationRegistry;
use App\Support\SettingDefinition;
use App\Support\SettingRegistry;
use App\Support\TaxonomyTypeRegistry;
use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        TaxonomyTypeRegistry::register('category', 'taxonomy.types.category');
        TaxonomyTypeRegistry::register('tag', 'taxonomy.types.tag');

        ContentTypeRegistry::register(
            ContentTypeDefinition::make('page')
                ->label('content.types.page')
                ->pluralLabel('content.types_plural.page')
                ->linkableInNavigation(),
            ContentTypeDefinition::make('post')
                ->label('content.types.post')
                ->pluralLabel('content.types_plural.post'),
            ContentTypeDefinition::make('archive')
                ->label('content.types.archive')
                ->pluralLabel('content.types_plural.archive')
                ->linkableInNavigation(),
        );

        FieldRegistry::content([
            FieldDefinition::make('meta_title')
                ->label('content.fields.meta_title')
                ->group('content.sections.seo_group')
                ->hint('content.hints.meta_title'),
            FieldDefinition::make('meta_description')
                ->type(FieldType::TEXTAREA)
                ->label('content.fields.meta_description')
                ->group('content.sections.seo_group')
                ->hint('content.hints.meta_description'),
            FieldDefinition::make('og_image')
                ->type(FieldType::IMAGE)
                ->label('content.fields.og_image')
                ->group('content.sections.seo_group')
                ->hint('content.hints.og_image'),
            FieldDefinition::make('noindex')
                ->type(FieldType::BOOLEAN)
                ->label('content.fields.noindex')
                ->group('content.sections.seo_group'),
        ]);

        SettingRegistry::register([
            SettingDefinition::make('site_name')
                ->label('settings.fields.site_name')
                ->group('settings.tabs.general')
                ->required(),
            SettingDefinition::make('site_description')
                ->type(FieldType::TEXTAREA)
                ->label('settings.fields.site_description')
                ->group('settings.tabs.general'),
            SettingDefinition::make('logo')
                ->type(FieldType::IMAGE)
                ->label('settings.fields.site_logo')
                ->group('settings.tabs.general')
                ->hint('settings.hints.logo'),
            SettingDefinition::make('favicon')
                ->type(FieldType::IMAGE)
                ->label('settings.fields.favicon')
                ->group('settings.tabs.general')
                ->hint('settings.hints.favicon'),
            SettingDefinition::make('locale')
                ->label('settings.fields.locale')
                ->group('settings.tabs.general')
                ->default('en')
                ->hint('settings.hints.locale'),
            SettingDefinition::make('contact_email')
                ->label('settings.fields.contact_email')
                ->group('settings.tabs.general')
                ->hint('settings.hints.contact_email')
                ->config(['email' => true]),

            SettingDefinition::make('meta_title')
                ->label('settings.fields.meta_title')
                ->group('settings.tabs.seo')
                ->hint('settings.hints.meta_title'),
            SettingDefinition::make('meta_description')
                ->type(FieldType::TEXTAREA)
                ->label('settings.fields.meta_description')
                ->group('settings.tabs.seo')
                ->hint('settings.hints.meta_description')
                ->config(['maxLength' => 160]),
            SettingDefinition::make('og_image')
                ->type(FieldType::IMAGE)
                ->label('settings.fields.og_image')
                ->group('settings.tabs.seo')
                ->hint('settings.hints.og_image'),
            SettingDefinition::make('google_site_verification')
                ->label('settings.fields.google_verification')
                ->group('settings.tabs.seo')
                ->hint('settings.hints.google_verification'),
            SettingDefinition::make('robots_txt')
                ->type(FieldType::TEXTAREA)
                ->label('settings.fields.robots_txt')
                ->group('settings.tabs.seo')
                ->hint('settings.hints.robots_txt')
                ->config(['placeholder' => "User-agent: *\nAllow: /\n\nUser-agent: GPTBot\nDisallow: /"]),
            SettingDefinition::make('twitter_url')
                ->type(FieldType::URL)
                ->label('settings.fields.twitter')
                ->group('settings.tabs.social'),
            SettingDefinition::make('linkedin_url')
                ->type(FieldType::URL)
                ->label('settings.fields.linkedin')
                ->group('settings.tabs.social'),
            SettingDefinition::make('facebook_url')
                ->type(FieldType::URL)
                ->label('settings.fields.facebook')
                ->group('settings.tabs.social'),
            SettingDefinition::make('instagram_url')
                ->type(FieldType::URL)
                ->label('settings.fields.instagram')
                ->group('settings.tabs.social'),
            SettingDefinition::make('github_url')
                ->type(FieldType::URL)
                ->label('settings.fields.github')
                ->group('settings.tabs.social'),
        ]);

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
            ContactFormBlock::definition(),
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

        NavigationRegistry::register(
            NavigationDefinition::make('header')->label('navigation.labels.header'),
            NavigationDefinition::make('footer')->label('navigation.labels.footer'),
        );
    }

    public function boot(): void
    {
        RateLimiter::for('api', fn (Request $request) => Limit::perMinute(120)->by($request->ip()));

        foreach (glob(base_path('themes/*'), GLOB_ONLYDIR) as $themePath) {
            $themeName = basename($themePath);
            View::addNamespace("themes.{$themeName}", "{$themePath}/views");
        }

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['en', 'cs', 'sk'])
                // ->locales(['en', 'cs', 'sk', 'de', 'fr', 'es', 'it', 'pl', 'pt_BR', 'uk', 'nl', 'hu', 'ro', 'sv', 'tr', 'ja', 'zh_CN', 'ar', 'ru', 'ko', 'hi'])
                ->flags([
                    'en' => 'https://flagcdn.com/gb.svg',
                    'cs' => 'https://flagcdn.com/cz.svg',
                    'sk' => 'https://flagcdn.com/sk.svg',
                    // 'de' => 'https://flagcdn.com/de.svg',
                    // 'fr' => 'https://flagcdn.com/fr.svg',
                    // 'es' => 'https://flagcdn.com/es.svg',
                    // 'it' => 'https://flagcdn.com/it.svg',
                    // 'pl' => 'https://flagcdn.com/pl.svg',
                    // 'pt_BR' => 'https://flagcdn.com/br.svg',
                    // 'uk' => 'https://flagcdn.com/ua.svg',
                    // 'nl' => 'https://flagcdn.com/nl.svg',
                    // 'hu' => 'https://flagcdn.com/hu.svg',
                    // 'ro' => 'https://flagcdn.com/ro.svg',
                    // 'sv' => 'https://flagcdn.com/se.svg',
                    // 'tr' => 'https://flagcdn.com/tr.svg',
                    // 'ja' => 'https://flagcdn.com/jp.svg',
                    // 'zh_CN' => 'https://flagcdn.com/cn.svg',
                    // 'ar' => 'https://flagcdn.com/sa.svg',
                    // 'ru' => 'https://flagcdn.com/ru.svg',
                    // 'ko' => 'https://flagcdn.com/kr.svg',
                    // 'hi' => 'https://flagcdn.com/in.svg',
                ])
                ->circular()
                ->maxHeight('40rem')
                ->nativeLabel()
                ->visible(insidePanels: true, outsidePanels: true)
                ->userPreferredLocale(fn () => request()->getPreferredLanguage(['en', 'cs', 'sk', 'de', 'fr', 'es', 'it', 'pl', 'pt_BR', 'uk', 'nl', 'hu', 'ro', 'sv', 'tr', 'ja', 'zh_CN', 'ar', 'ru', 'ko', 'hi']));
        });
    }
}
