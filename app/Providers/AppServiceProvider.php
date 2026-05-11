<?php

namespace App\Providers;

use App\Blocks\ButtonBlock;
use App\Blocks\ButtonGroupBlock;
use App\Blocks\CardsBlock;
use App\Blocks\ColumnsBlock;
use App\Blocks\DividerBlock;
use App\Blocks\HeroBlock;
use App\Blocks\TextBlock;
use App\Enums\FieldType;
use App\Support\AppearanceDefinition;
use App\Support\AppearanceRegistry;
use App\Support\BlockRegistry;
use App\Support\FieldDefinition;
use App\Support\FieldRegistry;
use App\Support\NavigationDefinition;
use App\Support\NavigationRegistry;
use App\Support\SettingDefinition;
use App\Support\SettingRegistry;
use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {

        FieldRegistry::content([
            FieldDefinition::make('body')->type(FieldType::RICH_TEXT)->label('Body'),
            FieldDefinition::make('hero_image')->type(FieldType::IMAGE)->label('Hero Image'),
            FieldDefinition::make('excerpt')->type(FieldType::TEXTAREA)->label('Excerpt'),

            FieldDefinition::make('meta_title')
                ->label('Meta Title')
                ->group('SEO')
                ->hint('Overrides the site-wide default when set.'),
            FieldDefinition::make('meta_description')
                ->type(FieldType::TEXTAREA)
                ->label('Meta Description')
                ->group('SEO')
                ->hint('Up to 160 characters. Overrides the site-wide default.'),
            FieldDefinition::make('og_image')
                ->type(FieldType::IMAGE)
                ->label('OG Image')
                ->group('SEO')
                ->hint('Overrides the site-wide OG image for this page.'),
            FieldDefinition::make('noindex')
                ->type(FieldType::BOOLEAN)
                ->label('Hide from search engines')
                ->group('SEO'),
        ]);
        AppearanceRegistry::content([
            AppearanceDefinition::make('color')
                ->type(FieldType::COLOR)
                ->label('Background Color'),
            AppearanceDefinition::make('text_color')
                ->type(FieldType::COLOR)
                ->label('Text Color'),
            AppearanceDefinition::make('background_image')
                ->type(FieldType::IMAGE)
                ->label('Background Image'),
            AppearanceDefinition::make('layout')
                ->type(FieldType::SELECT)
                ->label('Layout')
                ->config(['options' => [
                    'full-width' => 'Full Width',
                    'boxed' => 'Boxed',
                    'sidebar-left' => 'Sidebar Left',
                    'sidebar-right' => 'Sidebar Right',
                ]]),
            AppearanceDefinition::make('custom_css_class')
                ->label('Custom CSS Class')
                ->hint('Applied to the page wrapper element.'),
        ]);

        SettingRegistry::register([
            SettingDefinition::make('site_name')
                ->label('Site Name')
                ->group('General')
                ->required(),
            SettingDefinition::make('site_description')
                ->type(FieldType::TEXTAREA)
                ->label('Site Description')
                ->group('General'),
            SettingDefinition::make('logo')
                ->type(FieldType::IMAGE)
                ->label('Site Logo')
                ->group('General')
                ->hint('Displayed in the header and footer.'),
            SettingDefinition::make('favicon')
                ->type(FieldType::IMAGE)
                ->label('Favicon')
                ->group('General')
                ->hint('Browser tab icon. Recommended: 32×32 or 64×64 PNG.'),
            SettingDefinition::make('locale')
                ->label('Locale')
                ->group('General')
                ->default('en')
                ->hint('Language code used in <html lang="">. E.g. en, cs, de, fr.'),
            SettingDefinition::make('contact_email')
                ->label('Contact Email')
                ->group('General')
                ->hint('Used in contact forms and transactional emails.')
                ->config(['email' => true]),

            SettingDefinition::make('meta_title')
                ->label('Meta Title')
                ->group('SEO')
                ->hint('Default page title used when no content-level title is set.'),
            SettingDefinition::make('meta_description')
                ->type(FieldType::TEXTAREA)
                ->label('Meta Description')
                ->group('SEO')
                ->hint('Default meta description (up to 160 characters).')
                ->config(['maxLength' => 160]),
            SettingDefinition::make('og_image')
                ->type(FieldType::IMAGE)
                ->label('OG Image')
                ->group('SEO')
                ->hint('Default Open Graph image for social sharing.'),
            SettingDefinition::make('google_site_verification')
                ->label('Google Site Verification')
                ->group('SEO')
                ->hint('Paste the content value from the Google Search Console meta tag.'),
            SettingDefinition::make('robots_txt')
                ->type(FieldType::TEXTAREA)
                ->label('robots.txt')
                ->group('SEO')
                ->hint('Leave empty for default (Allow all). Sitemap URL is always appended automatically.')
                ->config(['placeholder' => "User-agent: *\nAllow: /\n\nUser-agent: GPTBot\nDisallow: /"]),
            SettingDefinition::make('twitter_url')
                ->type(FieldType::URL)
                ->label('X / Twitter')
                ->group('Social'),
            SettingDefinition::make('linkedin_url')
                ->type(FieldType::URL)
                ->label('LinkedIn')
                ->group('Social'),
            SettingDefinition::make('facebook_url')
                ->type(FieldType::URL)
                ->label('Facebook')
                ->group('Social'),
            SettingDefinition::make('instagram_url')
                ->type(FieldType::URL)
                ->label('Instagram')
                ->group('Social'),
            SettingDefinition::make('github_url')
                ->type(FieldType::URL)
                ->label('GitHub')
                ->group('Social'),
        ]);

        BlockRegistry::register(
            TextBlock::definition(),
            HeroBlock::definition(),
            CardsBlock::definition(),
            ColumnsBlock::definition(),
            ButtonBlock::definition(),
            ButtonGroupBlock::definition(),
            DividerBlock::definition(),
        );

        NavigationRegistry::register(
            NavigationDefinition::make('header')->label('Header Navigation'),
            NavigationDefinition::make('footer')->label('Footer Navigation'),
        );

        foreach (glob(base_path('themes/*'), GLOB_ONLYDIR) as $themePath) {
            $themeName = basename($themePath);
            View::addNamespace("themes.{$themeName}", "{$themePath}/views");
        }

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['en', 'cs', 'sk', 'de', 'fr', 'es', 'it', 'pl', 'pt_BR', 'uk', 'nl', 'hu', 'ro', 'sv', 'tr', 'ja', 'zh_CN', 'ar', 'ru', 'ko', 'hi'])
                ->flags([
                    'en' => 'https://flagcdn.com/gb.svg',
                    'cs' => 'https://flagcdn.com/cz.svg',
                    'sk' => 'https://flagcdn.com/sk.svg',
                    'de' => 'https://flagcdn.com/de.svg',
                    'fr' => 'https://flagcdn.com/fr.svg',
                    'es' => 'https://flagcdn.com/es.svg',
                    'it' => 'https://flagcdn.com/it.svg',
                    'pl' => 'https://flagcdn.com/pl.svg',
                    'pt_BR' => 'https://flagcdn.com/br.svg',
                    'uk' => 'https://flagcdn.com/ua.svg',
                    'nl' => 'https://flagcdn.com/nl.svg',
                    'hu' => 'https://flagcdn.com/hu.svg',
                    'ro' => 'https://flagcdn.com/ro.svg',
                    'sv' => 'https://flagcdn.com/se.svg',
                    'tr' => 'https://flagcdn.com/tr.svg',
                    'ja' => 'https://flagcdn.com/jp.svg',
                    'zh_CN' => 'https://flagcdn.com/cn.svg',
                    'ar' => 'https://flagcdn.com/sa.svg',
                    'ru' => 'https://flagcdn.com/ru.svg',
                    'ko' => 'https://flagcdn.com/kr.svg',
                    'hi' => 'https://flagcdn.com/in.svg',
                ])
                ->circular()
                ->maxHeight('40rem')
                ->nativeLabel()
                ->visible(insidePanels: true, outsidePanels: true)
                ->userPreferredLocale(fn () => request()->getPreferredLanguage(['en', 'cs', 'sk', 'de', 'fr', 'es', 'it', 'pl', 'pt_BR', 'uk', 'nl', 'hu', 'ro', 'sv', 'tr', 'ja', 'zh_CN', 'ar', 'ru', 'ko', 'hi']));
        });
    }
}
