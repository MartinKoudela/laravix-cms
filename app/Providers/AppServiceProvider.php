<?php

namespace App\Providers;

use App\Enums\FieldType;
use App\Support\AppearanceDefinition;
use App\Support\AppearanceRegistry;
use App\Support\BlockDefinition;
use App\Support\BlockRegistry;
use App\Support\FieldComponentFactory;
use App\Support\FieldDefinition;
use App\Support\FieldRegistry;
use App\Support\SettingDefinition;
use App\Support\SettingRegistry;
use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
            BlockDefinition::make('text')
                ->label('Text')
                ->icon('heroicon-o-document-text')
                ->schema(fn () => [
                    TextInput::make('heading')->label(fn () => __('Heading'))->columnSpanFull(),
                    RichEditor::make('content')->label(fn () => __('Content'))->columnSpanFull(),
                ]),
            BlockDefinition::make('hero')
                ->label('Hero')
                ->icon('heroicon-o-photo')
                ->schema(fn () => [
                    TextInput::make('heading')->label(fn () => __('Heading'))->columnSpanFull(),
                    Textarea::make('subheading')->label(fn () => __('Subheading'))->columnSpanFull(),
                    FieldComponentFactory::mediaSelect('image_id', __('Image')),
                    TextInput::make('button_label')->label(fn () => __('Button Label')),
                    TextInput::make('button_url')->label(fn () => __('Button URL'))->url(),
                ]),
            BlockDefinition::make('cards')
                ->label('Cards')
                ->icon('heroicon-o-squares-2x2')
                ->schema(fn () => [
                    TextInput::make('heading')->label(fn () => __('Heading'))->columnSpanFull(),
                    Repeater::make('items')
                        ->label(fn () => __('Cards'))
                        ->schema([
                            TextInput::make('title')->label(fn () => __('Title')),
                            Textarea::make('description')->label(fn () => __('Description'))->columnSpanFull(),
                            FieldComponentFactory::mediaSelect('image_id', __('Image')),
                            TextInput::make('link')->label(fn () => __('Link'))->url(),
                        ])
                        ->columnSpanFull(),
                ]),
            BlockDefinition::make('columns')
                ->label('Columns')
                ->icon('heroicon-o-view-columns')
                ->schema(fn () => [
                    Repeater::make('columns')
                        ->label(fn () => __('Columns'))
                        ->schema([
                            RichEditor::make('content')->label(fn () => __('Content'))->columnSpanFull(),
                        ])
                        ->columnSpanFull(),
                ]),
            BlockDefinition::make('button')
                ->label('Button')
                ->icon('heroicon-o-cursor-arrow-rays')
                ->schema(fn () => [
                    TextInput::make('label')->label(fn () => __('Label')),
                    TextInput::make('url')->label(fn () => __('URL'))->url(),
                    Select::make('style')
                        ->label(fn () => __('Style'))
                        ->options(fn () => [
                            'primary' => __('Primary'),
                            'secondary' => __('Secondary'),
                            'outline' => __('Outline'),
                        ])
                        ->default('primary'),
                ]),
            BlockDefinition::make('divider')
                ->label('Divider')
                ->icon('heroicon-o-minus')
                ->schema([]),
        );

        foreach (glob(base_path('themes/*'), GLOB_ONLYDIR) as $themePath) {
            $themeName = basename($themePath);
            View::addNamespace("themes.{$themeName}", "{$themePath}/views");
        }

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['en', 'cs', 'sk', 'de', 'fr', 'es', 'it', 'pl', 'pt', 'uk', 'nl', 'hu', 'ro', 'sv', 'tr', 'ja', 'zh'])
                ->flags([
                    'en' => 'https://flagcdn.com/gb.svg',
                    'cs' => 'https://flagcdn.com/cz.svg',
                    'sk' => 'https://flagcdn.com/sk.svg',
                    'de' => 'https://flagcdn.com/de.svg',
                    'fr' => 'https://flagcdn.com/fr.svg',
                    'es' => 'https://flagcdn.com/es.svg',
                    'it' => 'https://flagcdn.com/it.svg',
                    'pl' => 'https://flagcdn.com/pl.svg',
                    'pt' => 'https://flagcdn.com/br.svg',
                    'uk' => 'https://flagcdn.com/ua.svg',
                    'nl' => 'https://flagcdn.com/nl.svg',
                    'hu' => 'https://flagcdn.com/hu.svg',
                    'ro' => 'https://flagcdn.com/ro.svg',
                    'sv' => 'https://flagcdn.com/se.svg',
                    'tr' => 'https://flagcdn.com/tr.svg',
                    'ja' => 'https://flagcdn.com/jp.svg',
                    'zh' => 'https://flagcdn.com/cn.svg',
                ])
                ->circular()
                ->nativeLabel()
                ->visible(insidePanels: true, outsidePanels: true)
                ->userPreferredLocale(fn () => request()->getPreferredLanguage(['en', 'cs', 'sk', 'de', 'fr', 'es', 'it', 'pl', 'pt', 'uk', 'nl', 'hu', 'ro', 'sv', 'tr', 'ja', 'zh']));
        });
    }
}
